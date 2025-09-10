<?php
// PHP Signup page
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
            
            // Form validation
            const signupForm = document.getElementById('signup-form');
            if (signupForm) {
                signupForm.addEventListener('submit', function(event) {
                    // Basic form validation
                    event.preventDefault();
                    
                    const password = document.getElementById('password').value;
                    const confirmPassword = document.getElementById('confirm-password').value;
                    
                    if (password !== confirmPassword) {
                        alert('Passwords do not match!');
                        return;
                    }
                    
                    // Display a message or redirect
                    alert('Sign up functionality will be implemented soon!');
                    
                    // Simulate successful signup and redirect (remove this when backend is implemented)
                    // window.location.href = 'login.php';
                });
            }
        });
    </script>
</body>
</html>
