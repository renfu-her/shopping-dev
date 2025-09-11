@extends('layout.app')

@section('title', 'Checkout - ' . config('app.name'))


@section('content')

    <!-- Checkout Section -->
    <section class="py-5">
        <div class="container">
            <div id="checkout-loading" class="text-center py-5">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <p class="mt-2">Loading checkout...</p>
            </div>

            <div id="checkout-content" style="display: none;">
                <!-- Checkout Steps -->
                <div class="checkout-step">
                    <div class="step-number active" id="step-1-number">1</div>
                    <div>
                        <h5 class="mb-0">Shipping Information</h5>
                        <small class="text-muted">Enter your delivery details</small>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-8">
                        <!-- Shipping Information -->
                        <div class="form-section" id="shipping-section">
                            <h4 class="mb-4">
                                <i class="fas fa-shipping-fast me-2"></i>Shipping Information
                            </h4>
                            <form id="shippingForm">
                                <div class="row g-3">
                                    <div class="col-12">
                                        <label for="fullName" class="form-label">Full Name *</label>
                                        <input type="text" class="form-control" id="fullName" required>
                                    </div>
                                    <div class="col-12">
                                        <label for="email" class="form-label">Email Address *</label>
                                        <input type="email" class="form-control" id="email" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="phone" class="form-label">Phone Number *</label>
                                        <input type="tel" class="form-control" id="phone" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="company" class="form-label">Company (Optional)</label>
                                        <input type="text" class="form-control" id="company">
                                    </div>
                                    <div class="col-12">
                                        <label for="address" class="form-label">Street Address *</label>
                                        <input type="text" class="form-control" id="address" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="city" class="form-label">City *</label>
                                        <input type="text" class="form-control" id="city" required>
                                    </div>
                                    <div class="col-md-3">
                                        <label for="state" class="form-label">State *</label>
                                        <select class="form-select" id="state" required>
                                            <option value="">Select State</option>
                                            <option value="NY">New York</option>
                                            <option value="CA">California</option>
                                            <option value="TX">Texas</option>
                                            <option value="FL">Florida</option>
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <label for="zipCode" class="form-label">ZIP Code *</label>
                                        <input type="text" class="form-control" id="zipCode" required>
                                    </div>
                                    <div class="col-12">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="saveAddress">
                                            <label class="form-check-label" for="saveAddress">
                                                Save this address for future orders
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>

                        <!-- Payment Information -->
                        <div class="form-section" id="payment-section" style="display: none;">
                            <h4 class="mb-4">
                                <i class="fas fa-credit-card me-2"></i>Payment Information
                            </h4>
                            
                            <!-- Payment Methods -->
                            <div class="mb-4">
                                <h6>Select Payment Method</h6>
                                <div class="payment-method" data-method="card" onclick="selectPaymentMethod('card')">
                                    <div class="d-flex align-items-center">
                                        <input type="radio" name="paymentMethod" value="card" class="me-3">
                                        <i class="fas fa-credit-card fa-2x me-3"></i>
                                        <div>
                                            <h6 class="mb-0">Credit/Debit Card</h6>
                                            <small class="text-muted">Visa, MasterCard, American Express</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="payment-method" data-method="paypal" onclick="selectPaymentMethod('paypal')">
                                    <div class="d-flex align-items-center">
                                        <input type="radio" name="paymentMethod" value="paypal" class="me-3">
                                        <i class="fab fa-paypal fa-2x me-3 text-primary"></i>
                                        <div>
                                            <h6 class="mb-0">PayPal</h6>
                                            <small class="text-muted">Pay with your PayPal account</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="payment-method" data-method="apple" onclick="selectPaymentMethod('apple')">
                                    <div class="d-flex align-items-center">
                                        <input type="radio" name="paymentMethod" value="apple" class="me-3">
                                        <i class="fab fa-apple-pay fa-2x me-3"></i>
                                        <div>
                                            <h6 class="mb-0">Apple Pay</h6>
                                            <small class="text-muted">Pay with Apple Pay</small>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Card Details -->
                            <div id="card-details" style="display: none;">
                                <form id="paymentForm">
                                    <div class="row g-3">
                                        <div class="col-12">
                                            <label for="cardNumber" class="form-label">Card Number *</label>
                                            <input type="text" class="form-control" id="cardNumber" placeholder="1234 5678 9012 3456" required>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="expiryDate" class="form-label">Expiry Date *</label>
                                            <input type="text" class="form-control" id="expiryDate" placeholder="MM/YY" required>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="cvv" class="form-label">CVV *</label>
                                            <input type="text" class="form-control" id="cvv" placeholder="123" required>
                                        </div>
                                        <div class="col-12">
                                            <label for="cardName" class="form-label">Name on Card *</label>
                                            <input type="text" class="form-control" id="cardName" required>
                                        </div>
                                        <div class="col-12">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" id="saveCard">
                                                <label class="form-check-label" for="saveCard">
                                                    Save this card for future purchases
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <!-- Order Review -->
                        <div class="form-section" id="review-section" style="display: none;">
                            <h4 class="mb-4">
                                <i class="fas fa-check-circle me-2"></i>Review Your Order
                            </h4>
                            <div id="order-review-content">
                                <!-- Order review content will be populated here -->
                            </div>
                        </div>
                    </div>

                    <!-- Order Summary -->
                    <div class="col-lg-4">
                        <div class="order-summary">
                            <h5 class="mb-4">Order Summary</h5>
                            <div id="order-items">
                                <!-- Order items will be loaded here -->
                            </div>
                            <hr>
                            <div class="d-flex justify-content-between mb-2">
                                <span>Subtotal:</span>
                                <span id="subtotal">$0.00</span>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span>Shipping:</span>
                                <span id="shipping-cost">$0.00</span>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span>Tax:</span>
                                <span id="tax">$0.00</span>
                            </div>
                            <hr>
                            <div class="d-flex justify-content-between mb-4">
                                <strong>Total:</strong>
                                <strong id="total">$0.00</strong>
                            </div>
                            <button class="btn btn-checkout w-100" id="proceed-btn" onclick="proceedToNextStep()">
                                <i class="fas fa-arrow-right me-2"></i>Proceed to Payment
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection

@push('scripts')

    <script>
        class Checkout {
            constructor() {
                this.apiBaseUrl = '/api/v1';
                this.csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                this.currentStep = 1;
                this.cartData = null;
                this.shippingData = null;
                this.paymentData = null;
                this.init();
            }

            init() {
                this.loadCartData();
                this.updateCartCount();
            }

            async loadCartData() {
                try {
                    const response = await fetch(`${this.apiBaseUrl}/cart`);
                    if (response.ok) {
                        const result = await response.json();
                        this.cartData = result.data; // Extract data from API response
                        console.log('Checkout cart data:', this.cartData); // Debug log
                        this.renderOrderSummary();
                        this.loadMemberData(); // Load member data to pre-fill form
                        document.getElementById('checkout-loading').style.display = 'none';
                        document.getElementById('checkout-content').style.display = 'block';
                    } else {
                        throw new Error('Failed to load cart data');
                    }
                } catch (error) {
                    console.error('Error loading cart data:', error);
                    this.showError('Unable to load cart data. Please try again.');
                }
            }

            async loadMemberData() {
                try {
                    const response = await fetch(`${this.apiBaseUrl}/member/auth/me`, {
                        headers: {
                            'Accept': 'application/json',
                            'Authorization': `Bearer ${localStorage.getItem('member_token')}`
                        }
                    });
                    
                    if (response.ok) {
                        const result = await response.json();
                        const memberData = result.data;
                        
                        // Pre-fill form with member data
                        if (memberData) {
                            document.getElementById('fullName').value = memberData.name || '';
                            document.getElementById('email').value = memberData.email || '';
                        }
                    }
                } catch (error) {
                    console.log('No member data available or not logged in');
                }
            }

            renderOrderSummary() {
                if (!this.cartData || !this.cartData.items) {
                    this.showError('Your cart is empty.');
                    return;
                }

                const orderItemsContainer = document.getElementById('order-items');
                orderItemsContainer.innerHTML = '';

                let subtotal = 0;
                this.cartData.items.forEach(item => {
                    const itemTotal = parseFloat(item.price) * item.quantity;
                    subtotal += itemTotal;

                    const itemElement = document.createElement('div');
                    itemElement.className = 'd-flex justify-content-between align-items-center mb-3';
                    itemElement.innerHTML = `
                        <div class="d-flex align-items-center">
                            <img src="${item.product.image || '/assets/images/product-image/1.jpg'}" 
                                 alt="${item.product.name}" 
                                 style="width: 50px; height: 50px; object-fit: cover; border-radius: 5px; margin-right: 10px;"
                                 onerror="this.src='/assets/images/product-image/1.jpg'">
                            <div>
                                <h6 class="mb-0">${item.product.name}</h6>
                                <small class="text-muted">Qty: ${item.quantity}</small>
                            </div>
                        </div>
                        <span>$${itemTotal.toFixed(2)}</span>
                    `;
                    orderItemsContainer.appendChild(itemElement);
                });

                const shipping = subtotal > 50 ? 0 : 9.99;
                const tax = subtotal * 0.08; // 8% tax
                const total = subtotal + shipping + tax;

                document.getElementById('subtotal').textContent = `$${subtotal.toFixed(2)}`;
                document.getElementById('shipping-cost').textContent = shipping === 0 ? 'FREE' : `$${shipping.toFixed(2)}`;
                document.getElementById('tax').textContent = `$${tax.toFixed(2)}`;
                document.getElementById('total').textContent = `$${total.toFixed(2)}`;
            }

            proceedToNextStep() {
                if (this.currentStep === 1) {
                    if (this.validateShippingForm()) {
                        this.collectShippingData();
                        this.showPaymentSection();
                        this.currentStep = 2;
                        this.updateStepDisplay();
                    }
                } else if (this.currentStep === 2) {
                    if (this.validatePaymentForm()) {
                        this.collectPaymentData();
                        this.showReviewSection();
                        this.currentStep = 3;
                        this.updateStepDisplay();
                    }
                } else if (this.currentStep === 3) {
                    this.placeOrder();
                }
            }

            validateShippingForm() {
                const requiredFields = ['fullName', 'email', 'phone', 'address', 'city', 'state', 'zipCode'];
                let isValid = true;

                requiredFields.forEach(field => {
                    const input = document.getElementById(field);
                    if (!input.value.trim()) {
                        input.classList.add('is-invalid');
                        isValid = false;
                    } else {
                        input.classList.remove('is-invalid');
                    }
                });

                if (!isValid) {
                    this.showToast('Please fill in all required fields', 'error');
                }

                return isValid;
            }

            validatePaymentForm() {
                const selectedMethod = document.querySelector('input[name="paymentMethod"]:checked');
                if (!selectedMethod) {
                    this.showToast('Please select a payment method', 'error');
                    return false;
                }

                if (selectedMethod.value === 'card') {
                    const requiredFields = ['cardNumber', 'expiryDate', 'cvv', 'cardName'];
                    let isValid = true;

                    requiredFields.forEach(field => {
                        const input = document.getElementById(field);
                        if (!input.value.trim()) {
                            input.classList.add('is-invalid');
                            isValid = false;
                        } else {
                            input.classList.remove('is-invalid');
                        }
                    });

                    if (!isValid) {
                        this.showToast('Please fill in all card details', 'error');
                    }

                    return isValid;
                }

                return true;
            }

            collectShippingData() {
                this.shippingData = {
                    fullName: document.getElementById('fullName').value,
                    email: document.getElementById('email').value,
                    phone: document.getElementById('phone').value,
                    company: document.getElementById('company').value,
                    address: document.getElementById('address').value,
                    city: document.getElementById('city').value,
                    state: document.getElementById('state').value,
                    zipCode: document.getElementById('zipCode').value,
                    saveAddress: document.getElementById('saveAddress').checked
                };
            }

            collectPaymentData() {
                const selectedMethod = document.querySelector('input[name="paymentMethod"]:checked').value;
                this.paymentData = {
                    method: selectedMethod
                };

                if (selectedMethod === 'card') {
                    this.paymentData.card = {
                        number: document.getElementById('cardNumber').value,
                        expiry: document.getElementById('expiryDate').value,
                        cvv: document.getElementById('cvv').value,
                        name: document.getElementById('cardName').value,
                        saveCard: document.getElementById('saveCard').checked
                    };
                }
            }

            showPaymentSection() {
                document.getElementById('payment-section').style.display = 'block';
                document.getElementById('proceed-btn').innerHTML = '<i class="fas fa-arrow-right me-2"></i>Review Order';
            }

            showReviewSection() {
                document.getElementById('review-section').style.display = 'block';
                this.renderOrderReview();
                document.getElementById('proceed-btn').innerHTML = '<i class="fas fa-lock me-2"></i>Place Order';
            }

            renderOrderReview() {
                const reviewContent = document.getElementById('order-review-content');
                reviewContent.innerHTML = `
                    <div class="row">
                        <div class="col-md-6">
                            <h6>Shipping Address</h6>
                            <p>
                                ${this.shippingData.fullName}<br>
                                ${this.shippingData.address}<br>
                                ${this.shippingData.city}, ${this.shippingData.state} ${this.shippingData.zipCode}<br>
                                ${this.shippingData.phone}
                            </p>
                        </div>
                        <div class="col-md-6">
                            <h6>Payment Method</h6>
                            <p>
                                ${this.paymentData.method === 'card' ? 'Credit/Debit Card ending in ' + this.paymentData.card.number.slice(-4) : 
                                  this.paymentData.method === 'paypal' ? 'PayPal' : 
                                  this.paymentData.method === 'apple' ? 'Apple Pay' : 'Unknown'}
                            </p>
                        </div>
                    </div>
                `;
            }

            async placeOrder() {
                try {
                    const orderData = {
                        shipping: this.shippingData,
                        payment: this.paymentData,
                        items: this.cartData.items
                    };

                    const response = await fetch(`${this.apiBaseUrl}/orders`, {
                        method: 'POST',
                        headers: {
                            'Accept': 'application/json',
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': this.csrfToken
                        },
                        body: JSON.stringify(orderData)
                    });

                    if (response.ok) {
                        const orderResult = await response.json();
                        this.showToast('Order placed successfully!', 'success');
                        setTimeout(() => {
                            window.location.href = `/order-success/${orderResult.data.id}`;
                        }, 2000);
                    } else {
                        const error = await response.json();
                        this.showToast(error.message || 'Error placing order', 'error');
                    }
                } catch (error) {
                    console.error('Error placing order:', error);
                    this.showToast('Error placing order', 'error');
                }
            }

            updateStepDisplay() {
                // Update step numbers
                for (let i = 1; i <= 3; i++) {
                    const stepNumber = document.getElementById(`step-${i}-number`);
                    if (i < this.currentStep) {
                        stepNumber.className = 'step-number completed';
                    } else if (i === this.currentStep) {
                        stepNumber.className = 'step-number active';
                    } else {
                        stepNumber.className = 'step-number';
                    }
                }
            }

            async updateCartCount() {
                try {
                    const response = await fetch(`${this.apiBaseUrl}/cart/count`);
                    if (response.ok) {
                        const result = await response.json();
                        const count = result.count || 0;
                        const cartBadge = document.getElementById('cart-badge');
                        if (cartBadge) {
                            cartBadge.textContent = count;
                        }
                    }
                } catch (error) {
                    console.error('Error updating cart count:', error);
                }
            }

            showError(message) {
                document.getElementById('checkout-loading').innerHTML = `
                    <div class="text-center py-5">
                        <i class="fas fa-exclamation-triangle fa-3x text-warning mb-3"></i>
                        <h4>Error</h4>
                        <p class="text-muted">${message}</p>
                        <a href="/cart" class="btn btn-primary">Back to Cart</a>
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

        // Global functions
        function selectPaymentMethod(method) {
            // Remove selected class from all payment methods
            document.querySelectorAll('.payment-method').forEach(pm => {
                pm.classList.remove('selected');
            });
            
            // Add selected class to clicked method
            document.querySelector(`[data-method="${method}"]`).classList.add('selected');
            
            // Show/hide card details
            const cardDetails = document.getElementById('card-details');
            if (method === 'card') {
                cardDetails.style.display = 'block';
            } else {
                cardDetails.style.display = 'none';
            }
        }

        function proceedToNextStep() {
            if (window.checkout) {
                window.checkout.proceedToNextStep();
            }
        }

        // Initialize checkout when DOM is loaded
        let checkout;
        document.addEventListener('DOMContentLoaded', function() {
            checkout = new Checkout();
            window.checkout = checkout; // Make it globally accessible
        });
    </script>
@endpush

@push('styles')
<style>
    .checkout-step {
        display: flex;
        align-items: center;
        margin-bottom: 2rem;
        padding: 1rem;
        background-color: #f8f9fa;
        border-radius: 8px;
    }
    
    .step-number {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background-color: #6c757d;
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        margin-right: 1rem;
    }
    
    .step-number.active {
        background-color: #0d6efd;
    }
    
    .step-number.completed {
        background-color: #198754;
    }
    
    .form-section {
        background-color: white;
        padding: 2rem;
        border-radius: 8px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        margin-bottom: 2rem;
    }
    
    .order-summary {
        background-color: #f8f9fa;
        padding: 2rem;
        border-radius: 8px;
        border: 1px solid #dee2e6;
        position: sticky;
        top: 2rem;
    }
    
    .payment-method {
        border: 2px solid #dee2e6;
        border-radius: 8px;
        padding: 1rem;
        margin-bottom: 1rem;
        cursor: pointer;
        transition: all 0.3s ease;
    }
    
    .payment-method:hover {
        border-color: #0d6efd;
        background-color: #f8f9fa;
    }
    
    .payment-method.selected {
        border-color: #0d6efd;
        background-color: #e7f1ff;
    }
    
    .btn-checkout {
        background-color: #198754;
        border-color: #198754;
        color: white;
        padding: 1rem;
        font-size: 1.1rem;
        font-weight: 600;
        border-radius: 8px;
        transition: all 0.3s ease;
    }
    
    .btn-checkout:hover {
        background-color: #157347;
        border-color: #146c43;
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(25, 135, 84, 0.3);
    }
    
    .form-control:focus,
    .form-select:focus {
        border-color: #0d6efd;
        box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
    }
    
    .is-invalid {
        border-color: #dc3545;
    }
    
    .is-invalid:focus {
        border-color: #dc3545;
        box-shadow: 0 0 0 0.25rem rgba(220, 53, 69, 0.25);
    }
</style>
@endpush