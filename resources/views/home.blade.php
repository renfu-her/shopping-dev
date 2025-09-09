@extends('layout.app')

@section('title', 'Shopping Store - Home')


@section('content')
<!-- Hero Section -->
<section class="hero-section">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <h1 class="hero-title">Welcome to Shopping Store</h1>
                <p class="hero-subtitle">Discover amazing products at unbeatable prices. Shop with confidence and enjoy fast, secure delivery.</p>
                <div class="d-flex gap-3">
                    <a href="{{ route('products') }}" class="btn btn-light btn-lg">
                        <i class="fas fa-shopping-bag me-2"></i>Shop Now
                    </a>
                    <a href="{{ route('about') }}" class="btn btn-outline-light btn-lg">
                        <i class="fas fa-info-circle me-2"></i>Learn More
                    </a>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="swiper hero-swiper">
                    <div class="swiper-wrapper">
                        <div class="swiper-slide">
                            <img src="{{ asset('assets/images/slider-image/sample-1.jpg') }}" alt="Hero Image 1" class="img-fluid rounded">
                        </div>
                        <div class="swiper-slide">
                            <img src="{{ asset('assets/images/slider-image/sample-2.jpg') }}" alt="Hero Image 2" class="img-fluid rounded">
                        </div>
                        <div class="swiper-slide">
                            <img src="{{ asset('assets/images/slider-image/sample-3.jpg') }}" alt="Hero Image 3" class="img-fluid rounded">
                        </div>
                    </div>
                    <div class="swiper-pagination"></div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Categories Section -->
<section class="py-5">
    <div class="container">
        <h2 class="section-title">Shop by Category</h2>
        <div class="row g-4">
            <div class="col-md-4">
                <div class="category-card">
                    <div class="feature-icon">
                        <i class="fas fa-laptop fa-2x"></i>
                    </div>
                    <h4>Electronics</h4>
                    <p>Latest gadgets and electronics</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="category-card">
                    <div class="feature-icon">
                        <i class="fas fa-tshirt fa-2x"></i>
                    </div>
                    <h4>Fashion</h4>
                    <p>Trendy clothing and accessories</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="category-card">
                    <div class="feature-icon">
                        <i class="fas fa-home fa-2x"></i>
                    </div>
                    <h4>Home & Garden</h4>
                    <p>Everything for your home</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Featured Products Section -->
<section class="py-5 bg-light">
    <div class="container">
        <h2 class="section-title">Featured Products</h2>
        <div id="featured-products-loading" class="text-center py-5">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            <p class="mt-2">Loading featured products...</p>
        </div>
        <div id="featured-products" class="row g-4">
            <!-- Featured products will be loaded here -->
        </div>
    </div>
</section>

<!-- Features Section -->
<section class="py-5">
    <div class="container">
        <div class="row g-4">
            <div class="col-md-3 text-center">
                <div class="feature-icon">
                    <i class="fas fa-shipping-fast fa-2x"></i>
                </div>
                <h5>Free Shipping</h5>
                <p class="text-muted">Free shipping on orders over $50</p>
            </div>
            <div class="col-md-3 text-center">
                <div class="feature-icon">
                    <i class="fas fa-undo fa-2x"></i>
                </div>
                <h5>Easy Returns</h5>
                <p class="text-muted">30-day return policy</p>
            </div>
            <div class="col-md-3 text-center">
                <div class="feature-icon">
                    <i class="fas fa-shield-alt fa-2x"></i>
                </div>
                <h5>Secure Payment</h5>
                <p class="text-muted">100% secure payment</p>
            </div>
            <div class="col-md-3 text-center">
                <div class="feature-icon">
                    <i class="fas fa-headset fa-2x"></i>
                </div>
                <h5>24/7 Support</h5>
                <p class="text-muted">Round-the-clock customer support</p>
            </div>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
    class HomePage {
        constructor() {
            this.apiBaseUrl = '/api/v1';
            this.csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            this.init();
        }

        init() {
            this.initializeSwiper();
            this.loadFeaturedProducts();
        }

        initializeSwiper() {
            // Hero Swiper
            new Swiper('.hero-swiper', {
                loop: true,
                autoplay: {
                    delay: 3000,
                    disableOnInteraction: false,
                },
                pagination: {
                    el: '.swiper-pagination',
                    clickable: true,
                },
            });
        }

        async loadFeaturedProducts() {
            try {
                const response = await fetch(`${this.apiBaseUrl}/products/featured`);
                if (response.ok) {
                    const data = await response.json();
                    // ProductResource collection returns data directly, not wrapped in data.data
                    this.renderFeaturedProducts(data.data || data || []);
                } else {
                    throw new Error('Failed to load featured products');
                }
            } catch (error) {
                console.error('Error loading featured products:', error);
                this.showFeaturedProductsError();
            } finally {
                document.getElementById('featured-products-loading').classList.add('d-none');
            }
        }

        renderFeaturedProducts(products) {
            const container = document.getElementById('featured-products');
            container.innerHTML = '';

            if (products.length === 0) {
                container.innerHTML = `
                    <div class="col-12 text-center py-5">
                        <i class="fas fa-box fa-3x text-muted mb-3"></i>
                        <h4>No Featured Products</h4>
                        <p class="text-muted">Check back later for amazing products!</p>
                    </div>
                `;
                return;
            }

            products.forEach(product => {
                const productCard = this.createProductCard(product);
                container.appendChild(productCard);
            });
        }

        createProductCard(product) {
            const col = document.createElement('div');
            col.className = 'col-lg-3 col-md-6';
            
            const discount = product.discount || 0;
            const originalPrice = parseFloat(product.price);
            const salePrice = originalPrice * (1 - discount / 100);
            
            col.innerHTML = `
                <div class="card product-card h-100 position-relative">
                    ${discount > 0 ? `<div class="badge-sale">-${discount}%</div>` : ''}
                    <img src="${product.image || '/assets/images/product-image/1.jpg'}" 
                         class="card-img-top product-image" 
                         alt="${product.name}"
                         onerror="this.src='/assets/images/product-image/1.jpg'">
                    <div class="card-body d-flex flex-column">
                        <h6 class="card-title">${product.name}</h6>
                        <p class="card-text text-muted small flex-grow-1">${product.description || ''}</p>
                        <div class="mt-auto">
                            <div class="d-flex align-items-center mb-3">
                                <span class="price">$${salePrice.toFixed(2)}</span>
                                ${discount > 0 ? `<span class="old-price ms-2">$${originalPrice.toFixed(2)}</span>` : ''}
                            </div>
                            <div class="d-grid gap-2">
                                <button class="btn btn-primary btn-sm" onclick="homePage.addToCart(${product.id})">
                                    <i class="fas fa-shopping-cart me-1"></i>Add to Cart
                                </button>
                                <a href="/product/${product.id}" class="btn btn-outline-primary btn-sm">
                                    <i class="fas fa-eye me-1"></i>View Details
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            `;
            
            return col;
        }

        showFeaturedProductsError() {
            const container = document.getElementById('featured-products');
            container.innerHTML = `
                <div class="col-12 text-center py-5">
                    <i class="fas fa-exclamation-triangle fa-3x text-warning mb-3"></i>
                    <h4>Unable to Load Products</h4>
                    <p class="text-muted">Please try again later.</p>
                    <button class="btn btn-primary" onclick="homePage.loadFeaturedProducts()">
                        <i class="fas fa-refresh me-1"></i>Retry
                    </button>
                </div>
            `;
        }

        async addToCart(productId) {
            // Check if user is authenticated as member
            const isAuthenticated = @json(auth()->guard('member')->check());
            
            if (!isAuthenticated) {
                showToast('Please login to add items to cart', 'warning');
                setTimeout(() => {
                    window.location.href = '{{ route("auth") }}';
                }, 1500);
                return;
            }

            try {
                const response = await fetch(`${this.apiBaseUrl}/cart/add`, {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': this.csrfToken
                    },
                    body: JSON.stringify({
                        product_id: productId,
                        quantity: 1
                    })
                });

                if (response.ok) {
                    showToast('Product added to cart!', 'success');
                    updateCartBadge();
                } else {
                    const error = await response.json();
                    showToast(error.message || 'Error adding product to cart', 'danger');
                }
            } catch (error) {
                console.error('Error adding to cart:', error);
                showToast('Error adding product to cart', 'danger');
            }
        }
    }

    // Initialize homepage when DOM is loaded
    let homePage;
    document.addEventListener('DOMContentLoaded', function() {
        homePage = new HomePage();
    });
</script>
@endpush