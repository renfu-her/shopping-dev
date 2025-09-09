<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Shopping Store')</title>

    <!-- Bootstrap 5 CSS -->
    <link href="{{ asset('assets/css/vendor/bootstrap.min.css') }}" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ asset('assets/css/vendor/font-awesome.min.css') }}">
    
    <!-- Swiper CSS -->
    <link rel="stylesheet" href="{{ asset('assets/css/plugins/swiper.css') }}">
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ asset('assets/css/custom.css?v=' . time()) }}">
    
    <!-- Page-specific CSS -->
    <style>
        :root {
            --primary-color: #007bff;
            --secondary-color: #6c757d;
            --success-color: #28a745;
            --danger-color: #dc3545;
            --warning-color: #ffc107;
            --info-color: #17a2b8;
            --light-color: #f8f9fa;
            --dark-color: #343a40;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
        }

        .navbar-brand {
            font-weight: bold;
            font-size: 1.5rem;
        }

        .navbar-nav .nav-link {
            font-weight: 500;
            transition: color 0.3s ease;
        }

        .navbar-nav .nav-link:hover {
            color: var(--primary-color) !important;
        }

        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            background-color: #0056b3;
            border-color: #0056b3;
            transform: translateY(-1px);
        }

        .card {
            border: none;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 20px rgba(0,0,0,0.15);
        }

        .product-card {
            height: 100%;
        }

        .product-card img {
            height: 200px;
            object-fit: cover;
        }

        .price {
            font-size: 1.25rem;
            font-weight: bold;
            color: var(--success-color);
        }

        .footer {
            background-color: var(--dark-color);
            color: white;
            padding: 3rem 0 1rem;
            margin-top: auto;
        }

        .footer h5 {
            color: var(--primary-color);
            margin-bottom: 1rem;
        }

        .footer a {
            color: #ccc;
            text-decoration: none;
            transition: color 0.3s ease;
        }

        .footer a:hover {
            color: var(--primary-color);
        }

        .social-links a {
            display: inline-block;
            width: 40px;
            height: 40px;
            background-color: var(--primary-color);
            color: white;
            text-align: center;
            line-height: 40px;
            border-radius: 50%;
            margin-right: 10px;
            transition: all 0.3s ease;
        }

        .social-links a:hover {
            background-color: #0056b3;
            transform: translateY(-2px);
        }

        .main-content {
            min-height: calc(100vh - 200px);
        }

        .loading {
            display: none;
        }

        .loading.show {
            display: block;
        }

        .toast-container {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 1055;
        }

        .quantity-input {
            width: 80px;
            text-align: center;
        }

        .cart-badge {
            position: absolute;
            top: -8px;
            right: -8px;
            background-color: var(--danger-color);
            color: white;
            border-radius: 50%;
            width: 20px;
            height: 20px;
            font-size: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .navbar-nav .nav-item {
            position: relative;
        }

        .navbar-nav .nav-item .cart-badge {
            position: absolute;
            top: -5px;
            right: -5px;
        }

        @media (max-width: 768px) {
            .navbar-brand {
                font-size: 1.25rem;
            }
            
            .product-card img {
                height: 150px;
            }
            
            .footer {
                padding: 2rem 0 1rem;
            }
        }
    </style>

    @stack('styles')
</head>
<body class="d-flex flex-column min-vh-100">
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
        <div class="container">
            <a class="navbar-brand" href="{{ route('home') }}">
                <i class="fas fa-shopping-bag me-2"></i>
                Shopping Store
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('home') }}">
                            <i class="fas fa-home me-1"></i>Home
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('products') }}">
                            <i class="fas fa-box me-1"></i>Products
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('about') }}">
                            <i class="fas fa-info-circle me-1"></i>About Us
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('contact') }}">
                            <i class="fas fa-envelope me-1"></i>Contact
                        </a>
                    </li>
                </ul>
                
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link position-relative" href="{{ auth()->guard('member')->check() ? route('cart') : route('auth') }}">
                            <i class="fas fa-shopping-cart me-1"></i>Cart
                            <span class="cart-badge" id="cart-badge">0</span>
                        </a>
                    </li>
                    
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('auth') }}">
                            @auth('member')
                                <i class="fas fa-user me-1"></i>{{ Auth::guard('member')->user()->name }}
                            @else
                                <i class="fas fa-sign-in-alt me-1"></i>Login
                            @endauth
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="main-content">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="row">
                <div class="col-lg-4 col-md-6 mb-4">
                    <h5><i class="fas fa-shopping-bag me-2"></i>Shopping Store</h5>
                    <p>Your one-stop destination for quality products at great prices. We're committed to providing excellent customer service and fast delivery.</p>
                    <div class="social-links">
                        <a href="#"><i class="fab fa-facebook-f"></i></a>
                        <a href="#"><i class="fab fa-twitter"></i></a>
                        <a href="#"><i class="fab fa-instagram"></i></a>
                        <a href="#"><i class="fab fa-linkedin-in"></i></a>
                    </div>
                </div>
                
                <div class="col-lg-2 col-md-6 mb-4">
                    <h5>Quick Links</h5>
                    <ul class="list-unstyled">
                        <li><a href="{{ route('home') }}">Home</a></li>
                        <li><a href="{{ route('products') }}">Products</a></li>
                        <li><a href="{{ auth()->guard('member')->check() ? route('cart') : route('auth') }}">Cart</a></li>
                        <li><a href="{{ auth()->guard('member')->check() ? route('checkout') : route('auth') }}">Checkout</a></li>
                    </ul>
                </div>
                
                <div class="col-lg-2 col-md-6 mb-4">
                    <h5>Customer Service</h5>
                    <ul class="list-unstyled">
                        <li><a href="{{ route('about') }}">About Us</a></li>
                        <li><a href="{{ route('contact') }}">Contact Us</a></li>
                        <li><a href="#">FAQ</a></li>
                        <li><a href="#">Shipping Info</a></li>
                    </ul>
                </div>
                
                <div class="col-lg-4 col-md-6 mb-4">
                    <h5>Contact Information</h5>
                    <ul class="list-unstyled">
                        <li><i class="fas fa-map-marker-alt me-2"></i>123 Shopping Street, City, State 12345</li>
                        <li><i class="fas fa-phone me-2"></i>+1 (555) 123-4567</li>
                        <li><i class="fas fa-envelope me-2"></i>info@shoppingstore.com</li>
                        <li><i class="fas fa-clock me-2"></i>Mon - Fri: 9:00 AM - 6:00 PM</li>
                    </ul>
                </div>
            </div>
            
            <hr class="my-4">
            
            <div class="row align-items-center">
                <div class="col-md-6">
                    <p class="mb-0">&copy; {{ date('Y') }} Shopping Store. All rights reserved.</p>
                </div>
                <div class="col-md-6 text-md-end">
                    <a href="#" class="me-3">Privacy Policy</a>
                    <a href="#">Terms of Service</a>
                </div>
            </div>
        </div>
    </footer>

    <!-- Toast Container -->
    <div class="toast-container"></div>

    <!-- jQuery & Bootstrap JS (Combined Vendor File) -->
    <script src="{{ asset('assets/js/vendor/vendor.min.js') }}"></script>
    
    <!-- Swiper JS -->
    <script src="{{ asset('assets/js/plugins/plugins.min.js') }}"></script>

    <!-- Global JavaScript -->
    <script>
        // CSRF Token setup
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        // Global cart functions
        function updateCartBadge() {
            $.get('/api/cart/count')
                .done(function(response) {
                    $('#cart-badge').text(response.count || 0);
                })
                .fail(function() {
                    console.error('Failed to update cart badge');
                });
        }

        function showToast(message, type = 'success') {
            const toastHtml = `
                <div class="toast align-items-center text-white bg-${type} border-0" role="alert">
                    <div class="d-flex">
                        <div class="toast-body">
                            <i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-circle'} me-2"></i>
                            ${message}
                        </div>
                        <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
                    </div>
                </div>
            `;
            
            $('.toast-container').append(toastHtml);
            const toast = new bootstrap.Toast($('.toast-container .toast:last'));
            toast.show();
            
            // Remove toast element after it's hidden
            $('.toast-container .toast:last').on('hidden.bs.toast', function() {
                $(this).remove();
            });
        }

        function showLoading(element) {
            $(element).addClass('loading show');
        }

        function hideLoading(element) {
            $(element).removeClass('loading show');
        }

        // Initialize cart badge on page load
        $(document).ready(function() {
            updateCartBadge();
        });
    </script>

    @stack('scripts')
</body>
</html>
