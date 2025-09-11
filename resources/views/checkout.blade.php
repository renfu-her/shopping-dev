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
                        <!-- Member Login Section -->
                        <div class="form-section" id="member-login-section">
                            <h4 class="mb-4">
                                <i class="fas fa-user me-2"></i>Member Login (Optional)
                            </h4>
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle me-2"></i>
                                Already a member? Log in to pre-fill your information and enjoy member benefits.
                            </div>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="memberEmail" class="form-label">Email Address</label>
                                    <input type="email" class="form-control" id="memberEmail" placeholder="Enter your email">
                                </div>
                                <div class="col-md-6">
                                    <label for="memberPassword" class="form-label">Password</label>
                                    <input type="password" class="form-control" id="memberPassword" placeholder="Enter your password">
                                </div>
                                <div class="col-12">
                                    <button type="button" class="btn btn-outline-primary" onclick="loginMember()">
                                        <i class="fas fa-sign-in-alt me-2"></i>Login as Member
                                    </button>
                                    <button type="button" class="btn btn-outline-secondary ms-2" onclick="continueAsGuest()">
                                        <i class="fas fa-user-plus me-2"></i>Continue as Guest
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Shipping Information -->
                        <div class="form-section" id="shipping-section" style="display: none;">
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
                                        <label for="city" class="form-label">City/County *</label>
                                        <select class="form-select" id="city" required>
                                            <option value="">Select City/County</option>
                                            <option value="Taipei City">Taipei City</option>
                                            <option value="New Taipei City">New Taipei City</option>
                                            <option value="Taoyuan City">Taoyuan City</option>
                                            <option value="Taichung City">Taichung City</option>
                                            <option value="Tainan City">Tainan City</option>
                                            <option value="Kaohsiung City">Kaohsiung City</option>
                                            <option value="Keelung City">Keelung City</option>
                                            <option value="Hsinchu City">Hsinchu City</option>
                                            <option value="Chiayi City">Chiayi City</option>
                                            <option value="Hsinchu County">Hsinchu County</option>
                                            <option value="Miaoli County">Miaoli County</option>
                                            <option value="Changhua County">Changhua County</option>
                                            <option value="Nantou County">Nantou County</option>
                                            <option value="Yunlin County">Yunlin County</option>
                                            <option value="Chiayi County">Chiayi County</option>
                                            <option value="Pingtung County">Pingtung County</option>
                                            <option value="Yilan County">Yilan County</option>
                                            <option value="Hualien County">Hualien County</option>
                                            <option value="Taitung County">Taitung County</option>
                                            <option value="Penghu County">Penghu County</option>
                                            <option value="Kinmen County">Kinmen County</option>
                                            <option value="Lienchiang County">Lienchiang County</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6">
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
                            
                            <!-- ECPay Payment Method -->
                            <div class="mb-4">
                                <div class="payment-method selected" data-method="ecpay">
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-credit-card fa-2x me-3 text-primary"></i>
                                        <div>
                                            <h6 class="mb-0">Credit Card Payment</h6>
                                            <small class="text-muted">Secure payment powered by ECPay</small>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="alert alert-info mt-3">
                                    <i class="fas fa-info-circle me-2"></i>
                                    <strong>Secure Payment:</strong> Click "Proceed to Payment" to be redirected to ECPay's secure payment gateway 
                                    where you can complete your credit card transaction safely.
                                </div>
                                
                                <div class="alert alert-warning mt-2">
                                    <i class="fas fa-exclamation-triangle me-2"></i>
                                    <strong>Test Environment:</strong> This is a test environment. Use ECPay test credit card numbers for testing:
                                    <br>• Visa: 4000-2211-1111-1111
                                    <br>• MasterCard: 5555-5555-5555-4444
                                    <br>• Any future expiry date and any 3-digit CVV
                                </div>
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
                this.checkMemberStatus();
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

            checkMemberStatus() {
                // Check if user is authenticated via Laravel session
                const isAuthenticated = @json(auth()->guard('member')->check());
                
                if (isAuthenticated) {
                    // User is authenticated via session, show shipping section directly
                    document.getElementById('member-login-section').style.display = 'none';
                    document.getElementById('shipping-section').style.display = 'block';
                    
                    // Try to load member data from session
                    this.loadMemberDataFromSession();
                } else {
                    // Check localStorage token as fallback
                    const token = localStorage.getItem('member_token');
                    
                    if (token) {
                        // User has a token, try to load member data and show shipping section
                        this.loadMemberData().then(() => {
                            document.getElementById('member-login-section').style.display = 'none';
                            document.getElementById('shipping-section').style.display = 'block';
                        });
                    } else {
                        // No authentication, show login section
                        document.getElementById('member-login-section').style.display = 'block';
                        document.getElementById('shipping-section').style.display = 'none';
                    }
                }
            }

            loadMemberDataFromSession() {
                // Get member data from Laravel session
                const memberData = @json(auth()->guard('member')->user());
                
                if (memberData) {
                    // Pre-fill form with member data from session
                    document.getElementById('fullName').value = memberData.name || '';
                    document.getElementById('email').value = memberData.email || '';
                    document.getElementById('phone').value = memberData.phone || '';
                    document.getElementById('address').value = memberData.address || '';
                    document.getElementById('city').value = memberData.city || '';
                    document.getElementById('zipCode').value = memberData.postal_code || '';
                    
                    // Show a message that form was pre-filled
                    this.showToast('Form pre-filled with your member information', 'info');
                }
            }

            async loadMemberData() {
                try {
                    const token = localStorage.getItem('member_token');
                    
                    // Check if member token exists
                    if (!token) {
                        console.log('No member token found - proceeding as guest');
                        return;
                    }
                    
                    const response = await fetch(`${this.apiBaseUrl}/member/auth/me`, {
                        headers: {
                            'Accept': 'application/json',
                            'Authorization': `Bearer ${token}`
                        }
                    });
                    
                    if (response.ok) {
                        const result = await response.json();
                        const memberData = result.data?.member;
                        
                        // Pre-fill form with member data
                        if (memberData) {
                            document.getElementById('fullName').value = memberData.name || '';
                            document.getElementById('email').value = memberData.email || '';
                            document.getElementById('phone').value = memberData.phone || '';
                            document.getElementById('address').value = memberData.address || '';
                            document.getElementById('city').value = memberData.city || '';
                            document.getElementById('zipCode').value = memberData.postal_code || '';
                            
                            // Show a message that form was pre-filled
                            this.showToast('Form pre-filled with your member information', 'info');
                        }
                    } else if (response.status === 401) {
                        // Token is invalid or expired, remove it
                        localStorage.removeItem('member_token');
                        console.log('Member token expired or invalid - proceeding as guest');
                        // Show login section
                        document.getElementById('member-login-section').style.display = 'block';
                        document.getElementById('shipping-section').style.display = 'none';
                    } else {
                        console.log('Failed to load member data - proceeding as guest');
                    }
                } catch (error) {
                    console.log('Error loading member data - proceeding as guest:', error);
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
                const requiredFields = ['fullName', 'email', 'phone', 'address', 'city', 'zipCode'];
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
                // ECPay payment method is always valid since it's the only option
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
                    zipCode: document.getElementById('zipCode').value,
                    saveAddress: document.getElementById('saveAddress').checked
                };
            }

            collectPaymentData() {
                this.paymentData = {
                    method: 'ecpay'
                };
            }

            showPaymentSection() {
                document.getElementById('payment-section').style.display = 'block';
                document.getElementById('proceed-btn').innerHTML = '<i class="fas fa-arrow-right me-2"></i>Review Order';
            }

            showReviewSection() {
                document.getElementById('review-section').style.display = 'block';
                this.renderOrderReview();
                document.getElementById('proceed-btn').innerHTML = '<i class="fas fa-credit-card me-2"></i>Proceed to Payment';
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
                                ${this.shippingData.city} ${this.shippingData.zipCode}<br>
                                ${this.shippingData.phone}
                            </p>
                        </div>
                        <div class="col-md-6">
                            <h6>Payment Method</h6>
                            <p>
                                <i class="fas fa-credit-card me-2"></i>Credit Card Payment via ECPay
                                <br><small class="text-muted">You will be redirected to ECPay's secure payment page</small>
                            </p>
                        </div>
                    </div>
                `;
            }

            async placeOrder() {
                try {
                    // Show loading state
                    this.showToast('Processing your order...', 'info');
                    
                    // Create order and get ECPay payment parameters
                    const orderData = {
                        shipping: this.shippingData,
                        payment: this.paymentData,
                        items: this.cartData.items
                    };

                    // Determine which endpoint to use based on authentication method
                    const isSessionAuthenticated = @json(auth()->guard('member')->check());
                    const memberToken = localStorage.getItem('member_token');
                    
                    let endpoint, headers;
                    
                    if (isSessionAuthenticated) {
                        // Use session-based endpoint
                        endpoint = '/checkout/ecpay';
                        headers = {
                            'Accept': 'application/json',
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': this.csrfToken
                        };
                    } else if (memberToken) {
                        // Use API endpoint with token
                        endpoint = `${this.apiBaseUrl}/orders/ecpay`;
                        headers = {
                            'Accept': 'application/json',
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': this.csrfToken,
                            'Authorization': `Bearer ${memberToken}`
                        };
                    } else {
                        this.showToast('Authentication required', 'error');
                        return;
                    }

                    const response = await fetch(endpoint, {
                        method: 'POST',
                        headers: headers,
                        body: JSON.stringify(orderData)
                    });

                    if (response.ok) {
                        const result = await response.json();
                        
                        // Redirect to ECPay payment page immediately
                        this.redirectToECPay(result.data.ecpay_params);
                    } else {
                        const error = await response.json();
                        this.showToast(error.message || 'Error creating order', 'error');
                    }
                } catch (error) {
                    console.error('Error placing order:', error);
                    this.showToast('Error placing order', 'error');
                }
            }

            redirectToECPay(ecpayParams) {
                // Show redirect message
                this.showToast('Redirecting to ECPay secure payment page...', 'info');
                
                // Create a form to submit to ECPay
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = 'https://payment-stage.ecpay.com.tw/Cashier/AioCheckOut/V5'; // ECPay Test Environment
                form.target = '_self';
                form.style.display = 'none';

                // Add ECPay parameters to form
                Object.keys(ecpayParams).forEach(key => {
                    const input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = key;
                    input.value = ecpayParams[key];
                    form.appendChild(input);
                });

                // Add form to body and submit immediately
                document.body.appendChild(form);
                form.submit();
                
                // Remove form after submission
                setTimeout(() => {
                    if (document.body.contains(form)) {
                        document.body.removeChild(form);
                    }
                }, 1000);
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

        async function loginMember() {
            const email = document.getElementById('memberEmail').value;
            const password = document.getElementById('memberPassword').value;
            
            if (!email || !password) {
                if (window.checkout) {
                    window.checkout.showToast('Please enter both email and password', 'error');
                }
                return;
            }
            
            try {
                const response = await fetch('/api/v1/member/auth/login', {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        email: email,
                        password: password
                    })
                });
                
                if (response.ok) {
                    const result = await response.json();
                    const token = result.data.token;
                    
                    // Store token in localStorage
                    localStorage.setItem('member_token', token);
                    
                    // Hide login section and show shipping section
                    document.getElementById('member-login-section').style.display = 'none';
                    document.getElementById('shipping-section').style.display = 'block';
                    
                    // Load member data to pre-fill form
                    if (window.checkout) {
                        await window.checkout.loadMemberData();
                        window.checkout.showToast('Login successful! Form pre-filled with your information.', 'success');
                    }
                } else {
                    const error = await response.json();
                    if (window.checkout) {
                        window.checkout.showToast(error.message || 'Login failed', 'error');
                    }
                }
            } catch (error) {
                console.error('Login error:', error);
                if (window.checkout) {
                    window.checkout.showToast('Login failed. Please try again.', 'error');
                }
            }
        }

        function continueAsGuest() {
            // Hide login section and show shipping section
            document.getElementById('member-login-section').style.display = 'none';
            document.getElementById('shipping-section').style.display = 'block';
            
            if (window.checkout) {
                window.checkout.showToast('Continuing as guest. You can still create an account after checkout.', 'info');
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
    
    .alert-info {
        background-color: #e7f3ff;
        border-color: #b8daff;
        color: #004085;
    }
    
    .btn-outline-primary {
        border-color: #0d6efd;
        color: #0d6efd;
    }
    
    .btn-outline-primary:hover {
        background-color: #0d6efd;
        border-color: #0d6efd;
        color: white;
    }
    
    .btn-outline-secondary {
        border-color: #6c757d;
        color: #6c757d;
    }
    
    .btn-outline-secondary:hover {
        background-color: #6c757d;
        border-color: #6c757d;
        color: white;
    }
    
    .alert-warning {
        background-color: #fff3cd;
        border-color: #ffeaa7;
        color: #856404;
    }
    
    .alert-warning .fas {
        color: #f39c12;
    }
</style>
@endpush