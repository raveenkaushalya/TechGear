<?php
// PHP version of categories page with server-side includes
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

    <section class="category-hero">
        <div class="hero-content">
            <h2>Product</h2>
            <p>Explore our wide range of high-quality tech products</p>
        </div>
    </section>

    <!-- Keyboards Category -->
    <section id="keyboards" class="category-section">
        <h2>Keyboards</h2>
        <div class="category-description">
            <p>Discover our selection of premium mechanical keyboards, perfect for gaming and productivity.</p>
        </div>
        <div class="product-grid">
            <div class="product-card" data-product-id="k1">
                <div class="product-image">
                    <img src="../assets/images/k1.jpg" alt="Mechanical Gaming Keyboard">
                </div>
                <div class="product-info">
                    <h3>RGB Mechanical Keyboard</h3>
                    <p class="product-description">Customizable RGB Mechanical Gaming Keyboard with Blue Switches</p>
                    <p class="product-price">$89.99</p>
                    <button class="btn-add-to-cart">Add to Cart</button>
                </div>
            </div>
            <div class="product-card" data-product-id="k2">
                <div class="product-image">
                    <img src="../assets/images/k2.jpg" alt="Wireless Keyboard">
                </div>
                <div class="product-info">
                    <h3>Wireless Mechanical Keyboard</h3>
                    <p class="product-description">Low-latency Wireless Mechanical Keyboard with Brown Switches</p>
                    <p class="product-price">$129.99</p>
                    <button class="btn-add-to-cart">Add to Cart</button>
                </div>
            </div>
            <div class="product-card" data-product-id="k3">
                <div class="product-image">
                    <img src="../assets/images/k3.jpg" alt="Compact Keyboard">
                </div>
                <div class="product-info">
                    <h3>Compact 60% Keyboard</h3>
                    <p class="product-description">Compact 60% Layout Mechanical Keyboard with PBT Keycaps</p>
                    <p class="product-price">$79.99</p>
                    <button class="btn-add-to-cart">Add to Cart</button>
                </div>
            </div>
            <div class="product-card" data-product-id="k4">
                <div class="product-image">
                    <img src="../assets/images/k4.jpg" alt="Premium Keyboard">
                </div>
                <div class="product-info">
                    <h3>Premium Mechanical Keyboard</h3>
                    <p class="product-description">Premium Aluminum Frame Keyboard with Hot-swappable Switches</p>
                    <p class="product-price">$149.99</p>
                    <button class="btn-add-to-cart">Add to Cart</button>
                </div>
            </div>
            <div class="product-card" data-product-id="k5">
                <div class="product-image">
                    <img src="../assets/images/k5.jpg" alt="Silent Keyboard">
                </div>
                <div class="product-info">
                    <h3>Silent Mechanical Keyboard</h3>
                    <p class="product-description">Ultra-quiet Mechanical Keyboard for Office and Gaming</p>
                    <p class="product-price">$99.99</p>
                    <button class="btn-add-to-cart">Add to Cart</button>
                </div>
            </div>
        </div>
    </section>

    <!-- Mice Category -->
    <section id="mice" class="category-section">
        <h2>Mice</h2>
        <div class="category-description">
            <p>Find the perfect gaming and productivity mice with precision sensors and ergonomic designs.</p>
        </div>
        <div class="product-grid">
            <div class="product-card limited-edition" data-product-id="m1">
                <div class="product-image">
                    <img src="../assets/images/m1-cyberpunk.jpg" alt="Cyberpunk Limited Edition Mouse">
                </div>
                <div class="product-info">
                    <h3>Cyberpunk Edition Mouse <span class="limited-edition-label">(Limited Edition)</span></h3>
                    <p class="product-description">Exclusive Cyberpunk RGB Wireless Gaming Mouse – Only while stocks last!</p>
                    <p class="product-price">$129.99</p>
                    <button class="btn-add-to-cart">Add to Cart</button>
                </div>
            </div>
            <div class="product-card" data-product-id="m2">
                <div class="product-image">
                    <img src="../assets/images/m2.jpg" alt="Wireless Gaming Mouse">
                </div>
                <div class="product-info">
                    <h3>Wireless Gaming Mouse</h3>
                    <p class="product-description">Ultra-lightweight Wireless Gaming Mouse with 20,000 DPI Sensor</p>
                    <p class="product-price">$69.99</p>
                    <button class="btn-add-to-cart">Add to Cart</button>
                </div>
            </div>
            <div class="product-card" data-product-id="m3">
                <div class="product-image">
                    <img src="../assets/images/m3.jpg" alt="Ergonomic Mouse">
                </div>
                <div class="product-info">
                    <h3>Ergonomic Mouse</h3>
                    <p class="product-description">Vertical Ergonomic Mouse for Reduced Wrist Strain</p>
                    <p class="product-price">$49.99</p>
                    <button class="btn-add-to-cart">Add to Cart</button>
                </div>
            </div>
            <div class="product-card">
                <div class="product-image">
                    <img src="../assets/images/m4.jpg" alt="MMO Mouse">
                </div>
                <div class="product-info">
                    <h3>MMO Gaming Mouse</h3>
                    <p class="product-description">MMO Gaming Mouse with 12 Programmable Side Buttons</p>
                    <p class="product-price">$79.99</p>
                    <button class="btn-add-to-cart">Add to Cart</button>
                </div>
            </div>
            <div class="product-card">
                <div class="product-image">
                    <img src="../assets/images/m5.jpg" alt="Premium Gaming Mouse">
                </div>
                <div class="product-info">
                    <h3>Premium Gaming Mouse</h3>
                    <p class="product-description">Ultralight Gaming Mouse with PTFE Feet and Paracord Cable</p>
                    <p class="product-price">$89.99</p>
                    <button class="btn-add-to-cart">Add to Cart</button>
                </div>
            </div>
            <div class="product-card">
                <div class="product-image">
                    <img src="../assets/images/m6.jpg" alt="Classic Mouse">
                </div>
                <div class="product-info">
                    <h3>Classic Mouse</h3>
                    <p class="product-description">Reliable Wired Mouse for Everyday Use</p>
                    <p class="product-price">$29.99</p>
                    <button class="btn-add-to-cart">Add to Cart</button>
                </div>
            </div>
        </div>
    </section>

    <!-- Monitors Category -->
    <section id="monitors" class="category-section">
        <h2>Monitors</h2>
        <div class="category-description">
            <p>Upgrade your visual experience with our high-performance gaming and professional monitors.</p>
        </div>
        <div class="product-grid">
            <div class="product-card">
                <div class="product-image">
                    <img src="../assets/images/mn1.jpg" alt="Gaming Monitor">
                </div>
                <div class="product-info">
                    <h3>144Hz Gaming Monitor</h3>
                    <p class="product-description">27-inch 1440p 144Hz IPS Gaming Monitor with 1ms Response Time</p>
                    <p class="product-price">$299.99</p>
                    <button class="btn-add-to-cart">Add to Cart</button>
                </div>
            </div>
            <div class="product-card">
                <div class="product-image">
                    <img src="../assets/images/mn2.jpg" alt="Ultrawide Monitor">
                </div>
                <div class="product-info">
                    <h3>Ultrawide Monitor</h3>
                    <p class="product-description">34-inch Curved Ultrawide Monitor with 21:9 Aspect Ratio</p>
                    <p class="product-price">$449.99</p>
                    <button class="btn-add-to-cart">Add to Cart</button>
                </div>
            </div>
            <div class="product-card">
                <div class="product-image">
                    <img src="../assets/images/mn3.jpg" alt="4K Monitor">
                </div>
                <div class="product-info">
                    <h3>4K Professional Monitor</h3>
                    <p class="product-description">32-inch 4K Professional Monitor with 99% Adobe RGB Coverage</p>
                    <p class="product-price">$599.99</p>
                    <button class="btn-add-to-cart">Add to Cart</button>
                </div>
            </div>
            <div class="product-card">
                <div class="product-image">
                    <img src="../assets/images/mn4.jpg" alt="240Hz Monitor">
                </div>
                <div class="product-info">
                    <h3>240Hz Esports Monitor</h3>
                    <p class="product-description">24.5-inch 1080p 240Hz TN Monitor for Competitive Gaming</p>
                    <p class="product-price">$349.99</p>
                    <button class="btn-add-to-cart">Add to Cart</button>
                </div>
            </div>
            <div class="product-card">
                <div class="product-image">
                    <img src="../assets/images/mn5.jpg" alt="Budget Monitor">
                </div>
                <div class="product-info">
                    <h3>Budget Gaming Monitor</h3>
                    <p class="product-description">24-inch 1080p 75Hz Monitor with FreeSync Technology</p>
                    <p class="product-price">$179.99</p>
                    <button class="btn-add-to-cart">Add to Cart</button>
                </div>
            </div>
        </div>
    </section>

    <!-- Headphones Category -->
    <section id="headphones" class="category-section">
        <h2>Headphones</h2>
        <div class="category-description">
            <p>Experience superior audio with our range of gaming headsets and professional headphones.</p>
        </div>
        <div class="product-grid">
            <div class="product-card">
                <div class="product-image">
                    <img src="../assets/images/h1.jpg" alt="Wireless Gaming Headset">
                </div>
                <div class="product-info">
                    <h3>Wireless Gaming Headset</h3>
                    <p class="product-description">Low-latency Wireless Gaming Headset with 7.1 Surround Sound</p>
                    <p class="product-price">$129.99</p>
                    <button class="btn-add-to-cart">Add to Cart</button>
                </div>
            </div>
            <div class="product-card">
                <div class="product-image">
                    <img src="../assets/images/h2.jpg" alt="Studio Headphones">
                </div>
                <div class="product-info">
                    <h3>Studio Headphones</h3>
                    <p class="product-description">Professional Studio Monitoring Headphones with Flat Response</p>
                    <p class="product-price">$149.99</p>
                    <button class="btn-add-to-cart">Add to Cart</button>
                </div>
            </div>
            <div class="product-card">
                <div class="product-image">
                    <img src="../assets/images/h3.jpg" alt="Noise Cancelling Headphones">
                </div>
                <div class="product-info">
                    <h3>Noise Cancelling Headphones</h3>
                    <p class="product-description">Wireless Noise Cancelling Headphones with 30-hour Battery Life</p>
                    <p class="product-price">$199.99</p>
                    <button class="btn-add-to-cart">Add to Cart</button>
                </div>
            </div>
            <div class="product-card">
                <div class="product-image">
                    <img src="../assets/images/h4.jpg" alt="Budget Gaming Headset">
                </div>
                <div class="product-info">
                    <h3>Budget Gaming Headset</h3>
                    <p class="product-description">Affordable Gaming Headset with RGB Lighting and Microphone</p>
                    <p class="product-price">$49.99</p>
                    <button class="btn-add-to-cart">Add to Cart</button>
                </div>
            </div>
        </div>
    </section>

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
        });
    </script>
</body>
</html>
