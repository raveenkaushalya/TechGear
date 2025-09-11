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
        // Load products dynamically from API
        async function loadProducts() {
            try {
                console.log('Loading products from categories page...');
                const res = await fetch('/TechGear/src/admin/api/products.php?status=active');
                console.log('Response status:', res.status);
                
                const json = await res.json();
                console.log('API Response:', json);
                
                if (!json.success) { 
                    console.error('API Error:', json.error); 
                    return; 
                }
                renderProductsByCategory(json.data || []);
            } catch (error) {
                console.error('Failed to load products:', error);
            }
        }

        function renderProductsByCategory(products) {
            console.log('Rendering products by category:', products.length);
            
            // Group products by category using the actual category field from database
            const productsByCategory = {};
            
            products.forEach(product => {
                // Use the category field from the database, fallback to keyword matching for legacy data
                let category = product.category ? product.category.toLowerCase() : null;
                
                // If no category field, use legacy keyword matching
                if (!category) {
                    const categoryMap = {
                        'keyboards': ['keyboard', 'k1', 'k2', 'k3', 'k4', 'k5'],
                        'mice': ['mouse', 'mice', 'm1', 'm2', 'm3', 'm4', 'm5', 'm6'],
                        'monitors': ['monitor', 'mn1', 'mn2', 'mn3', 'mn4', 'mn5'],
                        'headphones': ['headset', 'headphone', 'h1', 'h2', 'h3', 'h4']
                    };
                    
                    const productStr = (product.name + ' ' + product.id + ' ' + (product.image || '')).toLowerCase();
                    category = 'other';
                    
                    for (const [cat, keywords] of Object.entries(categoryMap)) {
                        if (keywords.some(keyword => productStr.includes(keyword))) {
                            category = cat;
                            break;
                        }
                    }
                }
                
                if (!productsByCategory[category]) {
                    productsByCategory[category] = [];
                }
                productsByCategory[category].push(product);
            });
            
            console.log('Products by category:', productsByCategory);

            // Render products in each category grid
            const categoryIds = ['keyboards', 'mice', 'monitors', 'headphones'];
            categoryIds.forEach(categoryId => {
                const grid = document.querySelector(`[data-category="${categoryId}"]`);
                console.log(`Looking for grid: [data-category="${categoryId}"]`, grid);
                
                if (!grid) return;
                
                grid.innerHTML = '';
                const categoryProducts = productsByCategory[categoryId] || [];
                console.log(`Rendering ${categoryProducts.length} products for ${categoryId}`);
                
                categoryProducts.forEach(product => {
                    const productCard = document.createElement('div');
                    productCard.className = 'product-card';
                    productCard.setAttribute('data-product-id', product.id);
                    
                    // Handle different image path formats
                    let imageSrc = product.image || '';
                    if (imageSrc && !imageSrc.startsWith('http')) {
                        // Ensure relative paths work from pages directory
                        if (imageSrc.startsWith('assets/')) {
                            imageSrc = '../' + imageSrc;
                        } else if (imageSrc.startsWith('src/assets/')) {
                            imageSrc = imageSrc.replace('src/assets/', '../assets/');
                        } else if (imageSrc.startsWith('uploads/')) {
                            imageSrc = '../' + imageSrc;
                        }
                        // imageSrc already starting with ../ should work fine
                    }
                    
                    console.log('Product:', product.name, 'Original image path:', product.image, 'Processed path:', imageSrc);
                    
                    productCard.innerHTML = `
                        <div class="product-image">
                            <img src="${imageSrc}" alt="${product.name}" onerror="this.src='../assets/images/placeholder.jpg'">
                        </div>
                        <div class="product-info">
                            <h3>${product.name}</h3>
                            <p class="product-description">${product.description || ''}</p>
                            <p class="product-price">$${parseFloat(product.price || 0).toFixed(2)}</p>
                            <button class="btn-add-to-cart">Add to Cart</button>
                        </div>
                    `;
                    
                    grid.appendChild(productCard);
                });
            });
        }

        document.addEventListener('DOMContentLoaded', function() {
            console.log('Categories page DOMContentLoaded');
            
            // Load products from database API only
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
