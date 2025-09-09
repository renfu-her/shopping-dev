@extends('layout.app')

@section('title', 'Products - ' . config('app.name'))

@section('content')

    <!-- Hero Section -->
    <section class="hero-section">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-8 mx-auto text-center">
                    <h1 class="display-4 fw-bold mb-4">
                        <i class="fas fa-box me-3"></i>Our Products
                    </h1>
                    <p class="lead">Discover our amazing collection of high-quality products</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Products Section -->
    <section class="py-5">
        <div class="container">
            <!-- Loading State -->
            <div id="loading" class="text-center py-5">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <p class="mt-3 text-muted">Loading products...</p>
            </div>

            <!-- Error State -->
            <div id="error" class="alert alert-danger" style="display: none;">
                <i class="fas fa-exclamation-triangle me-2"></i>
                <span id="error-message">Failed to load products. Please try again later.</span>
            </div>

            <!-- Products Grid -->
            <div id="products-container" class="row g-4" style="display: none;">
                <!-- Products will be loaded here -->
            </div>

            <!-- Empty State -->
            <div id="empty-state" class="text-center py-5" style="display: none;">
                <i class="fas fa-shopping-bag fa-3x text-muted mb-3"></i>
                <h4>No Products Available</h4>
                <p class="text-muted">We're working on bringing you amazing products!</p>
            </div>
        </div>
    </section>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    loadProducts();
});

async function loadProducts() {
    const loading = document.getElementById('loading');
    const error = document.getElementById('error');
    const productsContainer = document.getElementById('products-container');
    const emptyState = document.getElementById('empty-state');

    try {
        loading.style.display = 'block';
        error.style.display = 'none';
        productsContainer.style.display = 'none';
        emptyState.style.display = 'none';

        const response = await fetch('/api/v1/products');
        
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }

        const data = await response.json();
        const products = data.data || data; // Handle both formats

        if (products.length === 0) {
            emptyState.style.display = 'block';
        } else {
            displayProducts(products);
            productsContainer.style.display = 'flex';
        }

    } catch (err) {
        console.error('Error loading products:', err);
        document.getElementById('error-message').textContent = 
            'Failed to load products: ' + err.message;
        error.style.display = 'block';
    } finally {
        loading.style.display = 'none';
    }
}

function displayProducts(products) {
    const container = document.getElementById('products-container');
    container.innerHTML = '';

    products.forEach(product => {
        const productCard = createProductCard(product);
        container.appendChild(productCard);
    });
}

function createProductCard(product) {
    const col = document.createElement('div');
    col.className = 'col-lg-4 col-md-6';

    const currentPrice = product.sale_price || product.price;
    const originalPrice = product.sale_price ? product.price : null;
    const discountPercentage = product.sale_price ? 
        Math.round(((product.price - product.sale_price) / product.price) * 100) : null;

    col.innerHTML = `
        <div class="card h-100 product-card">
            <div class="position-relative">
                <img src="${product.image || '/assets/images/placeholder-product.jpg'}" 
                     class="card-img-top" 
                     alt="${product.name}"
                     style="height: 250px; object-fit: cover;">
                ${discountPercentage ? `
                    <span class="badge bg-danger position-absolute top-0 end-0 m-2">
                        -${discountPercentage}%
                    </span>
                ` : ''}
                ${!product.is_in_stock ? `
                    <div class="position-absolute top-0 start-0 w-100 h-100 d-flex align-items-center justify-content-center" 
                         style="background: rgba(0,0,0,0.7);">
                        <span class="badge bg-secondary fs-6">Out of Stock</span>
                    </div>
                ` : ''}
            </div>
            <div class="card-body d-flex flex-column">
                <h5 class="card-title">${product.name}</h5>
                <p class="card-text text-muted flex-grow-1">
                    ${product.short_description || product.description || 'No description available.'}
                </p>
                <div class="mt-auto">
                    <div class="d-flex align-items-center mb-3">
                        <span class="h5 text-primary mb-0">$${currentPrice}</span>
                        ${originalPrice ? `
                            <span class="text-muted text-decoration-line-through ms-2">$${originalPrice}</span>
                        ` : ''}
                    </div>
                    <div class="d-grid gap-2">
                        <a href="/product/${product.id}" class="btn btn-outline-primary">
                            <i class="fas fa-eye me-2"></i>View Details
                        </a>
                        <button class="btn btn-primary" 
                                onclick="addToCart(${product.id})"
                                ${!product.is_in_stock ? 'disabled' : ''}>
                            <i class="fas fa-shopping-cart me-2"></i>
                            ${product.is_in_stock ? 'Add to Cart' : 'Out of Stock'}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    `;

    return col;
}

async function addToCart(productId) {
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
        const response = await fetch('/api/v1/cart/add', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                product_id: productId,
                quantity: 1
            })
        });

        if (!response.ok) {
            throw new Error('Failed to add item to cart');
        }

        const result = await response.json();
        showToast('Product added to cart successfully!', 'success');
        
        // Update cart badge
        updateCartBadge();

    } catch (error) {
        console.error('Error adding to cart:', error);
        showToast('Failed to add item to cart. Please try again.', 'error');
    }
}

function showToast(message, type = 'info') {
    const toast = document.createElement('div');
    toast.className = `alert alert-${type === 'success' ? 'success' : type === 'error' ? 'danger' : 'info'} alert-dismissible fade show position-fixed`;
    toast.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
    toast.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    `;
    
    document.body.appendChild(toast);
    
    setTimeout(() => {
        if (toast.parentNode) {
            toast.parentNode.removeChild(toast);
        }
    }, 5000);
}

async function updateCartBadge() {
    try {
        const response = await fetch('/api/v1/cart/count');
        if (response.ok) {
            const data = await response.json();
            const badge = document.getElementById('cart-badge');
            if (badge) {
                badge.textContent = data.count || 0;
            }
        }
    } catch (error) {
        console.error('Error updating cart badge:', error);
    }
}
</script>
@endpush

