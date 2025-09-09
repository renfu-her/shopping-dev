<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('home');
})->name('home');

// Authentication Routes
Route::get('/auth', [App\Http\Controllers\AuthController::class, 'index'])->name('auth');
Route::get('/login', [App\Http\Controllers\AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [App\Http\Controllers\AuthController::class, 'login']);
Route::post('/logout', [App\Http\Controllers\AuthController::class, 'logout'])->name('logout');
Route::get('/register', [App\Http\Controllers\AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [App\Http\Controllers\AuthController::class, 'register']);

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
    Route::get('/checkout', function () {
        return view('checkout');
    })->name('checkout');
});

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
Route::post('/contact', [App\Http\Controllers\ContactController::class, 'store'])->name('contact.store');
