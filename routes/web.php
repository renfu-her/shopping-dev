<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ContactController;

Route::get('/', function () {
    return view('home');
})->name('home');

// Authentication Routes
Route::get('/auth', [AuthController::class, 'index'])->name('auth');
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);

// Products page (placeholder)
Route::get('/products', function () {
    return view('products');
})->name('products');

// Protected routes for members only
Route::middleware(['auth:member', 'throttle:60,1'])->group(function () {
    // Cart page (requires member authentication)
    Route::get('/cart', function () {
        return view('cart');
    })->name('cart');
    
    // Checkout page (requires member authentication)
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout');
    
    // Checkout success page
    Route::get('/checkout/success/{order}', [CheckoutController::class, 'success'])->name('checkout.success');
});

// ECPay payment routes (no authentication required for return URL)
Route::post('/checkout/ecpay/return', [CheckoutController::class, 'handleECPayReturn'])->name('checkout.ecpay.return');

// Product detail page
Route::get('/product/{id}', function ($id) {
    return view('product-detail', ['product_id' => $id]);
})->name('product.detail');

// About Us page
Route::get('/about', function () {
    return view('about');
})->name('about');

// Contact Us page
Route::get('/contact', function () {
    return view('contact');
})->name('contact');

// Contact form submission
Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');
