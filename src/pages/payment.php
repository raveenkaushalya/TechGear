<?php
// Payment page with authentication check
session_start();

// Check if user is logged in
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    // Store checkout redirect for after login
    $_SESSION['checkout_redirect'] = '/TechGear/src/pages/payment.php';
    header('Location: /TechGear/src/pages/login.php?redirect=checkout');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment - TechGear</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/utility.css">
    <link rel="stylesheet" href="../assets/css/user-actions.css">
    <link rel="stylesheet" href="../assets/css/no-outline.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="../assets/js/auth.js" defer></script>
    <script src="../assets/js/app.js" defer></script>
    <style>
        /* Payment page specific styles */
        .payment-container {
            max-width: 1200px;
            margin: 2rem auto;
            padding: 0 1rem;
        }

        .payment-layout {
            display: grid;
            grid-template-columns: 1fr 400px;
            gap: 2rem;
            margin-top: 2rem;
        }

        .payment-form-section {
            background: var(--dark-secondary);
            padding: 2rem;
            border-radius: 10px;
            border: 1px solid var(--primary-color);
        }

        .order-summary {
            background: var(--dark-secondary);
            padding: 2rem;
            border-radius: 10px;
            border: 1px solid var(--primary-color);
            height: fit-content;
        }

        .payment-method {
            margin-bottom: 2rem;
        }

        .payment-method h3 {
            color: var(--primary-color);
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .payment-options {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 1rem;
            margin-bottom: 2rem;
        }

        .payment-option {
            border: 2px solid var(--border-color);
            border-radius: 10px;
            padding: 1rem;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s ease;
            background: var(--dark-color);
        }

        .payment-option:hover {
            border-color: var(--primary-color);
            background: rgba(0, 191, 255, 0.1);
        }

        .payment-option.selected {
            border-color: var(--primary-color);
            background: rgba(0, 191, 255, 0.2);
        }

        .payment-option input[type="radio"] {
            display: none;
        }

        .payment-option img {
            width: 40px;
            height: 30px;
            object-fit: contain;
            margin-bottom: 0.5rem;
        }

        .payment-option .payment-icon {
            font-size: 2rem;
            color: var(--primary-color);
            margin-bottom: 0.5rem;
        }

        .payment-details {
            display: none;
            margin-top: 1rem;
            padding: 1rem;
            background: var(--dark-color);
            border-radius: 5px;
        }

        .payment-details.active {
            display: block;
        }

        .form-group {
            margin-bottom: 1rem;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            color: var(--light-color);
            font-weight: 500;
        }

        .form-group input {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid var(--border-color);
            border-radius: 5px;
            background: var(--dark-color);
            color: var(--light-color);
            font-size: 1rem;
        }

        .form-group input:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(0, 191, 255, 0.1);
        }

        .form-row {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 1rem;
        }

        .billing-info {
            margin-top: 2rem;
        }

        .billing-info h3 {
            color: var(--primary-color);
            margin-bottom: 1rem;
        }

        .order-item {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 1rem 0;
            border-bottom: 1px solid var(--border-color);
        }

        .order-item:last-child {
            border-bottom: none;
        }

        .order-item img {
            width: 60px;
            height: 60px;
            object-fit: cover;
            border-radius: 5px;
        }

        .order-item-details {
            flex-grow: 1;
        }

        .order-item-name {
            font-weight: 600;
            color: var(--light-color);
            margin-bottom: 0.25rem;
        }

        .order-item-price {
            color: var(--primary-color);
            font-weight: 500;
        }

        .order-total {
            margin-top: 1rem;
            padding-top: 1rem;
            border-top: 2px solid var(--primary-color);
        }

        .total-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 0.5rem;
        }

        .total-row.grand-total {
            font-size: 1.2rem;
            font-weight: 600;
            color: var(--primary-color);
        }

        .place-order-btn {
            width: 100%;
            background: var(--primary-color);
            color: var(--dark-color);
            border: none;
            padding: 1rem;
            border-radius: 5px;
            font-size: 1.1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-top: 2rem;
        }

        .place-order-btn:hover {
            background: var(--light-color);
            transform: translateY(-2px);
        }

        .place-order-btn:disabled {
            background: #666;
            cursor: not-allowed;
            transform: none;
        }

        .security-info {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            color: #888;
            font-size: 0.9rem;
            margin-top: 1rem;
        }

        @media screen and (max-width: 768px) {
            .payment-layout {
                grid-template-columns: 1fr;
            }

            .form-row {
                grid-template-columns: 1fr;
            }

            .payment-options {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body class="payment-page">
    <!-- PHP include for header -->
    <?php include('../components/header.html'); ?>

    <main class="payment-container">
        <h1><i class="fas fa-credit-card"></i> Secure Checkout</h1>
        
        <div class="payment-layout">
            <div class="payment-form-section">
                <!-- Payment Method Selection -->
                <div class="payment-method">
                    <h3><i class="fas fa-payment"></i> Payment Method</h3>
                    
                    <div class="payment-options">
                        <label class="payment-option" for="visa">
                            <input type="radio" id="visa" name="payment_method" value="visa">
                            <div class="payment-icon">
                                <i class="fab fa-cc-visa"></i>
                            </div>
                            <div>Visa</div>
                        </label>
                        
                        <label class="payment-option" for="mastercard">
                            <input type="radio" id="mastercard" name="payment_method" value="mastercard">
                            <div class="payment-icon">
                                <i class="fab fa-cc-mastercard"></i>
                            </div>
                            <div>MasterCard</div>
                        </label>
                        
                        <label class="payment-option" for="paypal">
                            <input type="radio" id="paypal" name="payment_method" value="paypal">
                            <div class="payment-icon">
                                <i class="fab fa-cc-paypal"></i>
                            </div>
                            <div>PayPal</div>
                        </label>
                    </div>

                    <!-- Credit Card Details -->
                    <div id="card-details" class="payment-details">
                        <div class="form-group">
                            <label for="card-number">Card Number</label>
                            <input type="text" id="card-number" placeholder="1234 5678 9012 3456" maxlength="19">
                        </div>
                        
                        <div class="form-row">
                            <div class="form-group">
                                <label for="expiry">Expiry Date</label>
                                <input type="text" id="expiry" placeholder="MM/YY" maxlength="5">
                            </div>
                            <div class="form-group">
                                <label for="cvv">CVV</label>
                                <input type="text" id="cvv" placeholder="123" maxlength="4">
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="cardholder-name">Cardholder Name</label>
                            <input type="text" id="cardholder-name" placeholder="John Doe">
                        </div>
                    </div>

                    <!-- PayPal Details -->
                    <div id="paypal-details" class="payment-details">
                        <div class="form-group">
                            <label for="paypal-email">PayPal Email</label>
                            <input type="email" id="paypal-email" placeholder="your@email.com">
                        </div>
                        <p style="color: #888; font-size: 0.9rem; margin-top: 1rem;">
                            <i class="fas fa-info-circle"></i> You will be redirected to PayPal to complete your payment securely.
                        </p>
                    </div>
                </div>

                <!-- Billing Information -->
                <div class="billing-info">
                    <h3><i class="fas fa-address-card"></i> Billing Information</h3>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="first-name">First Name</label>
                            <input type="text" id="first-name" placeholder="John" value="<?php echo htmlspecialchars(explode(' ', $_SESSION['fullname'] ?? '')[0] ?? ''); ?>">
                        </div>
                        <div class="form-group">
                            <label for="last-name">Last Name</label>
                            <input type="text" id="last-name" placeholder="Doe" value="<?php echo htmlspecialchars(trim(str_replace(explode(' ', $_SESSION['fullname'] ?? '')[0] ?? '', '', $_SESSION['fullname'] ?? ''))); ?>">
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="email">Email Address</label>
                        <input type="email" id="email" placeholder="john@example.com" value="<?php echo htmlspecialchars($_SESSION['email'] ?? ''); ?>" readonly>
                    </div>
                    
                    <div class="form-group">
                        <label for="address">Street Address</label>
                        <input type="text" id="address" placeholder="123 Main Street">
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="city">City</label>
                            <input type="text" id="city" placeholder="New York">
                        </div>
                        <div class="form-group">
                            <label for="zip">ZIP Code</label>
                            <input type="text" id="zip" placeholder="10001">
                        </div>
                    </div>
                </div>

                <div class="security-info">
                    <i class="fas fa-shield-alt"></i>
                    Your payment information is encrypted and secure
                </div>
            </div>

            <!-- Order Summary -->
            <div class="order-summary">
                <h3><i class="fas fa-receipt"></i> Order Summary</h3>
                
                <div id="order-items">
                    <!-- Order items will be loaded here by JavaScript -->
                </div>

                <div class="order-total">
                    <div class="total-row">
                        <span>Subtotal:</span>
                        <span id="order-subtotal">$0.00</span>
                    </div>
                    <div class="total-row">
                        <span>Shipping:</span>
                        <span id="order-shipping">Free</span>
                    </div>
                    <div class="total-row">
                        <span>Tax (7%):</span>
                        <span id="order-tax">$0.00</span>
                    </div>
                    <div class="total-row grand-total">
                        <span>Total:</span>
                        <span id="order-total">$0.00</span>
                    </div>
                </div>

                <button class="place-order-btn" id="place-order-btn" disabled>
                    <i class="fas fa-lock"></i> Place Order
                </button>
            </div>
        </div>
    </main>

    <!-- PHP include for footer -->
    <?php include('../components/footer.html'); ?>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Load order summary from cart
            loadOrderSummary();
            
            // Set up payment method selection
            setupPaymentMethods();
            
            // Set up form validation
            setupFormValidation();
            
            // Set up order placement
            setupOrderPlacement();
        });

        function loadOrderSummary() {
            const cart = JSON.parse(localStorage.getItem('cart') || '[]');
            const orderItemsContainer = document.getElementById('order-items');
            
            if (cart.length === 0) {
                orderItemsContainer.innerHTML = '<p>Your cart is empty</p>';
                return;
            }

            let subtotal = 0;
            let itemsHTML = '';

            cart.forEach(item => {
                const itemTotal = item.price * item.quantity;
                subtotal += itemTotal;

                itemsHTML += `
                    <div class="order-item">
                        <img src="${item.image}" alt="${item.name}" onerror="this.src='../assets/images/placeholder.jpg'">
                        <div class="order-item-details">
                            <div class="order-item-name">${item.name}</div>
                            <div class="order-item-price">$${item.price.toFixed(2)} × ${item.quantity}</div>
                        </div>
                        <div class="order-item-total">$${itemTotal.toFixed(2)}</div>
                    </div>
                `;
            });

            orderItemsContainer.innerHTML = itemsHTML;

            // Calculate totals
            const tax = subtotal * 0.07;
            const total = subtotal + tax;

            document.getElementById('order-subtotal').textContent = '$' + subtotal.toFixed(2);
            document.getElementById('order-tax').textContent = '$' + tax.toFixed(2);
            document.getElementById('order-total').textContent = '$' + total.toFixed(2);
        }

        function setupPaymentMethods() {
            const paymentOptions = document.querySelectorAll('input[name="payment_method"]');
            const cardDetails = document.getElementById('card-details');
            const paypalDetails = document.getElementById('paypal-details');

            paymentOptions.forEach(option => {
                option.addEventListener('change', function() {
                    // Remove selected class from all options
                    document.querySelectorAll('.payment-option').forEach(opt => {
                        opt.classList.remove('selected');
                    });

                    // Add selected class to current option
                    this.closest('.payment-option').classList.add('selected');

                    // Show/hide payment details
                    cardDetails.classList.remove('active');
                    paypalDetails.classList.remove('active');

                    if (this.value === 'visa' || this.value === 'mastercard') {
                        cardDetails.classList.add('active');
                    } else if (this.value === 'paypal') {
                        paypalDetails.classList.add('active');
                    }

                    validateForm();
                });
            });
        }

        function setupFormValidation() {
            // Format card number input
            const cardNumberInput = document.getElementById('card-number');
            if (cardNumberInput) {
                cardNumberInput.addEventListener('input', function() {
                    let value = this.value.replace(/\s/g, '').replace(/[^0-9]/gi, '');
                    let formattedValue = '';
                    
                    for (let i = 0; i < value.length; i++) {
                        if (i > 0 && i % 4 === 0) {
                            formattedValue += ' ';
                        }
                        formattedValue += value[i];
                    }
                    
                    this.value = formattedValue;
                    validateForm();
                });
            }

            // Format expiry date input
            const expiryInput = document.getElementById('expiry');
            if (expiryInput) {
                expiryInput.addEventListener('input', function() {
                    let value = this.value.replace(/\D/g, '');
                    if (value.length >= 2) {
                        value = value.substring(0, 2) + '/' + value.substring(2, 4);
                    }
                    this.value = value;
                    validateForm();
                });
            }

            // Add validation to all required fields
            const requiredFields = document.querySelectorAll('input[required], input[data-required]');
            requiredFields.forEach(field => {
                field.addEventListener('input', validateForm);
                field.addEventListener('blur', validateForm);
            });
        }

        function validateForm() {
            const selectedPaymentMethod = document.querySelector('input[name="payment_method"]:checked');
            const placeOrderBtn = document.getElementById('place-order-btn');
            
            let isValid = true;

            // Check if payment method is selected
            if (!selectedPaymentMethod) {
                isValid = false;
            }

            // Validate based on selected payment method
            if (selectedPaymentMethod) {
                if (selectedPaymentMethod.value === 'visa' || selectedPaymentMethod.value === 'mastercard') {
                    // Validate credit card fields
                    const cardNumber = document.getElementById('card-number').value;
                    const expiry = document.getElementById('expiry').value;
                    const cvv = document.getElementById('cvv').value;
                    const cardholderName = document.getElementById('cardholder-name').value;

                    if (!cardNumber || cardNumber.replace(/\s/g, '').length < 13) isValid = false;
                    if (!expiry || expiry.length < 5) isValid = false;
                    if (!cvv || cvv.length < 3) isValid = false;
                    if (!cardholderName.trim()) isValid = false;
                } else if (selectedPaymentMethod.value === 'paypal') {
                    // Validate PayPal fields
                    const paypalEmail = document.getElementById('paypal-email').value;
                    if (!paypalEmail || !paypalEmail.includes('@')) isValid = false;
                }
            }

            // Validate billing information
            const firstName = document.getElementById('first-name').value;
            const lastName = document.getElementById('last-name').value;
            const address = document.getElementById('address').value;
            const city = document.getElementById('city').value;
            const zip = document.getElementById('zip').value;

            if (!firstName.trim() || !lastName.trim() || !address.trim() || !city.trim() || !zip.trim()) {
                isValid = false;
            }

            placeOrderBtn.disabled = !isValid;
        }

        function setupOrderPlacement() {
            const placeOrderBtn = document.getElementById('place-order-btn');
            
            placeOrderBtn.addEventListener('click', async function() {
                if (this.disabled) return;

                // Show loading state
                this.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Processing...';
                this.disabled = true;

                try {
                    // Get form data
                    const formData = getFormData();
                    const cart = JSON.parse(localStorage.getItem('cart') || '[]');
                    
                    if (cart.length === 0) {
                        throw new Error('Cart is empty');
                    }

                    // Calculate totals
                    const subtotal = cart.reduce((total, item) => total + (item.price * item.quantity), 0);
                    const tax = subtotal * 0.07;
                    const total = subtotal + tax;

                    // Prepare order data
                    const orderData = {
                        ...formData,
                        order_data: {
                            items: cart,
                            subtotal: subtotal,
                            tax: tax,
                            total: total
                        }
                    };

                    // Submit to payment processing endpoint
                    const response = await fetch('/TechGear/src/pages/process_payment.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify(orderData)
                    });

                    const result = await response.json();

                    if (result.success) {
                        // Clear cart
                        localStorage.removeItem('cart');
                        
                        // Show success message
                        showSuccessModal(result.data);
                        
                        // Redirect after delay
                        setTimeout(() => {
                            window.location.href = '/TechGear/index.php';
                        }, 3000);
                    } else {
                        throw new Error(result.message || 'Payment processing failed');
                    }

                } catch (error) {
                    console.error('Payment error:', error);
                    alert('Payment failed: ' + error.message);
                    
                    // Reset button
                    this.innerHTML = '<i class="fas fa-lock"></i> Place Order';
                    this.disabled = false;
                }
            });
        }

        function getFormData() {
            const selectedPaymentMethod = document.querySelector('input[name="payment_method"]:checked');
            
            const baseData = {
                payment_method: selectedPaymentMethod ? selectedPaymentMethod.value : '',
                first_name: document.getElementById('first-name').value.trim(),
                last_name: document.getElementById('last-name').value.trim(),
                email: document.getElementById('email').value.trim(),
                address: document.getElementById('address').value.trim(),
                city: document.getElementById('city').value.trim(),
                zip: document.getElementById('zip').value.trim()
            };

            // Add payment-specific fields
            if (selectedPaymentMethod && (selectedPaymentMethod.value === 'visa' || selectedPaymentMethod.value === 'mastercard')) {
                baseData.card_number = document.getElementById('card-number').value.trim();
                baseData.expiry = document.getElementById('expiry').value.trim();
                baseData.cvv = document.getElementById('cvv').value.trim();
                baseData.cardholder_name = document.getElementById('cardholder-name').value.trim();
            } else if (selectedPaymentMethod && selectedPaymentMethod.value === 'paypal') {
                baseData.paypal_email = document.getElementById('paypal-email').value.trim();
            }

            return baseData;
        }

        function showSuccessModal(orderData) {
            // Create success modal
            const modal = document.createElement('div');
            modal.style.cssText = `
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background: rgba(0,0,0,0.8);
                display: flex;
                align-items: center;
                justify-content: center;
                z-index: 10000;
            `;

            modal.innerHTML = `
                <div style="
                    background: var(--dark-secondary);
                    padding: 2rem;
                    border-radius: 10px;
                    text-align: center;
                    max-width: 400px;
                    border: 2px solid var(--primary-color);
                ">
                    <i class="fas fa-check-circle" style="font-size: 4rem; color: #28a745; margin-bottom: 1rem;"></i>
                    <h2 style="color: var(--primary-color); margin-bottom: 1rem;">Order Successful!</h2>
                    <p style="color: var(--light-color); margin-bottom: 1rem;">
                        Your order #${orderData.order_id} has been placed successfully.
                    </p>
                    <p style="color: #888; font-size: 0.9rem;">
                        Total: $${orderData.total_amount.toFixed(2)}
                    </p>
                    <p style="color: #888; font-size: 0.9rem; margin-top: 1rem;">
                        Redirecting to home page...
                    </p>
                </div>
            `;

            document.body.appendChild(modal);
        }
    </script>
</body>
</html>