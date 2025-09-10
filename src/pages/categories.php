<?php
// Categories page with database connection and fallback
// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Include the product manager to access products and categories
require_once '../includes/product_manager.php';

// Check database connection status
$dbConnected = $productManager->isDatabaseConnected();
$dbStatus = $productManager->getDatabaseStatus();

// Get all categories (will use fallback data if database is not connected)
$categories = $productManager->getAllCategories();

// Get products from the database based on the category
function getCategoryProducts($categoryId) {
    global $productManager;
    return $productManager->getAllProducts($categoryId);
}

// Create a notification variable that we'll use to display database status
$notification = null;
if (!$dbConnected) {
    $notification = [
        'type' => 'info',
        'message' => 'Using backup product data. Database connection is currently unavailable.'
    ];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TechGear - Product Categories</title>
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
    
    <?php if ($notification): ?>
    <!-- Database status notification -->
    <div class="notification notification-<?php echo $notification['type']; ?>">
        <p><?php echo htmlspecialchars($notification['message']); ?></p>
        <?php if (!$dbConnected): ?>
            <p class="notification-details">
                <small>Note: Products shown are from backup data. Some features may be limited.</small>
            </p>
        <?php endif; ?>
    </div>
    <?php endif; ?>

    <section class="category-hero">
        <div class="hero-content">
            <h2>Our Products</h2>
            <p>Explore our wide range of high-quality tech products</p>
        </div>
    </section>

    <!-- Display each category section with products -->
    <?php foreach ($categories as $category): ?>
    <section id="<?php echo $category['id']; ?>" class="category-section">
        <h2><?php echo htmlspecialchars($category['name']); ?></h2>
        <div class="category-description">
            <p><?php echo htmlspecialchars($category['description']); ?></p>
        </div>
        <div class="product-grid">
            <?php 
            // Get products for this category
            $products = getCategoryProducts($category['id']);
            foreach ($products as $product):
                // Check if product has an image path
                $imagePath = !empty($product['image_path']) ? $product['image_path'] : '../assets/images/placeholder.jpg';
                
                // Determine if it's a limited edition product
                $limitedClass = $product['limited_edition'] ? 'limited-edition' : '';
            ?>
            <div class="product-card <?php echo $limitedClass; ?>" data-product-id="<?php echo $product['id']; ?>">
                <div class="product-image">
                    <img src="<?php echo htmlspecialchars($imagePath); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>">
                </div>
                <div class="product-info">
                    <h3><?php echo htmlspecialchars($product['name']); ?> 
                        <?php if ($product['limited_edition']): ?>
                            <span class="limited-edition-label">(Limited Edition)</span>
                        <?php endif; ?>
                    </h3>
                    <p class="product-description"><?php echo htmlspecialchars($product['description']); ?></p>
                    <p class="product-price">$<?php echo number_format($product['price'], 2); ?></p>
                    <button class="btn-add-to-cart">Add to Cart</button>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </section>
    <?php endforeach; ?>

    <!-- PHP include for footer -->
    <?php include('../components/footer.html'); ?>
    
    <!-- PHP include for product modal -->
    <?php include('../components/product-modal.html'); ?>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize product modal if function exists
            if (typeof setupProductModal === 'function') {
                setupProductModal();
            }
            
            // Update cart icon if function exists
            if (typeof updateCartIcon === 'function') {
                updateCartIcon();
            }
            
            // Setup static product cards with event handlers
            if (typeof setupStaticProductCards === 'function') {
                setupStaticProductCards();
            }
        });
    </script>
</body>
</html>
