<?php
/**
 * Admin Security Functions
 * This file contains security features for admin authentication
 */

// Start session if not already started
function secureSession() {
    if (session_status() === PHP_SESSION_NONE) {
        // Set secure session parameters
        ini_set('session.use_only_cookies', 1);
        ini_set('session.use_strict_mode', 1);
        
        // Set session cookie parameters
        session_set_cookie_params([
            'lifetime' => 3600, // 1 hour
            'path' => '/',
            'domain' => '',
            'secure' => true,  // Only transmit cookie over HTTPS
            'httponly' => true, // Prevent JavaScript access to session cookie
            'samesite' => 'Strict' // Prevent CSRF attacks
        ]);
        
        session_start();
        
        // Regenerate session ID to prevent session fixation
        if (!isset($_SESSION['last_regeneration'])) {
            regenerateSession();
        } else {
            // Regenerate session ID periodically (every 30 minutes)
            $interval = 30 * 60;
            if ($_SESSION['last_regeneration'] + $interval < time()) {
                regenerateSession();
            }
        }
    }
}

// Regenerate session ID
function regenerateSession() {
    // Save current session data
    $old_session_data = $_SESSION;
    
    // Generate new session ID
    session_regenerate_id(true);
    
    // Restore session data
    $_SESSION = $old_session_data;
    
    // Update last regeneration time
    $_SESSION['last_regeneration'] = time();
}

// Check if user is logged in as admin
function isAdminLoggedIn() {
    return isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true && 
           isset($_SESSION['admin_id']) && isset($_SESSION['admin_last_activity']);
}

// Update the last activity timestamp
function updateLastActivity() {
    $_SESSION['admin_last_activity'] = time();
}

// Check for session timeout
function checkSessionTimeout() {
    $timeout_duration = 30 * 60; // 30 minutes
    
    if (isset($_SESSION['admin_last_activity']) && 
        (time() - $_SESSION['admin_last_activity'] > $timeout_duration)) {
        // Session has expired
        forceLogout("Your session has expired. Please log in again.");
    }
    
    // Update the last activity time
    updateLastActivity();
}

// Verify the authentication token for preventing back button navigation after logout
function verifyAuthToken() {
    if (!isset($_SESSION['auth_token']) || !isset($_COOKIE['auth_token']) || 
        $_SESSION['auth_token'] !== $_COOKIE['auth_token']) {
        return false;
    }
    return true;
}

// Force logout with optional message
function forceLogout($message = null) {
    // If a message is provided, store it to be displayed after redirect
    if ($message !== null) {
        $_SESSION['logout_message'] = $message;
    }
    
    // Clear all session data
    session_unset();
    session_destroy();
    
    // Redirect to login page
    header("Location: ../login.php");
    exit();
}

// Create a CSRF token
function generateCSRFToken() {
    if (!isset($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

// Verify CSRF token
function verifyCSRFToken($token) {
    if (!isset($_SESSION['csrf_token']) || $token !== $_SESSION['csrf_token']) {
        forceLogout("Security validation failed. Please log in again.");
        return false;
    }
    return true;
}

// Log login attempts
function logLoginAttempt($username, $success, $ip_address) {
    $log_file = __DIR__ . '/../logs/login_attempts.log';
    $dir = dirname($log_file);
    
    // Create logs directory if it doesn't exist
    if (!is_dir($dir)) {
        mkdir($dir, 0755, true);
    }
    
    // Format log entry
    $timestamp = date('Y-m-d H:i:s');
    $status = $success ? 'SUCCESS' : 'FAILURE';
    $log_entry = "[$timestamp] $status - Username: $username, IP: $ip_address" . PHP_EOL;
    
    // Append to log file
    file_put_contents($log_file, $log_entry, FILE_APPEND);
}

// Check for brute force attacks
function checkBruteForce($ip_address) {
    $log_file = __DIR__ . '/../logs/login_attempts.log';
    
    if (!file_exists($log_file)) {
        return false;
    }
    
    // Read log file
    $logs = file_get_contents($log_file);
    $lines = explode(PHP_EOL, $logs);
    
    // Count failed attempts in the last hour
    $count = 0;
    $one_hour_ago = strtotime('-1 hour');
    
    foreach ($lines as $line) {
        if (empty($line)) continue;
        
        if (strpos($line, "FAILURE") !== false && 
            strpos($line, "IP: $ip_address") !== false) {
            
            $timestamp_str = substr($line, 1, 19); // Extract timestamp
            $timestamp = strtotime($timestamp_str);
            
            if ($timestamp > $one_hour_ago) {
                $count++;
            }
        }
    }
    
    // Limit to 5 failed attempts per hour
    return $count >= 5;
}
