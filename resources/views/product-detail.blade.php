@extends('layout.app')

@section('title', ($product->name ?? 'Product Details') . ' - ' . config('app.name'))


@section('content')

    <!-- Product Detail Section -->
    <section class="py-5">
        <div class="container">
            <div id="product-loading" class="text-center py-5">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <p class="mt-2">Loading product details...</p>
            </div>
            
            <div id="product-detail" class="row g-5" style="display: none;">
                <!-- Product Images -->
                <div class="col-lg-6">
                    <div class="product-images">
                        <img id="main-product-image" src="" alt="Product Image" class="product-image mb-3">
                        <div class="thumbnail-gallery d-flex gap-2">
                            <!-- Thumbnails will be loaded here -->
                        </div>
                    </div>
                </div>

                <!-- Product Information -->
                <div class="col-lg-6">
                    <div class="product-info">
                        <div class="mb-3">
                            <span id="product-category" class="badge bg-secondary"></span>
                            <span id="product-sale-badge" class="badge-sale ms-2" style="display: none;"></span>
                        </div>
                        
                        <h1 id="product-name" class="mb-3"></h1>
                        
                        <div class="mb-3">
                            <span id="product-rating" class="text-warning"></span>
                            <span id="product-reviews" class="text-muted ms-2"></span>
                        </div>
                        
                        <div class="mb-4">
                            <span id="product-price" class="price"></span>
                            <span id="product-old-price" class="old-price ms-2" style="display: none;"></span>
                        </div>
                        
                        <p id="product-description" class="mb-4"></p>
                        
                        <div class="mb-4">
                            <label class="form-label fw-bold">Quantity:</label>
                            <div class="d-flex align-items-center gap-3">
                                <button class="btn btn-outline-secondary" onclick="changeQuantity(-1)">
                                    <i class="fas fa-minus"></i>
                                </button>
                                <input type="number" id="quantity" class="form-control quantity-input" value="1" min="1" max="99">
                                <button class="btn btn-outline-secondary" onclick="changeQuantity(1)">
                                    <i class="fas fa-plus"></i>
                                </button>
                            </div>
                        </div>
                        
                        <div class="d-grid gap-3">
                            <button class="btn btn-buy-now" onclick="buyNow()">
                                <i class="fas fa-bolt me-2"></i>Buy Now
                            </button>
                            <button class="btn btn-add-cart" onclick="addToCart()">
                                <i class="fas fa-shopping-cart me-2"></i>Add to Cart
                            </button>
                        </div>
                        
                        <div class="mt-4">
                            <div class="row g-3">
                                <div class="col-6">
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-shipping-fast text-primary me-2"></i>
                                        <small>Free Shipping</small>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-undo text-success me-2"></i>
                                        <small>30-Day Returns</small>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-shield-alt text-info me-2"></i>
                                        <small>Secure Payment</small>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-headset text-warning me-2"></i>
                                        <small>24/7 Support</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Related Products Section -->
    <section class="py-5 bg-light">
        <div class="container">
            <h2 class="text-center mb-5">Related Products</h2>
            <div id="related-products-loading" class="text-center py-5">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <p class="mt-2">Loading related products...</p>
            </div>
            <div id="related-products" class="row g-4">
                <!-- Related products will be loaded here -->
            </div>
        </div>
    </section>

@endsection

@push('scripts')

    <script>
        class ProductDetail {
            constructor() {
                this.apiBaseUrl = '/api/v1';
                this.csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                this.productId = this.getProductIdFromUrl();
                this.product = null;
                this.init();
            }

            getProductIdFromUrl() {
                const path = window.location.pathname;
                const matches = path.match(/\/product\/(\d+)/);
                return matches ? matches[1] : null;
            }

            init() {
                if (this.productId) {
                    this.loadProduct();
                    this.loadRelatedProducts();
                    this.updateCartCount();
                } else {
                    this.showError('Product not found');
                }
            }

            async loadProduct() {
                try {
                    const response = await fetch(`${this.apiBaseUrl}/products/${this.productId}`);
                    if (response.ok) {
                        const data = await response.json();
                        this.product = data.data;
                        this.renderProduct();
                    } else {
                        throw new Error('Failed to load product');
                    }
                } catch (error) {
                    console.error('Error loading product:', error);
                    this.showError('Unable to load product details');
                } finally {
                    document.getElementById('product-loading').style.display = 'none';
                    document.getElementById('product-detail').style.display = 'flex';
                }
            }

            renderProduct() {
                if (!this.product) return;

                // Update page title
                document.title = `${this.product.name} - ${document.title.split(' - ')[1]}`;

                // Product images
                const mainImage = document.getElementById('main-product-image');
                const thumbnailGallery = document.querySelector('.thumbnail-gallery');
                
                if (this.product.images && this.product.images.length > 0) {
                    mainImage.src = this.product.images[0];
                    mainImage.alt = this.product.name;
                    
                    thumbnailGallery.innerHTML = '';
                    this.product.images.forEach((image, index) => {
                        const thumbnail = document.createElement('img');
                        thumbnail.src = image;
                        thumbnail.alt = this.product.name;
                        thumbnail.className = `product-thumbnail ${index === 0 ? 'active' : ''}`;
                        thumbnail.onclick = () => this.changeMainImage(image, thumbnail);
                        thumbnailGallery.appendChild(thumbnail);
                    });
                } else {
                    mainImage.src = '/example/assets/images/product-image/1.jpg';
                    mainImage.alt = this.product.name;
                }

                // Product information
                document.getElementById('product-name').textContent = this.product.name;
                document.getElementById('product-description').textContent = this.product.description || 'No description available.';
                
                // Category
                if (this.product.category) {
                    document.getElementById('product-category').textContent = this.product.category.name;
                }

                // Price
                const discount = this.product.discount || 0;
                const originalPrice = parseFloat(this.product.price);
                const salePrice = originalPrice * (1 - discount / 100);
                
                document.getElementById('product-price').textContent = `$${salePrice.toFixed(2)}`;
                
                if (discount > 0) {
                    document.getElementById('product-old-price').textContent = `$${originalPrice.toFixed(2)}`;
                    document.getElementById('product-old-price').style.display = 'inline';
                    document.getElementById('product-sale-badge').textContent = `-${discount}%`;
                    document.getElementById('product-sale-badge').style.display = 'inline';
                }

                // Rating (mock data)
                const rating = this.product.rating || 4.5;
                const reviews = this.product.reviews || Math.floor(Math.random() * 100) + 10;
                document.getElementById('product-rating').innerHTML = this.generateStars(rating);
                document.getElementById('product-reviews').textContent = `(${reviews} reviews)`;
            }

            generateStars(rating) {
                const fullStars = Math.floor(rating);
                const hasHalfStar = rating % 1 !== 0;
                let stars = '';
                
                for (let i = 0; i < fullStars; i++) {
                    stars += '<i class="fas fa-star"></i>';
                }
                
                if (hasHalfStar) {
                    stars += '<i class="fas fa-star-half-alt"></i>';
                }
                
                const emptyStars = 5 - Math.ceil(rating);
                for (let i = 0; i < emptyStars; i++) {
                    stars += '<i class="far fa-star"></i>';
                }
                
                return stars;
            }

            changeMainImage(imageSrc, thumbnail) {
                document.getElementById('main-product-image').src = imageSrc;
                
                // Update active thumbnail
                document.querySelectorAll('.product-thumbnail').forEach(t => t.classList.remove('active'));
                thumbnail.classList.add('active');
            }

            async loadRelatedProducts() {
                try {
                    const response = await fetch(`${this.apiBaseUrl}/products/related/${this.productId}`);
                    if (response.ok) {
                        const data = await response.json();
                        this.renderRelatedProducts(data.data || []);
                    } else {
                        throw new Error('Failed to load related products');
                    }
                } catch (error) {
                    console.error('Error loading related products:', error);
                    this.showRelatedProductsError();
                } finally {
                    document.getElementById('related-products-loading').style.display = 'none';
                }
            }

            renderRelatedProducts(products) {
                const container = document.getElementById('related-products');
                container.innerHTML = '';

                if (products.length === 0) {
                    container.innerHTML = `
                        <div class="col-12 text-center py-5">
                            <i class="fas fa-box fa-3x text-muted mb-3"></i>
                            <h4>No Related Products</h4>
                            <p class="text-muted">Check out our other products!</p>
                        </div>
                    `;
                    return;
                }

                products.forEach(product => {
                    const productCard = this.createRelatedProductCard(product);
                    container.appendChild(productCard);
                });
            }

            createRelatedProductCard(product) {
                const col = document.createElement('div');
                col.className = 'col-lg-3 col-md-6';
                
                const discount = product.discount || 0;
                const originalPrice = parseFloat(product.price);
                const salePrice = originalPrice * (1 - discount / 100);
                
                col.innerHTML = `
                    <div class="card related-product-card h-100">
                        <img src="${product.images && product.images[0] ? product.images[0] : '/example/assets/images/product-image/1.jpg'}" 
                             class="card-img-top related-product-image" 
                             alt="${product.name}">
                        <div class="card-body d-flex flex-column">
                            <h6 class="card-title">${product.name}</h6>
                            <p class="card-text text-muted small flex-grow-1">${product.description || ''}</p>
                            <div class="mt-auto">
                                <div class="d-flex align-items-center mb-3">
                                    <span class="price">$${salePrice.toFixed(2)}</span>
                                    ${discount > 0 ? `<span class="old-price ms-2">$${originalPrice.toFixed(2)}</span>` : ''}
                                </div>
                                <div class="d-grid gap-2">
                                    <button class="btn btn-primary btn-sm" onclick="productDetail.addToCart(${product.id})">
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

            showRelatedProductsError() {
                const container = document.getElementById('related-products');
                container.innerHTML = `
                    <div class="col-12 text-center py-5">
                        <i class="fas fa-exclamation-triangle fa-3x text-warning mb-3"></i>
                        <h4>Unable to Load Related Products</h4>
                        <p class="text-muted">Please try again later.</p>
                    </div>
                `;
            }

            async addToCart(productId = null) {
                // Check if user is authenticated
                const isAuthenticated = @json(auth()->guard('member')->check());
                
                if (!isAuthenticated) {
                    this.showToast('Please login to add items to cart', 'warning');
                    setTimeout(() => {
                        window.location.href = '{{ route("auth") }}';
                    }, 1500);
                    return;
                }

                const id = productId || this.productId;
                const quantity = parseInt(document.getElementById('quantity').value);
                
                try {
                    const response = await fetch(`${this.apiBaseUrl}/cart/add`, {
                        method: 'POST',
                        headers: {
                            'Accept': 'application/json',
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': this.csrfToken
                        },
                        body: JSON.stringify({
                            product_id: id,
                            quantity: quantity
                        })
                    });

                    if (response.ok) {
                        this.showToast('Product added to cart!', 'success');
                        this.updateCartCount();
                    } else {
                        const error = await response.json();
                        this.showToast(error.message || 'Error adding product to cart', 'error');
                    }
                } catch (error) {
                    console.error('Error adding to cart:', error);
                    this.showToast('Error adding product to cart', 'error');
                }
            }

            buyNow() {
                // Check if user is authenticated
                const isAuthenticated = @json(auth()->guard('member')->check());
                
                if (!isAuthenticated) {
                    this.showToast('Please login to proceed to checkout', 'warning');
                    setTimeout(() => {
                        window.location.href = '{{ route("auth") }}';
                    }, 1500);
                    return;
                }

                this.addToCart().then(() => {
                    window.location.href = '/checkout';
                });
            }

            async updateCartCount() {
                try {
                    const response = await fetch(`${this.apiBaseUrl}/cart`);
                    if (response.ok) {
                        const cartData = await response.json();
                        const count = cartData.items ? cartData.items.length : 0;
                        document.getElementById('cart-badge').textContent = count;
                    }
                } catch (error) {
                    console.error('Error updating cart count:', error);
                }
            }

            showError(message) {
                document.getElementById('product-loading').innerHTML = `
                    <div class="text-center py-5">
                        <i class="fas fa-exclamation-triangle fa-3x text-warning mb-3"></i>
                        <h4>Error</h4>
                        <p class="text-muted">${message}</p>
                        <a href="/products" class="btn btn-primary">Browse Products</a>
                    </div>
                `;
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
                
                toastElement.addEventListener('hidden.bs.toast', () => {
                    toastElement.remove();
                });
            }
        }

        // Global functions for quantity control
        function changeQuantity(delta) {
            const quantityInput = document.getElementById('quantity');
            const currentValue = parseInt(quantityInput.value);
            const newValue = Math.max(1, Math.min(99, currentValue + delta));
            quantityInput.value = newValue;
        }

        function addToCart() {
            if (window.productDetail) {
                window.productDetail.addToCart();
            }
        }

        function buyNow() {
            if (window.productDetail) {
                window.productDetail.buyNow();
            }
        }

        // Initialize product detail when DOM is loaded
        let productDetail;
        document.addEventListener('DOMContentLoaded', function() {
            productDetail = new ProductDetail();
            window.productDetail = productDetail; // Make it globally accessible
        });
    </script>
@endpush
