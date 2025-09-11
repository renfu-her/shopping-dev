@extends('layout.app')

@section('title', 'Shopping Cart - ' . config('app.name'))


@section('content')

    <!-- Main Content -->
    <div class="container my-5">
        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Shopping Cart</li>
            </ol>
        </nav>

        <!-- Page Title -->
        <div class="row mb-4">
            <div class="col-12">
                <h1 class="h2 mb-0">
                    <i class="fas fa-shopping-cart me-2"></i>Shopping Cart
                </h1>
                <p class="text-muted">Review your items and proceed to checkout</p>
            </div>
        </div>

        <!-- Cart Content -->
        <div id="cart-content">
            <!-- Loading Spinner -->
            <div id="cart-loading" class="text-center py-5">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <p class="mt-2">Loading your cart...</p>
            </div>

            <!-- Empty Cart -->
            <div id="empty-cart" class="empty-cart d-none">
                <i class="fas fa-shopping-cart"></i>
                <h3>Your cart is empty</h3>
                <p class="text-muted mb-4">Looks like you haven't added any items to your cart yet.</p>
                <a href="{{ url('/products') }}" class="btn btn-primary btn-lg">
                    <i class="fas fa-shopping-bag me-2"></i>Start Shopping
                </a>
            </div>

            <!-- Cart Items -->
            <div id="cart-items" class="d-none">
                <div class="row">
                    <!-- Cart Items Column -->
                    <div class="col-lg-8">
                        <div class="card">
                            <div class="card-header bg-white">
                                <h5 class="mb-0">
                                    <i class="fas fa-list me-2"></i>Cart Items
                                </h5>
                            </div>
                            <div class="card-body p-0">
                                <div id="cart-items-list">
                                    <!-- Cart items will be loaded here -->
                                </div>
                            </div>
                        </div>

                        <!-- Cart Actions -->
                        <div class="row mt-3">
                            <div class="col-md-6">
                                <a href="{{ url('/products') }}" class="btn btn-outline-primary">
                                    <i class="fas fa-arrow-left me-2"></i>Continue Shopping
                                </a>
                            </div>
                            <div class="col-md-6 text-end">
                                <button id="update-cart" class="btn btn-secondary me-2">
                                    <i class="fas fa-sync me-2"></i>Update Cart
                                </button>
                                <button id="clear-cart" class="btn btn-outline-danger">
                                    <i class="fas fa-trash me-2"></i>Clear Cart
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Cart Summary Column -->
                    <div class="col-lg-4">
                        <!-- Coupon Section -->
                        <div class="coupon-section mb-4">
                            <h6 class="mb-3">
                                <i class="fas fa-ticket-alt me-2"></i>Coupon Code
                            </h6>
                            <div class="input-group">
                                <input type="text" id="coupon-code" class="form-control" placeholder="Enter coupon code">
                                <button id="apply-coupon" class="btn btn-outline-primary" type="button">
                                    Apply
                                </button>
                            </div>
                            <div id="coupon-message" class="mt-2"></div>
                        </div>

                        <!-- Cart Summary -->
                        <div class="cart-summary">
                            <h6 class="mb-3">
                                <i class="fas fa-calculator me-2"></i>Order Summary
                            </h6>
                            
                            <div class="d-flex justify-content-between mb-2">
                                <span>Subtotal:</span>
                                <span id="subtotal">$0.00</span>
                            </div>
                            
                            <div class="d-flex justify-content-between mb-2">
                                <span>Discount:</span>
                                <span id="discount" class="text-success">-$0.00</span>
                            </div>
                            
                            <div class="d-flex justify-content-between mb-2">
                                <span>Shipping:</span>
                                <span id="shipping">$0.00</span>
                            </div>
                            
                            <hr>
                            
                            <div class="d-flex justify-content-between mb-3">
                                <strong>Total:</strong>
                                <strong id="total">$0.00</strong>
                            </div>
                            
                            <button id="checkout-btn" class="btn btn-primary w-100 btn-lg">
                                <i class="fas fa-credit-card me-2"></i>Proceed to Checkout
                            </button>
                            
                            <div class="text-center mt-3">
                                <small class="text-muted">
                                    <i class="fas fa-shield-alt me-1"></i>
                                    Secure checkout with SSL encryption
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Related Products Section -->
        <div id="related-products" class="mt-5 d-none">
            <h4 class="mb-4">
                <i class="fas fa-heart me-2"></i>You might also like
            </h4>
            <div class="swiper related-products-swiper">
                <div class="swiper-wrapper" id="related-products-list">
                    <!-- Related products will be loaded here -->
                </div>
                <div class="swiper-pagination"></div>
            </div>
        </div>
    </div>

@endsection

@push('scripts')

    <script>
        class ShoppingCart {
            constructor() {
                this.cartData = null;
                this.apiBaseUrl = '/api/v1';
                this.csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                this.init();
            }

            init() {
                this.setupEventListeners();
                this.loadCart();
                this.initializeSwiper();
            }

            setupEventListeners() {
                // Update cart button
                document.getElementById('update-cart').addEventListener('click', () => {
                    this.updateCart();
                });

                // Clear cart button
                document.getElementById('clear-cart').addEventListener('click', () => {
                    this.clearCart();
                });

                // Apply coupon button
                document.getElementById('apply-coupon').addEventListener('click', () => {
                    this.applyCoupon();
                });

                // Checkout button
                document.getElementById('checkout-btn').addEventListener('click', () => {
                    this.proceedToCheckout();
                });

                // Coupon input enter key
                document.getElementById('coupon-code').addEventListener('keypress', (e) => {
                    if (e.key === 'Enter') {
                        this.applyCoupon();
                    }
                });
            }

            async loadCart() {
                try {
                    this.showLoading(true);
                    const response = await fetch(`${this.apiBaseUrl}/cart`, {
                        method: 'GET',
                        headers: {
                            'Accept': 'application/json',
                            'Content-Type': 'application/json',
                        }
                    });

                    if (response.ok) {
                        const result = await response.json();
                        this.cartData = result.data; // Extract data from API response
                        console.log('Cart data:', this.cartData); // Debug log
                        this.renderCart();
                        this.loadRelatedProducts();
                    } else {
                        throw new Error('Failed to load cart');
                    }
                } catch (error) {
                    console.error('Error loading cart:', error);
                    this.showToast('Error loading cart', 'error');
                } finally {
                    this.showLoading(false);
                }
            }

            renderCart() {
                if (!this.cartData || !this.cartData.items || this.cartData.items.length === 0) {
                    this.showEmptyCart();
                    return;
                }

                this.showCartItems();
                this.renderCartItems();
                this.updateCartSummary();
                this.updateCartCount();
            }

            showEmptyCart() {
                document.getElementById('cart-loading').classList.add('d-none');
                document.getElementById('empty-cart').classList.remove('d-none');
                document.getElementById('cart-items').classList.add('d-none');
                document.getElementById('related-products').classList.add('d-none');
            }

            showCartItems() {
                document.getElementById('cart-loading').classList.add('d-none');
                document.getElementById('empty-cart').classList.add('d-none');
                document.getElementById('cart-items').classList.remove('d-none');
                document.getElementById('related-products').classList.remove('d-none');
            }

            renderCartItems() {
                const cartItemsList = document.getElementById('cart-items-list');
                cartItemsList.innerHTML = '';

                this.cartData.items.forEach(item => {
                    const cartItem = this.createCartItemElement(item);
                    cartItemsList.appendChild(cartItem);
                });
            }

            createCartItemElement(item) {
                const div = document.createElement('div');
                div.className = 'cart-item border-bottom p-3';
                div.innerHTML = `
                    <div class="row align-items-center">
                        <div class="col-md-2">
                            <img src="${item.product.image || '/assets/images/product-image/1.jpg'}" 
                                 alt="${item.product.name}" 
                                 class="product-image"
                                 style="width: 80px; height: 80px; object-fit: cover; border-radius: 8px;"
                                 onerror="this.src='/assets/images/product-image/1.jpg'">
                        </div>
                        <div class="col-md-4">
                            <h6 class="mb-1">${item.product.name}</h6>
                            <small class="text-muted">${item.product.short_description || item.product.description || ''}</small>
                        </div>
                        <div class="col-md-2">
                            <span class="fw-bold">$${parseFloat(item.price).toFixed(2)}</span>
                        </div>
                        <div class="col-md-2">
                            <div class="input-group">
                                <button class="btn btn-outline-secondary btn-sm" 
                                        type="button" 
                                        onclick="cart.updateQuantity(${item.id}, ${item.quantity - 1})">
                                    <i class="fas fa-minus"></i>
                                </button>
                                <input type="number" 
                                       class="form-control form-control-sm quantity-input" 
                                       value="${item.quantity}" 
                                       min="1" 
                                       onchange="cart.updateQuantity(${item.id}, this.value)">
                                <button class="btn btn-outline-secondary btn-sm" 
                                        type="button" 
                                        onclick="cart.updateQuantity(${item.id}, ${item.quantity + 1})">
                                    <i class="fas fa-plus"></i>
                                </button>
                            </div>
                        </div>
                        <div class="col-md-1">
                            <span class="fw-bold">$${(parseFloat(item.price) * item.quantity).toFixed(2)}</span>
                        </div>
                        <div class="col-md-1">
                            <button class="btn btn-outline-danger btn-sm" 
                                    onclick="cart.removeItem(${item.id})"
                                    title="Remove item">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                `;
                return div;
            }

            updateCartSummary() {
                const subtotal = this.cartData.subtotal || 0;
                const discount = this.cartData.discount || 0;
                const shipping = 0; // Free shipping for now
                const total = this.cartData.total || (subtotal - discount + shipping);

                document.getElementById('subtotal').textContent = `$${parseFloat(subtotal).toFixed(2)}`;
                document.getElementById('discount').textContent = `-$${parseFloat(discount).toFixed(2)}`;
                document.getElementById('shipping').textContent = `$${parseFloat(shipping).toFixed(2)}`;
                document.getElementById('total').textContent = `$${parseFloat(total).toFixed(2)}`;
            }

            updateCartCount() {
                const count = this.cartData.items_count || (this.cartData.items ? this.cartData.items.length : 0);
                const cartBadge = document.getElementById('cart-badge');
                if (cartBadge) {
                    cartBadge.textContent = count;
                }
            }

            async updateQuantity(itemId, newQuantity) {
                if (newQuantity < 1) {
                    this.removeItem(itemId);
                    return;
                }

                try {
                    const response = await fetch(`${this.apiBaseUrl}/cart/items/${itemId}`, {
                        method: 'PUT',
                        headers: {
                            'Accept': 'application/json',
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': this.csrfToken
                        },
                        body: JSON.stringify({
                            quantity: parseInt(newQuantity)
                        })
                    });

                    if (response.ok) {
                        const result = await response.json();
                        this.cartData = result.data;
                        this.renderCart();
                        this.showToast('Cart updated successfully', 'success');
                    } else {
                        throw new Error('Failed to update quantity');
                    }
                } catch (error) {
                    console.error('Error updating quantity:', error);
                    this.showToast('Error updating quantity', 'error');
                }
            }

            async removeItem(itemId) {
                if (!confirm('Are you sure you want to remove this item from your cart?')) {
                    return;
                }

                try {
                    const response = await fetch(`${this.apiBaseUrl}/cart/items/${itemId}`, {
                        method: 'DELETE',
                        headers: {
                            'Accept': 'application/json',
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': this.csrfToken
                        }
                    });

                    if (response.ok) {
                        const result = await response.json();
                        this.cartData = result.data;
                        this.renderCart();
                        this.showToast('Item removed from cart', 'success');
                    } else {
                        throw new Error('Failed to remove item');
                    }
                } catch (error) {
                    console.error('Error removing item:', error);
                    this.showToast('Error removing item', 'error');
                }
            }

            async updateCart() {
                // This would typically update all quantities at once
                this.showToast('Cart updated successfully', 'success');
            }

            async clearCart() {
                if (!confirm('Are you sure you want to clear your entire cart?')) {
                    return;
                }

                try {
                    const response = await fetch(`${this.apiBaseUrl}/cart/clear`, {
                        method: 'DELETE',
                        headers: {
                            'Accept': 'application/json',
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': this.csrfToken
                        }
                    });

                    if (response.ok) {
                        const result = await response.json();
                        this.cartData = result.data;
                        this.renderCart();
                        this.showToast('Cart cleared successfully', 'success');
                    } else {
                        throw new Error('Failed to clear cart');
                    }
                } catch (error) {
                    console.error('Error clearing cart:', error);
                    this.showToast('Error clearing cart', 'error');
                }
            }

            async applyCoupon() {
                const couponCode = document.getElementById('coupon-code').value.trim();
                if (!couponCode) {
                    this.showToast('Please enter a coupon code', 'warning');
                    return;
                }

                try {
                    const response = await fetch(`${this.apiBaseUrl}/cart/coupon/apply`, {
                        method: 'POST',
                        headers: {
                            'Accept': 'application/json',
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': this.csrfToken
                        },
                        body: JSON.stringify({
                            code: couponCode
                        })
                    });

                    const result = await response.json();

                    if (response.ok) {
                        this.cartData = result.data;
                        this.renderCart();
                        this.showToast('Coupon applied successfully!', 'success');
                        document.getElementById('coupon-code').value = '';
                    } else {
                        this.showToast(result.message || 'Invalid coupon code', 'error');
                    }
                } catch (error) {
                    console.error('Error applying coupon:', error);
                    this.showToast('Error applying coupon', 'error');
                }
            }

            async removeCoupon() {
                try {
                    const response = await fetch(`${this.apiBaseUrl}/cart/coupon/remove`, {
                        method: 'DELETE',
                        headers: {
                            'Accept': 'application/json',
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': this.csrfToken
                        }
                    });

                    if (response.ok) {
                        const result = await response.json();
                        this.cartData = result.data;
                        this.renderCart();
                        this.showToast('Coupon removed successfully', 'success');
                    } else {
                        throw new Error('Failed to remove coupon');
                    }
                } catch (error) {
                    console.error('Error removing coupon:', error);
                    this.showToast('Error removing coupon', 'error');
                }
            }

            proceedToCheckout() {
                if (!this.cartData || !this.cartData.items || this.cartData.items.length === 0) {
                    this.showToast('Your cart is empty', 'warning');
                    return;
                }

                // Redirect to checkout page
                window.location.href = '/checkout';
            }

            async loadRelatedProducts() {
                try {
                    const response = await fetch(`${this.apiBaseUrl}/products?limit=6`);
                    if (response.ok) {
                        const result = await response.json();
                        this.renderRelatedProducts(result.data || []);
                    }
                } catch (error) {
                    console.error('Error loading related products:', error);
                }
            }

            renderRelatedProducts(products) {
                const relatedProductsList = document.getElementById('related-products-list');
                relatedProductsList.innerHTML = '';

                products.forEach(product => {
                    const productElement = document.createElement('div');
                    productElement.className = 'swiper-slide';
                    productElement.innerHTML = `
                        <div class="related-product">
                            <img src="${product.image || '/assets/images/product-image/1.jpg'}" 
                                 alt="${product.name}"
                                 style="width: 100%; height: 150px; object-fit: cover; border-radius: 8px;"
                                 onerror="this.src='/assets/images/product-image/1.jpg'">
                            <h6 class="mt-2">${product.name}</h6>
                            <p class="text-muted small">${product.short_description || product.description || ''}</p>
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="fw-bold text-primary">$${parseFloat(product.price).toFixed(2)}</span>
                                <button class="btn btn-sm btn-outline-primary" 
                                        onclick="cart.addToCart(${product.id})">
                                    <i class="fas fa-plus"></i>
                                </button>
                            </div>
                        </div>
                    `;
                    relatedProductsList.appendChild(productElement);
                });

                // Reinitialize Swiper
                this.initializeSwiper();
            }

            async addToCart(productId) {
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
                        this.showToast('Product added to cart!', 'success');
                        this.loadCart(); // Reload cart to update counts
                    } else {
                        const error = await response.json();
                        this.showToast(error.message || 'Error adding product to cart', 'error');
                    }
                } catch (error) {
                    console.error('Error adding to cart:', error);
                    this.showToast('Error adding product to cart', 'error');
                }
            }

            initializeSwiper() {
                if (typeof Swiper !== 'undefined') {
                    new Swiper('.related-products-swiper', {
                        slidesPerView: 1,
                        spaceBetween: 20,
                        pagination: {
                            el: '.swiper-pagination',
                            clickable: true,
                        },
                        breakpoints: {
                            640: {
                                slidesPerView: 2,
                            },
                            768: {
                                slidesPerView: 3,
                            },
                            1024: {
                                slidesPerView: 4,
                            },
                        },
                    });
                }
            }

            showLoading(show) {
                const cartContent = document.getElementById('cart-content');
                if (show) {
                    cartContent.classList.add('loading');
                } else {
                    cartContent.classList.remove('loading');
                }
            }

            showToast(message, type = 'info') {
                const toastContainer = document.querySelector('.toast-container');
                const toastId = 'toast-' + Date.now();
                
                const toastHtml = `
                    <div id="${toastId}" class="toast align-items-center text-white bg-${type === 'error' ? 'danger' : type} border-0" role="alert">
                        <div class="d-flex">
                            <div class="toast-body">
                                <i class="fas fa-${type === 'success' ? 'check-circle' : type === 'error' ? 'exclamation-circle' : 'info-circle'} me-2"></i>
                                ${message}
                            </div>
                            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
                        </div>
                    </div>
                `;
                
                toastContainer.insertAdjacentHTML('beforeend', toastHtml);
                
                const toastElement = document.getElementById(toastId);
                const toast = new bootstrap.Toast(toastElement);
                toast.show();
                
                // Remove toast element after it's hidden
                toastElement.addEventListener('hidden.bs.toast', () => {
                    toastElement.remove();
                });
            }
        }

        // Initialize cart when page loads
        let cart;
        document.addEventListener('DOMContentLoaded', function() {
            cart = new ShoppingCart();
        });
    </script>
@endpush

@push('styles')
<style>
    .cart-item {
        transition: background-color 0.3s ease;
    }
    
    .cart-item:hover {
        background-color: #f8f9fa;
    }
    
    .product-image {
        border: 1px solid #dee2e6;
    }
    
    .quantity-input {
        text-align: center;
        width: 60px;
    }
    
    .related-product {
        text-align: center;
        padding: 1rem;
        border: 1px solid #dee2e6;
        border-radius: 8px;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    
    .related-product:hover {
        transform: translateY(-5px);
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    }
    
    .empty-cart {
        text-align: center;
        padding: 4rem 2rem;
    }
    
    .empty-cart i {
        font-size: 4rem;
        color: #6c757d;
        margin-bottom: 1rem;
    }
    
    .cart-summary {
        background-color: #f8f9fa;
        padding: 1.5rem;
        border-radius: 8px;
        border: 1px solid #dee2e6;
    }
    
    .coupon-section {
        background-color: #f8f9fa;
        padding: 1.5rem;
        border-radius: 8px;
        border: 1px solid #dee2e6;
    }
    
    #coupon-message {
        font-size: 0.875rem;
    }
    
    .coupon-message.success {
        color: #198754;
    }
    
    .coupon-message.error {
        color: #dc3545;
    }
</style>
@endpush

