<?php
// PHP version of index page with server-side includes
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TechGear Shop - PHP Version</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/utility.css">
    <link rel="stylesheet" href="../assets/css/user-actions.css">
    <link rel="stylesheet" href="../assets/css/no-outline.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="../assets/js/app.js" defer></script>
</head>
<body>
    <!-- PHP include for header -->
    <?php include('../components/header.html'); ?>

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
                <img src="../assets/images/k1.jpg" alt="Keyboards">
                <h3>Keyboards</h3>
            </div>
            <div class="category-card">
                <img src="../assets/images/m1-cyberpunk.jpg" alt="Mice">
                <h3>Mice</h3>
            </div>
            <div class="category-card">
                <img src="../assets/images/mn1.jpg" alt="Monitors">
                <h3>Monitors</h3>
            </div>
            <div class="category-card">
                <img src="../assets/images/k3.jpg" alt="Headphones">
                <h3>Headphones</h3>
            </div>
        </div>
    </section>

    <!-- PHP include for footer -->
    <?php include('../components/footer.html'); ?>
    
    <!-- PHP include for product modal -->
    <?php include('../components/product-modal.html'); ?>
    
    <script>
        // This script initializes the page with PHP-included components
        document.addEventListener('DOMContentLoaded', () => {
            // Product modal is already included via PHP
            if (typeof setupProductModal === 'function') {
                setupProductModal();
            }
                
            if (typeof loadProducts === 'function') {
                loadProducts();
            }
            
            if (typeof setupStaticProductCards === 'function') {
                setupStaticProductCards();
            }
            
            if (typeof setupGlobalEventHandlers === 'function') {
                setupGlobalEventHandlers();
            }
            
            if (typeof updateCartIcon === 'function') {
                updateCartIcon();
            }
        });
    </script>
</body>
</html>
