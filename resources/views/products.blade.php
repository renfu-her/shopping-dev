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
            <!-- Search and Sort Controls - Full Width -->
            <div class="row mb-4">
                <div class="col-lg-3">
                    <!-- Empty space to align with sidebar -->
                </div>
                <div class="col-lg-9">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="input-group">
                                <input type="text" id="search-input" class="form-control"
                                    placeholder="Search products...">
                                <button class="btn btn-outline-secondary" type="button" id="search-btn">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <select id="sort-select" class="form-select">
                                <option value="created_at-desc">Newest First</option>
                                <option value="created_at-asc">Oldest First</option>
                                <option value="name-asc">Name A-Z</option>
                                <option value="name-desc">Name Z-A</option>
                                <option value="price-asc">Price Low to High</option>
                                <option value="price-desc">Price High to Low</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <button class="btn btn-outline-secondary w-100" id="clear-filters" style="display: none;">
                                <i class="fas fa-times me-1"></i>Clear
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <!-- Sidebar - Category Filter -->
                <div class="col-lg-3 mb-4">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">
                                <i class="fas fa-filter me-2"></i>Categories
                            </h5>
                        </div>
                        <div class="card-body p-0">
                            <!-- Loading State for Categories -->
                            <div id="categories-loading" class="text-center py-3">
                                <div class="spinner-border spinner-border-sm text-primary" role="status">
                                    <span class="visually-hidden">Loading...</span>
                                </div>
                            </div>

                            <!-- Categories List -->
                            <div id="categories-container" style="display: none;">
                                <ul class="list-group list-group-flush">
                                    <li class="list-group-item">
                                        <a href="#" class="text-decoration-none category-link" data-category-id="">
                                            <i class="fas fa-th-large me-2"></i>All Products
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Main Content -->
                <div class="col-lg-9">

                    <!-- Results Header -->
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <div>
                            <h4 id="results-title">All Products</h4>
                            <p class="text-muted mb-0" id="results-count">Loading...</p>
                        </div>
                    </div>

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
                        <h4>No Products Found</h4>
                        <p class="text-muted">Try adjusting your filters or search terms.</p>
                        <button class="btn btn-primary" id="clear-filters-btn">
                            <i class="fas fa-refresh me-2"></i>Clear All Filters
                        </button>
                    </div>

                    <!-- Pagination -->
                    <div id="pagination-container" class="d-flex justify-content-center mt-4" style="display: none;">
                        <!-- Pagination will be loaded here -->
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection

@push('styles')
    <style>
        .category-link {
            color: #6c757d;
            transition: all 0.3s ease;
            padding: 8px 12px;
            border-radius: 6px;
            display: block;
        }

        .category-link:hover {
            color: #0d6efd;
            background-color: #f8f9fa;
            text-decoration: none;
        }

        .category-link.active {
            color: #0d6efd;
            background-color: #e7f1ff;
            font-weight: 600;
        }

        .category-link.fw-bold {
            color: #495057;
            font-size: 0.95rem;
        }

        .category-link.fw-bold:hover {
            color: #0d6efd;
        }

        .category-link.fw-bold.active {
            color: #0d6efd;
            background-color: #e7f1ff;
        }

        .product-card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            border: 1px solid #e9ecef;
        }

        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
        }

        .card-header {
            background-color: #f8f9fa;
            border-bottom: 1px solid #e9ecef;
        }

        .list-group-item {
            border: none;
            padding: 0;
        }

        .list-group-item:first-child {
            border-top-left-radius: 0;
            border-top-right-radius: 0;
        }

        .list-group-item:last-child {
            border-bottom-left-radius: 0;
            border-bottom-right-radius: 0;
        }

        #results-title {
            color: #212529;
            margin-bottom: 0.5rem;
        }

        #results-count {
            font-size: 0.9rem;
        }

        .pagination {
            margin-bottom: 0;
        }

        .page-link {
            color: #0d6efd;
            border-color: #dee2e6;
        }

        .page-link:hover {
            color: #0a58ca;
            background-color: #e9ecef;
            border-color: #dee2e6;
        }

        .page-item.active .page-link {
            background-color: #0d6efd;
            border-color: #0d6efd;
        }

        /* Search and Sort Controls */
        .input-group .form-control {
            border-right: 0;
        }

        .input-group .btn {
            border-left: 0;
        }

        .form-select {
            border-color: #ced4da;
        }

        .form-select:focus {
            border-color: #86b7fe;
            box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
        }

        /* Responsive adjustments */
        @media (max-width: 991.98px) {
            .col-lg-3 {
                margin-bottom: 2rem;
            }
            
            /* On mobile, make search controls full width */
            .col-lg-3:first-child {
                display: none;
            }
            
            .col-lg-9:first-child {
                flex: 0 0 100%;
                max-width: 100%;
            }
        }

        @media (max-width: 767.98px) {
            .row.mb-4 .col-md-6,
            .row.mb-4 .col-md-4,
            .row.mb-4 .col-md-2 {
                margin-bottom: 1rem;
            }
        }
    </style>
@endpush

@push('scripts')
    <script>
        // Global state
        let currentFilters = {
            category_id: '',
            search: '',
            sort_by: 'created_at',
            sort_order: 'desc',
            page: 1
        };

        document.addEventListener('DOMContentLoaded', function() {
            loadCategories();
            loadProducts();
            setupEventListeners();
        });

        function setupEventListeners() {
            // Category filter
            document.addEventListener('click', function(e) {
                if (e.target.classList.contains('category-link')) {
                    e.preventDefault();
                    const categoryId = e.target.getAttribute('data-category-id');
                    setCategoryFilter(categoryId);
                }
            });

            // Search
            const searchInput = document.getElementById('search-input');
            const searchBtn = document.getElementById('search-btn');

            searchBtn.addEventListener('click', function() {
                const searchTerm = searchInput.value.trim();
                setSearchFilter(searchTerm);
            });

            searchInput.addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    const searchTerm = searchInput.value.trim();
                    setSearchFilter(searchTerm);
                }
            });

            // Sort
            const sortSelect = document.getElementById('sort-select');
            sortSelect.addEventListener('change', function() {
                const [sortBy, sortOrder] = this.value.split('-');
                setSortFilter(sortBy, sortOrder);
            });

            // Clear filters
            const clearFiltersBtn = document.getElementById('clear-filters');
            const clearFiltersBtn2 = document.getElementById('clear-filters-btn');

            if (clearFiltersBtn) {
                clearFiltersBtn.addEventListener('click', clearAllFilters);
            }
            if (clearFiltersBtn2) {
                clearFiltersBtn2.addEventListener('click', clearAllFilters);
            }
        }

        async function loadCategories() {
            const categoriesLoading = document.getElementById('categories-loading');
            const categoriesContainer = document.getElementById('categories-container');

            try {
                categoriesLoading.style.display = 'block';
                categoriesContainer.style.display = 'none';

                const response = await fetch('/api/v1/products/categories');

                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }

                const data = await response.json();
                const categories = data.data || data;

                displayCategories(categories);
                categoriesContainer.style.display = 'block';

            } catch (err) {
                console.error('Error loading categories:', err);
                categoriesContainer.innerHTML = '<div class="p-3 text-muted">Failed to load categories</div>';
                categoriesContainer.style.display = 'block';
            } finally {
                categoriesLoading.style.display = 'none';
            }
        }

        function displayCategories(categories) {
            const container = document.getElementById('categories-container');
            const listGroup = container.querySelector('.list-group');

            categories.forEach(category => {
                // Add parent category
                const parentLi = document.createElement('li');
                parentLi.className = 'list-group-item';
                parentLi.innerHTML = `
            <a href="#" class="text-decoration-none category-link fw-bold" data-category-id="${category.id}">
                <i class="fas fa-folder me-2"></i>${category.name}
            </a>
        `;
                listGroup.appendChild(parentLi);

                // Add child categories if they exist
                if (category.children && category.children.length > 0) {
                    category.children.forEach(child => {
                        const childLi = document.createElement('li');
                        childLi.className = 'list-group-item';
                        childLi.style.paddingLeft = '2rem';
                        childLi.innerHTML = `
                    <a href="#" class="text-decoration-none category-link" data-category-id="${child.id}">
                        <i class="fas fa-folder-open me-2"></i>${child.name}
                    </a>
                `;
                        listGroup.appendChild(childLi);
                    });
                }
            });
        }

        async function loadProducts() {
            const loading = document.getElementById('loading');
            const error = document.getElementById('error');
            const productsContainer = document.getElementById('products-container');
            const emptyState = document.getElementById('empty-state');
            const paginationContainer = document.getElementById('pagination-container');

            try {
                loading.style.display = 'block';
                error.style.display = 'none';
                productsContainer.style.display = 'none';
                emptyState.style.display = 'none';
                paginationContainer.style.display = 'none';

                // Build query parameters
                const params = new URLSearchParams();
                if (currentFilters.category_id) params.append('category_id', currentFilters.category_id);
                if (currentFilters.search) params.append('search', currentFilters.search);
                if (currentFilters.sort_by) params.append('sort_by', currentFilters.sort_by);
                if (currentFilters.sort_order) params.append('sort_order', currentFilters.sort_order);
                if (currentFilters.page) params.append('page', currentFilters.page);

                const response = await fetch(`/api/v1/products?${params.toString()}`);

                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }

                const data = await response.json();
                const products = data.data || data; // Handle both formats

                // Update results header
                updateResultsHeader(products);

                if (products.length === 0) {
                    emptyState.style.display = 'block';
                } else {
                    displayProducts(products);
                    productsContainer.style.display = 'flex';
                }

                // Handle pagination
                if (data.meta && data.meta.last_page > 1) {
                    displayPagination(data.meta);
                    paginationContainer.style.display = 'flex';
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

        function updateResultsHeader(products) {
            const resultsTitle = document.getElementById('results-title');
            const resultsCount = document.getElementById('results-count');
            const clearFiltersBtn = document.getElementById('clear-filters');

            // Update title based on current filter
            if (currentFilters.category_id) {
                const categoryLink = document.querySelector(`[data-category-id="${currentFilters.category_id}"]`);
                const categoryName = categoryLink ? categoryLink.textContent.trim() : 'Selected Category';
                resultsTitle.textContent = categoryName;
            } else if (currentFilters.search) {
                resultsTitle.textContent = `Search Results for "${currentFilters.search}"`;
            } else {
                resultsTitle.textContent = 'All Products';
            }

            // Update count
            resultsCount.textContent = `${products.length} products found`;

            // Show/hide clear filters button
            const hasFilters = currentFilters.category_id || currentFilters.search;
            clearFiltersBtn.style.display = hasFilters ? 'block' : 'none';
        }

        function displayPagination(meta) {
            const container = document.getElementById('pagination-container');
            let paginationHTML = '<nav><ul class="pagination">';

            // Previous button
            if (meta.current_page > 1) {
                paginationHTML += `
            <li class="page-item">
                <a class="page-link" href="#" data-page="${meta.current_page - 1}">Previous</a>
            </li>
        `;
            }

            // Page numbers
            const startPage = Math.max(1, meta.current_page - 2);
            const endPage = Math.min(meta.last_page, meta.current_page + 2);

            for (let i = startPage; i <= endPage; i++) {
                const activeClass = i === meta.current_page ? 'active' : '';
                paginationHTML += `
            <li class="page-item ${activeClass}">
                <a class="page-link" href="#" data-page="${i}">${i}</a>
            </li>
        `;
            }

            // Next button
            if (meta.current_page < meta.last_page) {
                paginationHTML += `
            <li class="page-item">
                <a class="page-link" href="#" data-page="${meta.current_page + 1}">Next</a>
            </li>
        `;
            }

            paginationHTML += '</ul></nav>';
            container.innerHTML = paginationHTML;

            // Add click event listeners
            container.addEventListener('click', function(e) {
                if (e.target.classList.contains('page-link')) {
                    e.preventDefault();
                    const page = parseInt(e.target.getAttribute('data-page'));
                    setPageFilter(page);
                }
            });
        }

        function setCategoryFilter(categoryId) {
            currentFilters.category_id = categoryId;
            currentFilters.page = 1; // Reset to first page
            updateActiveCategory(categoryId);
            loadProducts();
        }

        function setSearchFilter(searchTerm) {
            currentFilters.search = searchTerm;
            currentFilters.page = 1; // Reset to first page
            loadProducts();
        }

        function setSortFilter(sortBy, sortOrder) {
            currentFilters.sort_by = sortBy;
            currentFilters.sort_order = sortOrder;
            currentFilters.page = 1; // Reset to first page
            loadProducts();
        }

        function setPageFilter(page) {
            currentFilters.page = page;
            loadProducts();
        }

        function updateActiveCategory(categoryId) {
            // Remove active class from all category links
            document.querySelectorAll('.category-link').forEach(link => {
                link.classList.remove('active', 'fw-bold');
            });

            // Add active class to selected category
            const activeLink = document.querySelector(`[data-category-id="${categoryId}"]`);
            if (activeLink) {
                activeLink.classList.add('active', 'fw-bold');
            }
        }

        function clearAllFilters() {
            currentFilters = {
                category_id: '',
                search: '',
                sort_by: 'created_at',
                sort_order: 'desc',
                page: 1
            };

            // Reset UI elements
            document.getElementById('search-input').value = '';
            document.getElementById('sort-select').value = 'created_at-desc';
            updateActiveCategory('');

            loadProducts();
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
                <img src="${product.image || '/assets/images/product-image/1.jpg'}" 
                     class="card-img-top" 
                     alt="${product.name}"
                     style="height: 250px; object-fit: cover;"
                     onerror="this.src='/assets/images/product-image/1.jpg'">
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
                    window.location.href = '{{ route('auth') }}';
                }, 1500);
                return;
            }

            try {
                const response = await fetch('/api/v1/cart/add', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                            'content')
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
            toast.className =
                `alert alert-${type === 'success' ? 'success' : type === 'error' ? 'danger' : 'info'} alert-dismissible fade show position-fixed`;
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
