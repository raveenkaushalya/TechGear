<?php
/**
 * Admin Auth Middleware
 * Include this file at the top of any admin page that requires authentication
 */

// Include security functions
require_once(__DIR__ . '/security.php');

// Start secure session
secureSession();

// Set no-cache headers to prevent back/forward navigation after logout
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
header("Expires: Sat, 26 Jul 1997 05:00:00 GMT"); // Past date

// Check if user is logged in, redirect to login if not
if (!isAdminLoggedIn()) {
    header('Location: ' . dirname($_SERVER['PHP_SELF']) . '/login.php');
    exit();
}

// Check for session timeout
checkSessionTimeout();

// Verify auth token to prevent back/forward navigation after logout
if (isset($_SESSION['auth_token']) && isset($_COOKIE['auth_token'])) {
    if ($_SESSION['auth_token'] !== $_COOKIE['auth_token']) {
        // Auth token mismatch - likely a back/forward navigation attempt after logout
        forceLogout("Your session has been invalidated. Please log in again.");
        exit;
    }
}

// Generate CSRF token for forms
$csrf_token = generateCSRFToken();

// Add additional verification to prevent back/forward navigation attacks
// Store a random token in both session and cookie, they must match
if (!isset($_SESSION['auth_token']) || !isset($_COOKIE['auth_token']) || 
    $_SESSION['auth_token'] !== $_COOKIE['auth_token']) {
    
    // Generate a new auth token
    $auth_token = bin2hex(random_bytes(16));
    $_SESSION['auth_token'] = $auth_token;
    
    // Set cookie with the same token
    setcookie('auth_token', $auth_token, [
        'expires' => time() + 3600, // 1 hour
        'path' => '/',
        'domain' => '',
        'secure' => true,
        'httponly' => true,
        'samesite' => 'Strict'
    ]);
}
?>
