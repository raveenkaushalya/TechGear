<?php
// PHP Login page
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - TechGear</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/utility.css">
    <link rel="stylesheet" href="../assets/css/user-actions.css">
    <link rel="stylesheet" href="../assets/css/auth.css">
    <link rel="stylesheet" href="../assets/css/no-outline.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="../assets/js/app.js" defer></script>
    <style>
        .auth-box { opacity: 0; transform: translateY(30px); transition: opacity 0.6s cubic-bezier(.4,0,.2,1), transform 0.6s cubic-bezier(.4,0,.2,1); }
        .auth-box.visible { opacity: 1; transform: translateY(0); }
        .loading-overlay {
            position: fixed;
            top: 0; left: 0; width: 100vw; height: 100vh;
            background: rgba(24,28,32,0.85);
            display: flex; align-items: center; justify-content: center;
            z-index: 9999;
            opacity: 0; pointer-events: none;
            transition: opacity 0.4s cubic-bezier(.4,0,.2,1);
        }
        .loading-overlay.active { opacity: 1; pointer-events: all; }
        .loading-spinner {
            border: 4px solid #23272b;
            border-top: 4px solid #00bfff;
            border-radius: 50%;
            width: 48px; height: 48px;
            animation: spin 1s linear infinite;
        }
        @keyframes spin { 100% { transform: rotate(360deg); } }
    </style>
</head>
<body class="auth-page">
    <!-- PHP include for header -->
    <?php include('../components/header.html'); ?>

    <main class="auth-container">
        <div class="loading-overlay" id="loadingOverlay">
            <div class="loading-spinner"></div>
        </div>
        <div class="auth-box" id="userLoginBox">
            <div class="auth-header">
                <h1>Login to Your Account</h1>
                <p>Welcome back! Please enter your credentials to continue.</p>
            </div>

            <form id="login-form" class="auth-form" method="post" action="login_process.php">
                <div class="form-group">
                    <label for="username">Username or Email</label>
                    <div class="input-icon-wrapper">
                        <i class="fas fa-user"></i>
                        <input type="text" id="username" name="username" placeholder="Enter your username or email" required>
                    </div>
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <div class="input-icon-wrapper">
                        <i class="fas fa-lock"></i>
                        <input type="password" id="password" name="password" placeholder="Enter your password" required>
                    </div>
                </div>

                <div class="form-options">
                    <div class="remember-me">
                        <input type="checkbox" id="remember" name="remember">
                        <label for="remember">Remember me</label>
                    </div>
                    <a href="forgot-password.php" class="forgot-password">Forgot password?</a>
                </div>

                <button type="submit" class="btn btn-auth">Login</button>

                <div class="auth-separator">
                    <span>OR</span>
                </div>

                <div class="social-login">
                    <button type="button" class="btn btn-social btn-google">
                        <i class="fab fa-google"></i> Login with Google
                    </button>
                    <button type="button" class="btn btn-social btn-facebook">
                        <i class="fab fa-facebook-f"></i> Login with Facebook
                    </button>
                </div>
            </form>

            <div class="auth-footer">
                <p>Don't have an account? <a href="signup.php">Sign Up</a></p>
            </div>
        </div>
    </main>

    <!-- PHP include for footer -->
    <?php include('../components/footer.html'); ?>
    
    <script src="../assets/js/auth.js" defer></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Fade-in animation for login box
            setTimeout(function() {
                document.getElementById('userLoginBox').classList.add('visible');
            }, 100);
            
            // Update cart icon if function exists
            if (typeof updateCartIcon === 'function') {
                updateCartIcon();
            }
            
            // Form validation and submission
            const loginForm = document.getElementById('login-form');
            if (loginForm) {
                loginForm.addEventListener('submit', function(event) {
                    event.preventDefault();
                    
                    // Get form data
                    const formData = new FormData(loginForm);
                    const username = document.getElementById('username').value.trim();
                    const password = document.getElementById('password').value;
                    
                    // Basic client-side validation
                    if (!username) {
                        showNotification('Please enter your username or email', 'error');
                        return;
                    }
                    
                    if (!password) {
                        showNotification('Please enter your password', 'error');
                        return;
                    }
                    
                    // Show loading overlay
                    document.getElementById('loadingOverlay').classList.add('active');
                    
                    // Disable submit button to prevent double submission
                    const submitBtn = loginForm.querySelector('button[type="submit"]');
                    const originalText = submitBtn.textContent;
                    submitBtn.disabled = true;
                    submitBtn.textContent = 'Logging in...';
                    
                    // Submit form via AJAX
                    fetch('login_process.php', {
                        method: 'POST',
                        body: formData
                    })
                    .then(response => response.json())
                    .then(data => {
                        // Hide loading overlay
                        document.getElementById('loadingOverlay').classList.remove('active');
                        
                        if (data.success) {
                            showNotification(data.message, 'success');
                            
                            // Update login state if functions exist
                            if (typeof loginUser === 'function') {
                                loginUser(data.data.username);
                            }
                            
                            // Check if there's a checkout redirect from localStorage
                            const checkoutRedirect = localStorage.getItem('checkoutRedirect');
                            let redirectUrl = data.data.redirect || '/TechGear/index.php';
                            
                            if (checkoutRedirect) {
                                localStorage.removeItem('checkoutRedirect');
                                redirectUrl = checkoutRedirect;
                            }
                            
                            // Redirect after successful login
                            setTimeout(() => {
                                window.location.href = redirectUrl;
                            }, 1500);
                        } else {
                            showNotification(data.message, 'error');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        document.getElementById('loadingOverlay').classList.remove('active');
                        showNotification('An error occurred during login. Please try again.', 'error');
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
        });
    </script>
</body>
</html>
