<?php
// Advanced Admin Login Page with Security Features
require_once('includes/security.php');

// Start secure session
secureSession();

// Check if there's a logout message
$message = '';
$messageClass = '';
if (isset($_SESSION['logout_message'])) {
    $message = $_SESSION['logout_message'];
    $messageClass = 'info';
    unset($_SESSION['logout_message']);
}

// Initialize variables
$error = '';
$username = '';

// Handle login form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    $ip_address = $_SERVER['REMOTE_ADDR'];
    
    // Check for brute force attempts
    if (checkBruteForce($ip_address)) {
        $error = 'Too many failed login attempts. Please try again later.';
        logLoginAttempt($username, false, $ip_address);
    } 
    // Hardcoded admin credentials (for demo only - in production, use database)
    else if ($username === 'admin' && $password === 'admin123') {
        // Log successful login
        logLoginAttempt($username, true, $ip_address);
        
        // Set session variables
        $_SESSION['admin_logged_in'] = true;
        $_SESSION['admin_id'] = 1; // In production, use actual user ID
        $_SESSION['admin_username'] = $username;
        $_SESSION['admin_last_activity'] = time();
        
        // Regenerate session ID for security
        regenerateSession();
        
        // Generate initial CSRF token
        generateCSRFToken();
        
        // Redirect to dashboard
        header('Location: dashboard.php');
        exit();
    } else {
        // Log failed login attempt
        logLoginAttempt($username, false, $ip_address);
        $error = 'Invalid username or password.';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - TechGear</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>
        body { display: flex; justify-content: center; align-items: center; height: 100vh; background: #181c20; }
        .admin-login-box { background: #23272b; padding: 2rem 2.5rem; border-radius: 8px; box-shadow: 0 2px 16px #0002; width: 350px; opacity: 0; transform: translateY(30px); transition: opacity 0.6s cubic-bezier(.4,0,.2,1), transform 0.6s cubic-bezier(.4,0,.2,1); }
        .admin-login-box.visible { opacity: 1; transform: translateY(0); }
        .admin-login-box h2 { color: #fff; margin-bottom: 1.5rem; }
        .admin-login-box label { color: #ccc; font-size: 1rem; }
        .admin-login-box input { width: 100%; padding: 0.7rem; margin: 0.5rem 0 1rem 0; border-radius: 4px; border: 1px solid #444; background: #181c20; color: #fff; }
        .admin-login-box button { width: 100%; padding: 0.7rem; background: #007bff; color: #fff; border: none; border-radius: 4px; font-size: 1rem; cursor: pointer; }
        .admin-login-box button:hover { background: #0056b3; }
        .admin-login-box .error { color: #ff4d4f; margin-bottom: 1rem; }
        .admin-login-box .message { margin-bottom: 1rem; padding: 10px; border-radius: 4px; background-color: #3b4044; }
        .admin-login-box .message.info { color: #4cc9f0; border-left: 4px solid #4cc9f0; }
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
<body>
    <div class="loading-overlay" id="loadingOverlay">
        <div class="loading-spinner"></div>
    </div>
    <form class="admin-login-box" method="post" autocomplete="off" id="adminLoginBox">
        <h2>Admin Login</h2>
        <?php if ($error): ?>
            <div class="error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>
        <?php if ($message): ?>
            <div class="message <?= $messageClass ?>"><?= htmlspecialchars($message) ?></div>
        <?php endif; ?>
        <label for="username">Username</label>
        <input type="text" id="username" name="username" required autofocus>
        <label for="password">Password</label>
        <input type="password" id="password" name="password" required>
        <button type="submit">Login</button>
    </form>
    <script>
    // Fade-in animation for login box
    window.addEventListener('DOMContentLoaded', function() {
        setTimeout(function() {
            document.getElementById('adminLoginBox').classList.add('visible');
        }, 100);
    });
    // Show loading overlay on submit
    document.getElementById('adminLoginBox').addEventListener('submit', function() {
        document.getElementById('loadingOverlay').classList.add('active');
    });
    </script>
</body>
</html>
