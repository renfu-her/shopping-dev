<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\MemberLoginRequest;
use App\Http\Requests\Api\MemberRegisterRequest;
use App\Http\Requests\Api\UpdateMemberProfileRequest;
use App\Http\Resources\MemberResource;
use App\Models\Member;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class MemberController extends Controller
{
    /**
     * Login member and return token
     */
    public function login(MemberLoginRequest $request): JsonResponse
    {
        $credentials = $request->only('email', 'password');
        $remember = $request->boolean('remember', false);

        if (!Auth::guard('member')->attempt($credentials, $remember)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        $member = Auth::guard('member')->user();
        $token = $member->createToken('member-auth-token')->plainTextToken;

        // Update last login
        $member->update(['last_login_at' => now()]);

        return response()->json([
            'success' => true,
            'message' => 'Login successful',
            'data' => [
                'member' => new MemberResource($member),
                'token' => $token,
                'token_type' => 'Bearer',
            ],
        ]);
    }

    /**
     * Register new member
     */
    public function register(MemberRegisterRequest $request): JsonResponse
    {
        $member = Member::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'phone' => $request->phone,
            'date_of_birth' => $request->date_of_birth,
            'gender' => $request->gender,
            'address' => $request->address,
            'city' => $request->city,
            'state' => $request->state,
            'postal_code' => $request->postal_code,
            'country' => $request->country,
            'membership_type' => 'basic',
            'membership_status' => 'active',
            'membership_start_date' => now(),
            'membership_end_date' => now()->addYear(),
        ]);

        // Send email verification if needed
        if (config('auth.verify_email')) {
            $member->sendEmailVerificationNotification();
        }

        $token = $member->createToken('member-auth-token')->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'Registration successful',
            'data' => [
                'member' => new MemberResource($member),
                'token' => $token,
                'token_type' => 'Bearer',
            ],
        ], 201);
    }

    /**
     * Logout member and revoke token
     */
    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'success' => true,
            'message' => 'Logout successful',
        ]);
    }

    /**
     * Get authenticated member profile
     */
    public function me(Request $request): JsonResponse
    {
        $member = $request->user();

        return response()->json([
            'success' => true,
            'data' => [
                'member' => new MemberResource($member),
            ],
        ]);
    }

    /**
     * Update member profile
     */
    public function updateProfile(UpdateMemberProfileRequest $request): JsonResponse
    {
        $member = $request->user();
        $member->update($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Profile updated successfully',
            'data' => [
                'member' => new MemberResource($member->fresh()),
            ],
        ]);
    }

    /**
     * Send password reset link
     */
    public function sendPasswordResetLink(Request $request): JsonResponse
    {
        $request->validate([
            'email' => 'required|email|exists:members,email',
        ]);

        $status = Password::broker('members')->sendResetLink(
            $request->only('email')
        );

        if ($status === Password::RESET_LINK_SENT) {
            return response()->json([
                'success' => true,
                'message' => 'Password reset link sent to your email',
            ]);
        }

        throw ValidationException::withMessages([
            'email' => [trans($status)],
        ]);
    }

    /**
     * Reset password with token
     */
    public function resetPassword(Request $request): JsonResponse
    {
        $request->validate([
            'token' => 'required|string',
            'email' => 'required|email|exists:members,email',
            'password' => 'required|string|min:8|confirmed',
            'password_confirmation' => 'required|string',
        ]);

        $status = Password::broker('members')->reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function (Member $member, string $password) {
                $member->forceFill([
                    'password' => Hash::make($password),
                ])->setRememberToken(Str::random(60));

                $member->save();

                event(new PasswordReset($member));
            }
        );

        if ($status === Password::PASSWORD_RESET) {
            return response()->json([
                'success' => true,
                'message' => 'Password reset successful',
            ]);
        }

        throw ValidationException::withMessages([
            'email' => [trans($status)],
        ]);
    }

    /**
     * Refresh token
     */
    public function refresh(Request $request): JsonResponse
    {
        $member = $request->user();
        
        // Revoke current token
        $request->user()->currentAccessToken()->delete();
        
        // Create new token
        $token = $member->createToken('member-auth-token')->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'Token refreshed successfully',
            'data' => [
                'token' => $token,
                'token_type' => 'Bearer',
            ],
        ]);
    }

    /**
     * Get member's membership benefits
     */
    public function benefits(Request $request): JsonResponse
    {
        $member = $request->user();

        return response()->json([
            'success' => true,
            'data' => [
                'membership_level' => $member->membership_level,
                'membership_type' => $member->membership_type,
                'benefits' => $member->membership_benefits,
                'points_balance' => $member->points_balance,
                'total_spent' => $member->total_spent,
            ],
        ]);
    }

    /**
     * Get member's order history
     */
    public function orders(Request $request): JsonResponse
    {
        $member = $request->user();
        $orders = $member->orders()
            ->with(['items.product', 'items.product.images'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return response()->json([
            'success' => true,
            'data' => $orders,
        ]);
    }
}
