<?php
// Enhanced Admin logout script with security features
require_once('includes/security.php');

// Start secure session
secureSession();

// Record the logout event if logged in
if (isAdminLoggedIn()) {
    // Log the logout event
    $username = $_SESSION['admin_username'] ?? 'Unknown';
    $ip_address = $_SERVER['REMOTE_ADDR'];
    $log_file = __DIR__ . '/../logs/user_activity.log';
    $timestamp = date('Y-m-d H:i:s');
    $log_entry = "[$timestamp] LOGOUT - Username: $username, IP: $ip_address" . PHP_EOL;
    
    // Create logs directory if needed
    $dir = dirname($log_file);
    if (!is_dir($dir)) {
        mkdir($dir, 0755, true);
    }
    
    // Append to log file
    file_put_contents($log_file, $log_entry, FILE_APPEND);
}

// Clear session cookies
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
    
    // Also clear the auth token cookie used to prevent back button navigation
    setcookie('auth_token', '', time() - 42000, '/', '', true, true);
}

// Destroy the session
session_unset();
session_destroy();

// Set a logout message
session_start();
$_SESSION['logout_message'] = "You have been successfully logged out.";

// Redirect to login page
header('Location: login.php');
exit();
