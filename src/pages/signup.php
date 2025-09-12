<?php
// PHP Signup page

// Start session and check if user is already logged in
session_start();

// If user is already logged in, redirect to index.php
if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true && isset($_SESSION['user_id'])) {
    // Add a small delay and message for better UX
    header('Location: /TechGear/index.php?already_logged_in=1');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up - TechGear</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/utility.css">
    <link rel="stylesheet" href="../assets/css/user-actions.css">
    <link rel="stylesheet" href="../assets/css/auth.css">
    <link rel="stylesheet" href="../assets/css/no-outline.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="../assets/js/app.js" defer></script>
</head>
<body class="auth-page">
    <!-- PHP include for header -->
    <?php include('../components/header.html'); ?>

    <main class="auth-container">
        <div class="auth-box signup-box">
            <div class="auth-header">
                <h1>Create Your Account</h1>
                <p>Join TechGear today to enjoy exclusive offers and faster checkout.</p>
            </div>

            <form id="signup-form" class="auth-form" method="post" action="signup_process.php">
                <div class="form-group">
                    <label for="fullname">Full Name</label>
                    <div class="input-icon-wrapper">
                        <i class="fas fa-user"></i>
                        <input type="text" id="fullname" name="fullname" placeholder="Enter your full name" required>
                    </div>
                </div>

                <div class="form-group">
                    <label for="email">Email</label>
                    <div class="input-icon-wrapper">
                        <i class="fas fa-envelope"></i>
                        <input type="email" id="email" name="email" placeholder="Enter your email" required>
                    </div>
                </div>

                <div class="form-group">
                    <label for="username">Username</label>
                    <div class="input-icon-wrapper">
                        <i class="fas fa-user-circle"></i>
                        <input type="text" id="username" name="username" placeholder="Choose a username" required>
                    </div>
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <div class="input-icon-wrapper">
                        <i class="fas fa-lock"></i>
                        <input type="password" id="password" name="password" placeholder="Choose a password" required>
                    </div>
                </div>

                <div class="form-group">
                    <label for="confirm-password">Confirm Password</label>
                    <div class="input-icon-wrapper">
                        <i class="fas fa-lock"></i>
                        <input type="password" id="confirm-password" name="confirm_password" placeholder="Confirm your password" required>
                    </div>
                </div>

                <div class="form-options">
                    <div class="terms-conditions">
                        <input type="checkbox" id="terms" name="terms" required>
                        <label for="terms">I agree to the <a href="#">Terms of Service</a> and <a href="#">Privacy Policy</a></label>
                    </div>
                </div>

                <button type="submit" class="btn btn-auth">Create Account</button>

                <div class="auth-separator">
                    <span>OR</span>
                </div>

                <div class="social-login">
                    <button type="button" class="btn btn-social btn-google">
                        <i class="fab fa-google"></i> Sign Up with Google
                    </button>
                    <button type="button" class="btn btn-social btn-facebook">
                        <i class="fab fa-facebook-f"></i> Sign Up with Facebook
                    </button>
                </div>
            </form>

            <div class="auth-footer">
                <p>Already have an account? <a href="login.php">Login</a></p>
            </div>
        </div>
    </main>

    <!-- PHP include for footer -->
    <?php include('../components/footer.html'); ?>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Update cart icon if function exists
            if (typeof updateCartIcon === 'function') {
                updateCartIcon();
            }
            
            // Form validation and submission
            const signupForm = document.getElementById('signup-form');
            if (signupForm) {
                signupForm.addEventListener('submit', function(event) {
                    event.preventDefault();
                    
                    // Get form data
                    const formData = new FormData(signupForm);
                    const password = document.getElementById('password').value;
                    const confirmPassword = document.getElementById('confirm-password').value;
                    
                    // Client-side validation
                    if (password !== confirmPassword) {
                        showNotification('Passwords do not match!', 'error');
                        return;
                    }
                    
                    if (password.length < 8) {
                        showNotification('Password must be at least 8 characters long!', 'error');
                        return;
                    }
                    
                    if (!/[A-Za-z]/.test(password) || !/[0-9]/.test(password)) {
                        showNotification('Password must contain both letters and numbers!', 'error');
                        return;
                    }
                    
                    // Disable submit button to prevent double submission
                    const submitBtn = signupForm.querySelector('button[type="submit"]');
                    const originalText = submitBtn.textContent;
                    submitBtn.disabled = true;
                    submitBtn.textContent = 'Creating Account...';
                    
                    // Submit form via AJAX
                    fetch('signup_process.php', {
                        method: 'POST',
                        body: formData
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            showNotification(data.message, 'success');
                            
                            // Redirect after successful signup
                            setTimeout(() => {
                                window.location.href = data.data.redirect || '/TechGear/index.php';
                            }, 1500);
                        } else {
                            showNotification(data.message, 'error');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        showNotification('An error occurred. Please try again.', 'error');
                    })
                    .finally(() => {
                        // Re-enable submit button
                        submitBtn.disabled = false;
                        submitBtn.textContent = originalText;
                    });
                });
            }
            
            // Notification function
            function showNotification(message, type = 'info') {
                // Remove existing notifications
                const existingNotifications = document.querySelectorAll('.notification');
                existingNotifications.forEach(notification => notification.remove());
                
                // Create notification element
                const notification = document.createElement('div');
                notification.className = `notification notification-${type}`;
                notification.style.cssText = `
                    position: fixed;
                    top: 20px;
                    right: 20px;
                    background: ${type === 'error' ? '#dc3545' : type === 'success' ? '#28a745' : '#007bff'};
                    color: white;
                    padding: 15px 20px;
                    border-radius: 5px;
                    box-shadow: 0 4px 6px rgba(0,0,0,0.1);
                    z-index: 10000;
                    max-width: 400px;
                    animation: slideIn 0.3s ease-out;
                `;
                
                notification.innerHTML = `
                    <div style="display: flex; align-items: center; gap: 10px;">
                        <i class="fas ${type === 'error' ? 'fa-exclamation-circle' : type === 'success' ? 'fa-check-circle' : 'fa-info-circle'}"></i>
                        <span>${message}</span>
                    </div>
                `;
                
                document.body.appendChild(notification);
                
                // Auto remove after 5 seconds
                setTimeout(() => {
                    notification.style.animation = 'slideOut 0.3s ease-in';
                    setTimeout(() => notification.remove(), 300);
                }, 5000);
                
                // Add CSS animations if not already added
                if (!document.getElementById('notification-styles')) {
                    const style = document.createElement('style');
                    style.id = 'notification-styles';
                    style.textContent = `
                        @keyframes slideIn {
                            from { transform: translateX(100%); opacity: 0; }
                            to { transform: translateX(0); opacity: 1; }
                        }
                        @keyframes slideOut {
                            from { transform: translateX(0); opacity: 1; }
                            to { transform: translateX(100%); opacity: 0; }
                        }
                    `;
                    document.head.appendChild(style);
                }
            }
            
            // Real-time password validation
            const passwordField = document.getElementById('password');
            const confirmPasswordField = document.getElementById('confirm-password');
            
            if (passwordField && confirmPasswordField) {
                function validatePasswords() {
                    const password = passwordField.value;
                    const confirmPassword = confirmPasswordField.value;
                    
                    // Remove previous validation messages
                    const existingMessages = document.querySelectorAll('.validation-message');
                    existingMessages.forEach(msg => msg.remove());
                    
                    // Password strength validation
                    if (password.length > 0 && password.length < 8) {
                        addValidationMessage(passwordField, 'Password must be at least 8 characters long', 'error');
                    } else if (password.length >= 8 && (!/[A-Za-z]/.test(password) || !/[0-9]/.test(password))) {
                        addValidationMessage(passwordField, 'Password must contain both letters and numbers', 'error');
                    } else if (password.length >= 8) {
                        addValidationMessage(passwordField, 'Password looks good!', 'success');
                    }
                    
                    // Confirm password validation
                    if (confirmPassword.length > 0 && password !== confirmPassword) {
                        addValidationMessage(confirmPasswordField, 'Passwords do not match', 'error');
                    } else if (confirmPassword.length > 0 && password === confirmPassword) {
                        addValidationMessage(confirmPasswordField, 'Passwords match!', 'success');
                    }
                }
                
                function addValidationMessage(field, message, type) {
                    const messageElement = document.createElement('small');
                    messageElement.className = `validation-message validation-${type}`;
                    messageElement.style.cssText = `
                        display: block;
                        margin-top: 5px;
                        color: ${type === 'error' ? '#dc3545' : '#28a745'};
                        font-size: 12px;
                    `;
                    messageElement.textContent = message;
                    field.parentNode.parentNode.appendChild(messageElement);
                }
                
                passwordField.addEventListener('input', validatePasswords);
                confirmPasswordField.addEventListener('input', validatePasswords);
            }
        });
    </script>
</body>
</html>
