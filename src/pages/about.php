<?php
// About Us page
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us - TechGear</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/utility.css">
    <link rel="stylesheet" href="../assets/css/user-actions.css">
    <link rel="stylesheet" href="../assets/css/no-outline.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="../assets/js/auth.js" defer></script>
    <script src="../assets/js/app.js" defer></script>
    <style>
        /* Page-specific styles */
        .about-hero {
            background: linear-gradient(rgba(0,0,0,0.7), rgba(0,0,0,0.7)), url('../assets/images/h1.jpg');
            background-size: cover;
            background-position: center;
            padding: 100px 0;
            text-align: center;
            color: #fff;
        }
        
        .about-hero h1 {
            font-size: 3rem;
            margin-bottom: 1rem;
            opacity: 0;
            transform: translateY(20px);
            animation: fadeInUp 0.8s forwards 0.2s;
        }
        
        .about-hero p {
            font-size: 1.2rem;
            max-width: 600px;
            margin: 0 auto;
            opacity: 0;
            transform: translateY(20px);
            animation: fadeInUp 0.8s forwards 0.4s;
        }
        
        .about-content {
            max-width: 1000px;
            margin: 60px auto;
            padding: 0 20px;
        }
        
        .about-section {
            margin-bottom: 80px;
            opacity: 0;
            transform: translateY(30px);
            transition: opacity 0.8s, transform 0.8s;
        }
        
        .about-section.visible {
            opacity: 1;
            transform: translateY(0);
        }
        
        .about-section h2 {
            color: var(--primary-color);
            margin-bottom: 20px;
            font-size: 1.8rem;
        }
        
        .about-section p {
            line-height: 1.8;
            margin-bottom: 20px;
        }
        
        .social-links {
            display: flex;
            justify-content: center;
            gap: 10px;
        }
        
        .social-links a {
            color: var(--primary-color);
            transition: color 0.3s;
        }
        
        .social-links a:hover {
            color: var(--accent-color);
        }
        
        .values-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 30px;
            margin-top: 30px;
        }
        
        .value-card {
            background-color: var(--background-alt);
            padding: 30px;
            border-radius: 10px;
            text-align: center;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            transition: transform 0.3s, box-shadow 0.3s;
        }
        
        .value-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.15);
        }
        
        .value-card i {
            font-size: 2.5rem;
            color: var(--primary-color);
            margin-bottom: 20px;
        }
        
        .value-card h3 {
            margin-bottom: 15px;
        }
        
        /* Animation */
        @keyframes fadeInUp {
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        /* Trust indicators styling */
        .trust-indicators {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            margin: 40px 0;
            gap: 20px;
        }
        
        .trust-stat {
            flex: 1;
            min-width: 200px;
            background-color: var(--background-alt);
            padding: 30px;
            border-radius: 10px;
            text-align: center;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            transition: transform 0.3s;
        }
        
        .trust-stat:hover {
            transform: translateY(-5px);
        }
        
        .trust-number {
            font-size: 2.5rem;
            font-weight: 700;
            color: var(--primary-color);
            margin-bottom: 10px;
        }
        
        .trust-label {
            font-size: 1rem;
            color: var(--secondary-text-color);
        }
        
        .star-rating {
            margin-top: 10px;
            color: #FFD700;
        }
        
        /* Testimonials styling */
        .testimonials {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 30px;
            margin-top: 40px;
        }
        
        .testimonial {
            background-color: var(--background-alt);
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            position: relative;
        }
        
        .testimonial::before {
            content: '"';
            font-size: 5rem;
            color: rgba(var(--primary-color-rgb), 0.1);
            position: absolute;
            top: 10px;
            left: 10px;
            line-height: 1;
        }
        
        .quote {
            font-style: italic;
            margin-bottom: 15px;
            position: relative;
            z-index: 1;
        }
        
        .author {
            font-weight: 600;
            color: var(--primary-color);
        }
        
        @media screen and (max-width: 768px) {
            .trust-indicators {
                flex-direction: column;
            }
            
            .testimonials {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <!-- PHP include for header -->
    <?php include('../components/header.html'); ?>
    
    <section class="about-hero">
        <h1>About TechGear</h1>
        <p>Your premier destination for high-quality tech accessories</p>
    </section>
    
    <main class="about-content">
        <section class="about-section" id="our-story">
            <h2>Our Story</h2>
            <p>Founded in 2020, TechGear started as a small online shop run by tech enthusiasts who were passionate about quality peripherals and accessories. What began as a hobby quickly grew into a thriving business serving customers worldwide.</p>
            
            <p>Our founders were frustrated with the lack of high-quality yet affordable tech products on the market. After countless hours of research and development, they created TechGear to fill this gap and bring premium tech accessories to everyone at reasonable prices.</p>
            
            <p>Today, TechGear is proud to be one of the leading tech accessories retailers, with a vast catalog of premium products, international shipping capabilities, and most importantly, a community of over 100,000 satisfied customers.</p>
        </section>
        
        <section class="about-section" id="our-values">
            <h2>Our Values</h2>
            <p>At TechGear, our core values guide everything we do. From product selection to customer service, these principles ensure we stay true to our mission of providing quality tech gear for everyone.</p>
            
            <div class="values-grid">
                <div class="value-card">
                    <i class="fas fa-award"></i>
                    <h3>Quality</h3>
                    <p>We never compromise on quality. Every product in our catalog is thoroughly tested and meets our high standards.</p>
                </div>
                <div class="value-card">
                    <i class="fas fa-dollar-sign"></i>
                    <h3>Affordability</h3>
                    <p>Premium doesn't have to mean expensive. We work hard to offer the best value for your money.</p>
                </div>
                <div class="value-card">
                    <i class="fas fa-users"></i>
                    <h3>Community</h3>
                    <p>We actively engage with our community of users to improve our products and services.</p>
                </div>
                <div class="value-card">
                    <i class="fas fa-leaf"></i>
                    <h3>Sustainability</h3>
                    <p>We're committed to reducing our environmental impact through eco-friendly packaging and practices.</p>
                </div>
            </div>
        </section>
        
        <section class="about-section" id="our-guarantees">
            <h2>Our Guarantees</h2>
            <p>At TechGear, we stand behind every product we sell. We understand that online shopping requires trust, which is why we offer comprehensive guarantees to ensure your complete satisfaction.</p>
            
            <div class="values-grid">
                <div class="value-card">
                    <i class="fas fa-shield-alt"></i>
                    <h3>100% Money-Back Guarantee</h3>
                    <p>Not satisfied with your purchase? Return it within 30 days for a full refund, no questions asked.</p>
                </div>
                <div class="value-card">
                    <i class="fas fa-check-circle"></i>
                    <h3>Quality Assurance</h3>
                    <p>Every product undergoes rigorous testing before reaching our inventory. We guarantee authentic, high-quality merchandise.</p>
                </div>
                <div class="value-card">
                    <i class="fas fa-truck"></i>
                    <h3>Delivery Guarantee</h3>
                    <p>We guarantee on-time delivery or we'll refund your shipping costs. Track your order from warehouse to doorstep.</p>
                </div>
                <div class="value-card">
                    <i class="fas fa-lock"></i>
                    <h3>Secure Payments</h3>
                    <p>Shop with confidence using our secure payment gateway with advanced encryption and fraud protection.</p>
                </div>
            </div>
        </section>
        
        <section class="about-section" id="customer-trust">
            <h2>Why Customers Trust Us</h2>
            <p>Our reputation speaks for itself. Here's why thousands of tech enthusiasts choose TechGear as their preferred destination for tech accessories:</p>
            
            <div class="trust-indicators">
                <div class="trust-stat">
                    <div class="trust-number">4.9/5</div>
                    <div class="trust-label">Customer Satisfaction Rating</div>
                    <div class="star-rating">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                    </div>
                </div>
                
                <div class="trust-stat">
                    <div class="trust-number">100K+</div>
                    <div class="trust-label">Happy Customers</div>
                </div>
                
                <div class="trust-stat">
                    <div class="trust-number">99.8%</div>
                    <div class="trust-label">Order Accuracy</div>
                </div>
                
                <div class="trust-stat">
                    <div class="trust-number">24/7</div>
                    <div class="trust-label">Customer Support</div>
                </div>
            </div>
            
            <div class="testimonials">
                <div class="testimonial">
                    <div class="quote">"TechGear delivered exactly what was promised. The quality of their products exceeded my expectations, and their customer service was exceptional."</div>
                    <div class="author">- Michael R., Verified Customer</div>
                </div>
                
                <div class="testimonial">
                    <div class="quote">"I had an issue with my order, and their support team resolved it immediately. Their commitment to customer satisfaction is unmatched."</div>
                    <div class="author">- Sarah K., Verified Customer</div>
                </div>
                
                <div class="testimonial">
                    <div class="quote">"The security and ease of payment processing gave me peace of mind. I'll definitely be shopping here again."</div>
                    <div class="author">- David T., Verified Customer</div>
                </div>
            </div>
        </section>
    </main>
    
    <!-- PHP include for footer -->
    <?php include('../components/footer.html'); ?>
    
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // Update cart icon if function exists
            if (typeof updateCartIcon === 'function') {
                updateCartIcon();
            }
            
            // Intersection Observer for revealing sections on scroll
            const observerOptions = {
                threshold: 0.1
            };
            
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('visible');
                        observer.unobserve(entry.target); // Only animate once
                    }
                });
            }, observerOptions);
            
            // Observe all about sections
            document.querySelectorAll('.about-section').forEach(section => {
                observer.observe(section);
            });
        });
    </script>
</body>
</html>
