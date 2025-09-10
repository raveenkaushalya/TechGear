<?php
// PHP version of cart page with server-side includes
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping Cart - TechGear</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/utility.css">
    <link rel="stylesheet" href="../assets/css/user-actions.css">
    <link rel="stylesheet" href="../assets/css/no-outline.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="../assets/js/auth.js" defer></script>
    <script src="../assets/js/app.js" defer></script>
</head>
<body class="cart-page">
    <!-- PHP include for header -->
    <?php include('../components/header.html'); ?>

    <main class="cart-page-container">
        <h1>Your Shopping Cart</h1>
        <div class="cart-layout">
            <div class="cart-items-list">
                <!-- Cart items will be dynamically loaded here -->
            </div>
            <div class="cart-summary">
                <h2>Summary</h2>
                <div class="summary-row">
                    <span>Subtotal</span>
                    <span id="summary-subtotal">$0.00</span>
                </div>
                <div class="summary-row">
                    <span>Shipping</span>
                    <span id="summary-shipping">Free</span>
                </div>
                <div class="summary-row">
                    <span>Tax (7%)</span>
                    <span id="summary-tax">$0.00</span>
                </div>
                <div class="summary-row total">
                    <span>Total</span>
                    <span id="summary-total">$0.00</span>
                </div>
                <button class="btn checkout-btn">Proceed to Checkout</button>
                <button class="btn continue-shopping">Continue Shopping</button>
            </div>
        </div>
        
        <div class="empty-cart-message">
            <div class="empty-cart-content">
                <i class="fas fa-shopping-cart fa-4x"></i>
                <h2>Your cart is empty</h2>
                <p>Looks like you haven't added any products to your cart yet.</p>
                <a href="categories.php" class="btn">Start Shopping</a>
            </div>
        </div>
    </main>

    <!-- PHP include for footer -->
    <?php include('../components/footer.html'); ?>
    
    <!-- PHP include for product modal -->
    <?php include('../components/product-modal.html'); ?>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize product modal if available
            if (typeof setupProductModal === 'function') {
                setupProductModal();
            }
            
            // Update cart display
            if (typeof renderCart === 'function') {
                renderCart();
            }
            
            // Update cart icon if function exists
            if (typeof updateCartIcon === 'function') {
                updateCartIcon();
            }
            
            // Set up continue shopping button
            document.querySelector('.continue-shopping').addEventListener('click', function() {
                window.location.href = 'categories.php';
            });
        });
    </script>
</body>
</html>
