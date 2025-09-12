<?php
// PHP version of index page with server-side includes
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TechGear Shop</title>
    <link rel="stylesheet" href="src/assets/css/style.css">
    <link rel="stylesheet" href="src/assets/css/utility.css">
    <link rel="stylesheet" href="src/assets/css/user-actions.css">
    <link rel="stylesheet" href="src/assets/css/no-outline.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="src/assets/js/auth.js" defer></script>
    <script src="src/assets/js/app.js" defer></script>
</head>
<body>
    <!-- PHP include for header -->
    <?php include('src/components/header.html'); ?>

    <section class="hero">
        <div class="hero-content">
            <h2>Premium Tech Products</h2>
            <p>Find the perfect gear for your setup</p>
            <button class="btn">Shop Now</button>
        </div>
    </section>

    <section class="featured-products">
        <h2>Featured Products</h2>
        <div class="product-grid">
            <!-- Products will be dynamically loaded here -->
        </div>
    </section>

    <section class="categories">
        <h2>Shop by Category</h2>
        <div class="category-grid">
            <div class="category-card">
                <img src="src/assets/images/k1.jpg" alt="Keyboards">
                <h3>Keyboards</h3>
            </div>
            <div class="category-card">
                <img src="src/assets/images/m1-cyberpunk.jpg" alt="Mice">
                <h3>Mice</h3>
            </div>
            <div class="category-card">
                <img src="src/assets/images/mn1.jpg" alt="Monitors">
                <h3>Monitors</h3>
            </div>
            <div class="category-card">
                <img src="src/assets/images/k3.jpg" alt="Headphones">
                <h3>Headphones</h3>
            </div>
        </div>
    </section>

    <!-- PHP include for footer -->
    <?php include('src/components/footer.html'); ?>
    
    <!-- PHP include for product modal -->
    <?php include('src/components/product-modal.html'); ?>
    
    <script>
        // This script initializes the page with PHP-included components
        document.addEventListener('DOMContentLoaded', async () => {
            console.log('Index page DOMContentLoaded');
            
            // Check for redirect messages
            const urlParams = new URLSearchParams(window.location.search);
            const redirected = urlParams.get('redirected');
            if (redirected === 'already_logged_in') {
                showNotification('You are already logged in!', 'info');
            }
            
            // Set flag to prevent app.js from loading products again
            window.productsLoadedByPage = true;
            
            // Product modal is already included via PHP
            if (typeof setupProductModal === 'function') {
                setupProductModal();
            }
            
            // Load products from database only - no static products
            if (typeof loadProducts === 'function') {
                console.log('Calling loadProducts from index.php');
                await loadProducts();
            } else {
                console.error('loadProducts function not found');
            }
            
            if (typeof setupGlobalEventHandlers === 'function') {
                setupGlobalEventHandlers();
            }
            
            if (typeof updateCartIcon === 'function') {
                updateCartIcon();
            }
        });

        function showNotification(message, type = 'info') {
            // Create notification element
            const notification = document.createElement('div');
            notification.style.cssText = `
                position: fixed;
                top: 20px;
                right: 20px;
                background: ${type === 'info' ? '#17a2b8' : '#28a745'};
                color: white;
                padding: 15px 20px;
                border-radius: 5px;
                box-shadow: 0 4px 6px rgba(0,0,0,0.1);
                z-index: 1000;
                max-width: 300px;
                opacity: 0;
                transition: opacity 0.3s ease;
            `;
            notification.textContent = message;
            
            // Add to page
            document.body.appendChild(notification);
            
            // Show notification
            setTimeout(() => {
                notification.style.opacity = '1';
            }, 100);
            
            // Remove after 3 seconds
            setTimeout(() => {
                notification.style.opacity = '0';
                setTimeout(() => {
                    if (notification.parentNode) {
                        notification.parentNode.removeChild(notification);
                    }
                }, 300);
            }, 3000);
        }
    </script>
</body>
</html>
