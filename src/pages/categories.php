<?php
// Categories page with database connection and fallback
// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Include database connection
require_once(__DIR__ . '/../includes/db_connection.php');

// Check database connection status
global $db_connected;

// Get categories from fallback data or implement category system
function getCategories() {
    return [
        [
            'id' => 'keyboards',
            'name' => 'Keyboards',
            'description' => 'Discover our selection of premium mechanical keyboards, perfect for gaming and productivity.'
        ],
        [
            'id' => 'mice',
            'name' => 'Mice',
            'description' => 'Find the perfect gaming and productivity mice with precision sensors and ergonomic designs.'
        ],
        [
            'id' => 'monitors',
            'name' => 'Monitors',
            'description' => 'Upgrade your visual experience with our high-performance gaming and professional monitors.'
        ],
        [
            'id' => 'headphones',
            'name' => 'Headphones',
            'description' => 'Experience superior audio with our range of gaming headsets and professional headphones.'
        ]
    ];
}

// Get products dynamically using JavaScript from API
$categories = getCategories();

// Create a notification variable that we'll use to display database status
$notification = null;
if (!$db_connected) {
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
        <?php if (!$db_connected): ?>
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
        <div class="product-grid" data-category="<?php echo $category['id']; ?>">
            <!-- Products will be loaded here via JavaScript -->
        </div>
    </section>
    <?php endforeach; ?>

    <!-- PHP include for footer -->
    <?php include('../components/footer.html'); ?>
    
    <!-- PHP include for product modal -->
    <?php include('../components/product-modal.html'); ?>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            console.log('Categories page DOMContentLoaded');
            
            // Load products from database API using the proper function from app.js
            loadProducts();
            
            // Initialize product modal if function exists
            if (typeof setupProductModal === 'function') {
                setupProductModal();
            }
            
            // Update cart icon if function exists
            if (typeof updateCartIcon === 'function') {
                updateCartIcon();
            }
        });
    </script>
</body>
</html>
