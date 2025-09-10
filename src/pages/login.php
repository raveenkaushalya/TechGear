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
</head>
<body class="auth-page">
    <!-- PHP include for header -->
    <?php include('../components/header.html'); ?>

    <main class="auth-container">
        <div class="auth-box">
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
            // Update cart icon if function exists
            if (typeof updateCartIcon === 'function') {
                updateCartIcon();
            }
            
            // Form validation
            const loginForm = document.getElementById('login-form');
            if (loginForm) {
                loginForm.addEventListener('submit', function(event) {
                    // Basic form validation can be added here
                    event.preventDefault();
                    
                    // Get the username value
                    const username = document.getElementById('username').value;
                    if (!username) {
                        alert('Please enter a username');
                        return;
                    }
                    
                    // Simulate login (in a real app this would validate against a database)
                    if (typeof loginUser === 'function') {
                        loginUser(username);
                    }
                    
                    // Display success message
                    if (typeof showLoginNotification === 'function') {
                        showLoginNotification('Login successful! Redirecting...', 'success');
                    } else {
                        alert('Login successful!');
                    }
                    
                    // Check if we should redirect to checkout
                    const checkoutRedirect = localStorage.getItem('checkoutRedirect');
                    
                    // Redirect after a short delay
                    setTimeout(() => {
                        if (checkoutRedirect) {
                            localStorage.removeItem('checkoutRedirect'); // Clear the redirect
                            window.location.href = checkoutRedirect;
                        } else {
                            window.location.href = 'index.php';
                        }
                    }, 1500);
                });
            }
        });
    </script>
</body>
</html>
