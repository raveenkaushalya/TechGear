<?php
// Contact Us page
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us - TechGear</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/utility.css">
    <link rel="stylesheet" href="../assets/css/user-actions.css">
    <link rel="stylesheet" href="../assets/css/no-outline.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="../assets/js/auth.js" defer></script>
    <script src="../assets/js/app.js" defer></script>
    <style>
        /* Contact page specific styles */
        .contact-hero {
            background: linear-gradient(rgba(0,0,0,0.7), rgba(0,0,0,0.7)), url('../assets/images/k3.jpg');
            background-size: cover;
            background-position: center;
            padding: 100px 0;
            text-align: center;
            color: #fff;
        }
        
        .contact-hero h1 {
            font-size: 3rem;
            margin-bottom: 1rem;
            opacity: 0;
            transform: translateY(20px);
            animation: fadeInUp 0.8s forwards 0.2s;
        }
        
        .contact-hero p {
            font-size: 1.2rem;
            max-width: 600px;
            margin: 0 auto;
            opacity: 0;
            transform: translateY(20px);
            animation: fadeInUp 0.8s forwards 0.4s;
        }
        
        .contact-container {
            max-width: 1200px;
            margin: 60px auto;
            padding: 0 20px;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 50px;
        }
        
        @media (max-width: 768px) {
            .contact-container {
                grid-template-columns: 1fr;
            }
        }
        
        .contact-info {
            opacity: 0;
            transform: translateX(-30px);
            animation: fadeInLeft 0.8s forwards 0.6s;
        }
        
        .contact-form {
            opacity: 0;
            transform: translateX(30px);
            animation: fadeInRight 0.8s forwards 0.6s;
        }
        
        .contact-info h2,
        .contact-form h2 {
            color: var(--primary-color);
            margin-bottom: 25px;
            font-size: 1.8rem;
        }
        
        .contact-info-item {
            margin-bottom: 30px;
            display: flex;
            align-items: flex-start;
        }
        
        .contact-info-item i {
            font-size: 1.5rem;
            color: var(--primary-color);
            margin-right: 15px;
            width: 25px;
            text-align: center;
        }
        
        .contact-info-item .content h3 {
            margin-bottom: 5px;
            font-size: 1.1rem;
        }
        
        .contact-info-item .content p,
        .contact-info-item .content a {
            color: var(--secondary-text-color);
            line-height: 1.6;
            transition: color 0.3s;
        }
        
        .contact-info-item .content a:hover {
            color: var(--primary-color);
        }
        
        .social-links {
            display: flex;
            gap: 15px;
            margin-top: 30px;
        }
        
        .social-links a {
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: var(--background-alt);
            color: var(--primary-color);
            border-radius: 50%;
            font-size: 1.2rem;
            transition: all 0.3s ease;
        }
        
        .social-links a:hover {
            background-color: var(--primary-color);
            color: white;
            transform: translateY(-3px);
        }
        
        .contact-form form {
            display: flex;
            flex-direction: column;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
        }
        
        .form-group input,
        .form-group textarea,
        .form-group select {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-family: inherit;
            font-size: 1rem;
            background-color: var(--input-bg);
            color: var(--text-color);
            transition: border-color 0.3s, box-shadow 0.3s;
        }
        
        .form-group input:focus,
        .form-group textarea:focus,
        .form-group select:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 2px rgba(var(--primary-color-rgb), 0.2);
            outline: none;
        }
        
        .form-group textarea {
            resize: vertical;
            min-height: 120px;
        }
        
        .submit-btn {
            padding: 12px 24px;
            background-color: var(--primary-color);
            color: white;
            border: none;
            border-radius: 4px;
            font-size: 1rem;
            font-weight: 500;
            cursor: pointer;
            transition: background-color 0.3s, transform 0.3s;
            align-self: flex-start;
        }
        
        .submit-btn:hover {
            background-color: var(--primary-color-dark);
            transform: translateY(-3px);
        }
        
        .form-message {
            margin-top: 20px;
            padding: 15px;
            border-radius: 4px;
            font-weight: 500;
            display: none;
        }
        
        .form-message.success {
            background-color: rgba(40, 167, 69, 0.2);
            color: #28a745;
            border: 1px solid #28a745;
        }
        
        .form-message.error {
            background-color: rgba(220, 53, 69, 0.2);
            color: #dc3545;
            border: 1px solid #dc3545;
        }
        
        /* Map section */
        .map-section {
            margin: 60px 0;
            opacity: 0;
            transform: translateY(30px);
            animation: fadeInUp 0.8s forwards 0.8s;
        }
        
        .map-container {
            height: 400px;
            width: 100%;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        
        .map-container iframe {
            width: 100%;
            height: 100%;
            border: 0;
        }
        
        /* FAQ Section */
        .faq-section {
            max-width: 800px;
            margin: 60px auto;
            padding: 0 20px;
            opacity: 0;
            transform: translateY(30px);
            animation: fadeInUp 0.8s forwards 1s;
        }
        
        .faq-section h2 {
            color: var(--primary-color);
            margin-bottom: 25px;
            font-size: 1.8rem;
            text-align: center;
        }
        
        .faq-item {
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 8px;
            overflow: hidden;
        }
        
        .faq-question {
            padding: 15px 20px;
            background-color: var(--background-alt);
            font-weight: 500;
            cursor: pointer;
            display: flex;
            justify-content: space-between;
            align-items: center;
            transition: background-color 0.3s;
        }
        
        .faq-question:hover {
            background-color: rgba(var(--primary-color-rgb), 0.1);
        }
        
        .faq-question i {
            transition: transform 0.3s;
        }
        
        .faq-item.active .faq-question i {
            transform: rotate(180deg);
        }
        
        .faq-answer {
            padding: 0 20px;
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.4s ease-out, padding 0.4s;
        }
        
        .faq-item.active .faq-answer {
            padding: 15px 20px;
            max-height: 300px;
        }
        
        /* Animations */
        @keyframes fadeInUp {
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        @keyframes fadeInLeft {
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }
        
        @keyframes fadeInRight {
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }
    </style>
</head>
<body>
    <!-- PHP include for header -->
    <?php include('../components/header.html'); ?>
    
    <section class="contact-hero">
        <h1>Contact Us</h1>
        <p>Get in touch with our team. We're here to help!</p>
    </section>
    
    <div class="contact-container">
        <div class="contact-info">
            <h2>Contact Information</h2>
            
            <div class="contact-info-item">
                <i class="fas fa-map-marker-alt"></i>
                <div class="content">
                    <h3>Our Location</h3>
                    <p>123 Tech Street, Silicon Valley<br>CA 94043, United States</p>
                </div>
            </div>
            
            <div class="contact-info-item">
                <i class="fas fa-phone-alt"></i>
                <div class="content">
                    <h3>Phone Number</h3>
                    <p><a href="tel:+11234567890">+1 (123) 456-7890</a></p>
                </div>
            </div>
            
            <div class="contact-info-item">
                <i class="fas fa-envelope"></i>
                <div class="content">
                    <h3>Email Address</h3>
                    <p><a href="mailto:support@techgear.com">support@techgear.com</a></p>
                </div>
            </div>
            
            <div class="contact-info-item">
                <i class="fas fa-clock"></i>
                <div class="content">
                    <h3>Working Hours</h3>
                    <p>Monday - Friday: 9AM - 6PM<br>Saturday: 10AM - 4PM<br>Sunday: Closed</p>
                </div>
            </div>
            
            <div class="social-links">
                <a href="#"><i class="fab fa-facebook-f"></i></a>
                <a href="#"><i class="fab fa-twitter"></i></a>
                <a href="#"><i class="fab fa-instagram"></i></a>
                <a href="#"><i class="fab fa-linkedin-in"></i></a>
            </div>
        </div>
        
        <div class="contact-form">
            <h2>Send Us a Message</h2>
            
            <form id="contactForm">
                <div class="form-group">
                    <label for="name">Full Name</label>
                    <input type="text" id="name" name="name" required>
                </div>
                
                <div class="form-group">
                    <label for="email">Email Address</label>
                    <input type="email" id="email" name="email" required>
                </div>
                
                <div class="form-group">
                    <label for="phone">Phone Number (Optional)</label>
                    <input type="tel" id="phone" name="phone">
                </div>
                
                <div class="form-group">
                    <label for="subject">Subject</label>
                    <select id="subject" name="subject" required>
                        <option value="">Select a subject</option>
                        <option value="general">General Inquiry</option>
                        <option value="support">Technical Support</option>
                        <option value="order">Order Status</option>
                        <option value="returns">Returns & Refunds</option>
                        <option value="feedback">Feedback</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="message">Your Message</label>
                    <textarea id="message" name="message" required></textarea>
                </div>
                
                <button type="submit" class="submit-btn">Send Message</button>
                
                <div class="form-message" id="formMessage"></div>
            </form>
        </div>
    </div>
    
    <section class="map-section">
        <div class="map-container">
            <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3168.6284523892463!2d-122.08374492395372!3d37.422740731788236!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x808fba02425dad8f%3A0x713f143f006a04d8!2sGoogleplex!5e0!3m2!1sen!2sus!4v1712383690229!5m2!1sen!2sus" allowfullscreen="" loading="lazy"></iframe>
        </div>
    </section>
    
    <section class="faq-section">
        <h2>Frequently Asked Questions</h2>
        
        <div class="faq-item">
            <div class="faq-question">
                How long does shipping take? <i class="fas fa-chevron-down"></i>
            </div>
            <div class="faq-answer">
                <p>Standard shipping typically takes 3-5 business days within the continental US. Express shipping options are available at checkout for 1-2 day delivery. International shipping varies by location and can take 7-14 business days.</p>
            </div>
        </div>
        
        <div class="faq-item">
            <div class="faq-question">
                What is your return policy? <i class="fas fa-chevron-down"></i>
            </div>
            <div class="faq-answer">
                <p>We offer a 30-day return policy on most items. Products must be in their original packaging and condition. Some items like earbuds may have hygiene restrictions. Please contact our support team before initiating a return.</p>
            </div>
        </div>
        
        <div class="faq-item">
            <div class="faq-question">
                Do you offer international shipping? <i class="fas fa-chevron-down"></i>
            </div>
            <div class="faq-answer">
                <p>Yes, we ship to over 25 countries worldwide. International customers may be responsible for import duties and taxes according to their country's regulations. Shipping costs and delivery times vary by location.</p>
            </div>
        </div>
        
        <div class="faq-item">
            <div class="faq-question">
                How do I track my order? <i class="fas fa-chevron-down"></i>
            </div>
            <div class="faq-answer">
                <p>Once your order ships, you'll receive a confirmation email with tracking information. You can also log into your TechGear account to view order status and tracking details for all your purchases.</p>
            </div>
        </div>
    </section>
    
    <!-- PHP include for footer -->
    <?php include('../components/footer.html'); ?>
    
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // Update cart icon if function exists
            if (typeof updateCartIcon === 'function') {
                updateCartIcon();
            }
            
            // Handle FAQ accordion
            document.querySelectorAll('.faq-question').forEach(question => {
                question.addEventListener('click', () => {
                    const item = question.parentElement;
                    
                    // Close all other items
                    document.querySelectorAll('.faq-item').forEach(faqItem => {
                        if (faqItem !== item) {
                            faqItem.classList.remove('active');
                        }
                    });
                    
                    // Toggle current item
                    item.classList.toggle('active');
                });
            });
            
            // Contact form submission
            const contactForm = document.getElementById('contactForm');
            const formMessage = document.getElementById('formMessage');
            
            contactForm.addEventListener('submit', (e) => {
                e.preventDefault();
                
                // Get form data
                const formData = new FormData(contactForm);
                const formValues = {};
                for (const [key, value] of formData.entries()) {
                    formValues[key] = value;
                }
                
                // Simple form validation
                if (!formValues.name || !formValues.email || !formValues.message) {
                    showFormMessage('Please fill in all required fields.', 'error');
                    return;
                }
                
                // Email validation
                const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if (!emailPattern.test(formValues.email)) {
                    showFormMessage('Please enter a valid email address.', 'error');
                    return;
                }
                
                // In a real application, you would send this data to a server
                // For this demo, we'll simulate a successful submission
                setTimeout(() => {
                    showFormMessage('Your message has been sent successfully! We will get back to you soon.', 'success');
                    contactForm.reset();
                }, 1000);
                
                // Show "sending" message
                showFormMessage('Sending your message...', 'info');
            });
            
            function showFormMessage(message, type) {
                formMessage.textContent = message;
                formMessage.className = 'form-message';
                formMessage.classList.add(type);
                formMessage.style.display = 'block';
                
                if (type === 'success') {
                    setTimeout(() => {
                        formMessage.style.display = 'none';
                    }, 5000);
                }
            }
            
            // Add input animation effects
            const formInputs = document.querySelectorAll('.form-group input, .form-group textarea, .form-group select');
            
            formInputs.forEach(input => {
                input.addEventListener('focus', () => {
                    input.parentElement.classList.add('focused');
                });
                
                input.addEventListener('blur', () => {
                    if (!input.value) {
                        input.parentElement.classList.remove('focused');
                    }
                });
            });
        });
    </script>
</body>
</html>
