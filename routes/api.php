<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CartController;
use App\Http\Controllers\Api\MemberController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\CheckoutController;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Legacy API routes (without v1 prefix for backward compatibility)
Route::middleware('web')->group(function () {
    Route::get('/cart/count', [CartController::class, 'count']);
});

// Public routes
Route::prefix('v1')->group(function () {
    // Authentication routes
    Route::post('/auth/login', [AuthController::class, 'login']);
    Route::post('/auth/register', [AuthController::class, 'register']);
    Route::post('/auth/password/reset', [AuthController::class, 'sendPasswordResetLink']);
    Route::post('/auth/password/reset/token', [AuthController::class, 'resetPassword']);

    // Member authentication routes
    Route::post('/member/auth/login', [MemberController::class, 'login']);
    Route::post('/member/auth/register', [MemberController::class, 'register']);
    Route::post('/member/auth/password/reset', [MemberController::class, 'sendPasswordResetLink']);
    Route::post('/member/auth/password/reset/token', [MemberController::class, 'resetPassword']);

    // Product routes (public)
    Route::get('/products', [ProductController::class, 'index']);
    Route::get('/products/featured', [ProductController::class, 'featured']);
    Route::get('/products/search', [ProductController::class, 'search']);
    Route::get('/products/categories', [ProductController::class, 'categories']);
    Route::get('/products/related/{id}', [ProductController::class, 'relatedById']);
    Route::get('/products/{product}', [ProductController::class, 'show']);
    Route::get('/products/{product}/related', [ProductController::class, 'related']);

    // Cart routes (public for guests) - with session support
    Route::middleware('web')->group(function () {
        Route::get('/cart', [CartController::class, 'index']);
        Route::get('/cart/count', [CartController::class, 'count']);
        Route::post('/cart/add', [CartController::class, 'add']);
        Route::put('/cart/items/{cartItem}', [CartController::class, 'update']);
        Route::delete('/cart/items/{cartItem}', [CartController::class, 'remove']);
        Route::delete('/cart/clear', [CartController::class, 'clear']);
        Route::post('/cart/coupon/apply', [CartController::class, 'applyCoupon']);
        Route::delete('/cart/coupon/remove', [CartController::class, 'removeCoupon']);
    });

    // ECPay payment routes
    Route::post('/orders/ecpay', [CheckoutController::class, 'createECPayOrder']);
});

// Protected routes (require authentication)
Route::prefix('v1')->middleware('auth:sanctum')->group(function () {
    // Authentication routes
    Route::post('/auth/logout', [AuthController::class, 'logout']);
    Route::post('/auth/refresh', [AuthController::class, 'refresh']);
    Route::get('/auth/me', [AuthController::class, 'me']);

    // Member authentication routes
    Route::post('/member/auth/logout', [MemberController::class, 'logout']);
    Route::post('/member/auth/refresh', [MemberController::class, 'refresh']);
    Route::get('/member/auth/me', [MemberController::class, 'me']);
    Route::put('/member/profile', [MemberController::class, 'updateProfile']);
    Route::get('/member/benefits', [MemberController::class, 'benefits']);
    Route::get('/member/orders', [MemberController::class, 'orders']);

    // User routes
    Route::get('/user/profile', [UserController::class, 'profile']);
    Route::put('/user/profile', [UserController::class, 'updateProfile']);
    Route::get('/user/addresses', [UserController::class, 'addresses']);
    Route::post('/user/addresses', [UserController::class, 'storeAddress']);
    Route::put('/user/addresses/{address}', [UserController::class, 'updateAddress']);
    Route::delete('/user/addresses/{address}', [UserController::class, 'deleteAddress']);
    Route::get('/user/coupons', [UserController::class, 'coupons']);
    Route::get('/user/orders', [UserController::class, 'orders']);

    // Order routes
    Route::post('/orders', [OrderController::class, 'store']);
    Route::get('/orders', [OrderController::class, 'index']);
    Route::get('/orders/{order}', [OrderController::class, 'show']);
    Route::put('/orders/{order}/cancel', [OrderController::class, 'cancel']);
});

// Fallback route
Route::fallback(function () {
    return response()->json([
        'success' => false,
        'message' => 'API endpoint not found',
    ], 404);
});
