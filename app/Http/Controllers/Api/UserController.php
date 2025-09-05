<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\UpdateProfileRequest;
use App\Http\Requests\Api\StoreAddressRequest;
use App\Http\Requests\Api\UpdateAddressRequest;
use App\Http\Resources\AddressResource;
use App\Http\Resources\CouponResource;
use App\Http\Resources\OrderResource;
use App\Models\Address;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class UserController extends Controller
{
    /**
     * Get user profile
     */
    public function profile(Request $request): JsonResponse
    {
        $user = $request->user();

        return response()->json([
            'success' => true,
            'data' => [
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'email_verified_at' => $user->email_verified_at,
                    'created_at' => $user->created_at,
                    'updated_at' => $user->updated_at,
                ],
            ],
        ]);
    }

    /**
     * Update user profile
     */
    public function updateProfile(UpdateProfileRequest $request): JsonResponse
    {
        $user = $request->user();
        $user->update($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Profile updated successfully',
            'data' => [
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'email_verified_at' => $user->email_verified_at,
                    'created_at' => $user->created_at,
                    'updated_at' => $user->updated_at,
                ],
            ],
        ]);
    }

    /**
     * Get user addresses
     */
    public function addresses(Request $request): AnonymousResourceCollection
    {
        $addresses = $request->user()->addresses()->orderBy('created_at', 'desc')->get();

        return AddressResource::collection($addresses);
    }

    /**
     * Store new address
     */
    public function storeAddress(StoreAddressRequest $request): JsonResponse
    {
        $user = $request->user();
        $address = $user->addresses()->create($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Address created successfully',
            'data' => new AddressResource($address),
        ], 201);
    }

    /**
     * Update address
     */
    public function updateAddress(UpdateAddressRequest $request, Address $address): JsonResponse
    {
        // Verify address belongs to user
        if ($address->user_id !== $request->user()->id) {
            return response()->json([
                'success' => false,
                'message' => 'Address not found',
            ], 404);
        }

        $address->update($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Address updated successfully',
            'data' => new AddressResource($address),
        ]);
    }

    /**
     * Delete address
     */
    public function deleteAddress(Request $request, Address $address): JsonResponse
    {
        // Verify address belongs to user
        if ($address->user_id !== $request->user()->id) {
            return response()->json([
                'success' => false,
                'message' => 'Address not found',
            ], 404);
        }

        $address->delete();

        return response()->json([
            'success' => true,
            'message' => 'Address deleted successfully',
        ]);
    }

    /**
     * Get user coupons
     */
    public function coupons(Request $request): AnonymousResourceCollection
    {
        $user = $request->user();
        
        // Get available coupons for user
        $coupons = \App\Models\Coupon::where('is_active', true)
            ->where(function ($query) {
                $query->whereNull('valid_from')
                      ->orWhere('valid_from', '<=', now());
            })
            ->where(function ($query) {
                $query->whereNull('valid_until')
                      ->orWhere('valid_until', '>=', now());
            })
            ->where(function ($query) {
                $query->whereNull('usage_limit')
                      ->orWhereRaw('used_count < usage_limit');
            })
            ->orderBy('created_at', 'desc')
            ->get();

        return CouponResource::collection($coupons);
    }

    /**
     * Get user orders
     */
    public function orders(Request $request): AnonymousResourceCollection
    {
        $orders = $request->user()->orders()
            ->with(['items.product', 'items.product.images'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return OrderResource::collection($orders);
    }
}
