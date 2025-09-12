<?php
// Login Process - Handle user authentication
session_start();

// Include database connection
require_once(__DIR__ . '/../includes/db_connection.php');

// Function to send JSON response
function json_response($success, $message, $data = null) {
    header('Content-Type: application/json');
    echo json_encode([
        'success' => $success,
        'message' => $message,
        'data' => $data
    ]);
    exit;
}

// Function to sanitize input
function sanitize_input($data) {
    return htmlspecialchars(strip_tags(trim($data)));
}

// Function to log user activity
function log_login_attempt($username, $success, $ip_address) {
    error_log("Login attempt - Username: $username, Success: " . ($success ? 'Yes' : 'No') . ", IP: $ip_address");
}

// Only handle POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    json_response(false, 'Only POST requests are allowed');
}

// Check if database connection exists
global $conn, $db_connected;
if (!$db_connected || !$conn) {
    json_response(false, 'Database connection is not available. Please try again later.');
}

try {
    // Get and sanitize form data
    $username_or_email = sanitize_input($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    $remember_me = isset($_POST['remember']) ? true : false;
    
    // Get user's IP address for logging
    $ip_address = $_SERVER['REMOTE_ADDR'] ?? 'unknown';

    // Validation
    if (empty($username_or_email)) {
        json_response(false, 'Username or email is required');
    }

    if (empty($password)) {
        json_response(false, 'Password is required');
    }

    // Check if input is email or username
    $is_email = filter_var($username_or_email, FILTER_VALIDATE_EMAIL);
    
    if ($is_email) {
        // Search by email
        $stmt = $conn->prepare("SELECT id, username, email, fullname, password_hash, status FROM users WHERE email = ? AND status = 'active'");
        $stmt->bind_param("s", $username_or_email);
    } else {
        // Search by username
        $stmt = $conn->prepare("SELECT id, username, email, fullname, password_hash, status FROM users WHERE username = ? AND status = 'active'");
        $stmt->bind_param("s", $username_or_email);
    }
    
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 0) {
        $stmt->close();
        log_login_attempt($username_or_email, false, $ip_address);
        json_response(false, 'Invalid email or password');
    }
    
    $user = $result->fetch_assoc();
    $stmt->close();
    
    // Verify password
    if (!password_verify($password, $user['password_hash'])) {
        log_login_attempt($username_or_email, false, $ip_address);
        json_response(false, 'Invalid email or password');
    }
    
    // Check if account is active
    if ($user['status'] !== 'active') {
        log_login_attempt($username_or_email, false, $ip_address);
        json_response(false, 'Your account is currently inactive. Please contact support.');
    }
    
    // Login successful - create session
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['username'] = $user['username'];
    $_SESSION['email'] = $user['email'];
    $_SESSION['fullname'] = $user['fullname'];
    $_SESSION['logged_in'] = true;
    $_SESSION['login_time'] = time();
    
    // Update last login timestamp
    $stmt = $conn->prepare("UPDATE users SET last_login = CURRENT_TIMESTAMP WHERE id = ?");
    $stmt->bind_param("i", $user['id']);
    $stmt->execute();
    $stmt->close();
    
    // Set remember me cookie if requested (optional - for future enhancement)
    if ($remember_me) {
        // Set a secure remember me cookie (expires in 30 days)
        $remember_token = bin2hex(random_bytes(32));
        $cookie_expires = time() + (30 * 24 * 60 * 60); // 30 days
        
        // Store remember token in database (you might want to add a remember_token column later)
        // For now, we'll just set the cookie
        setcookie('remember_user', $user['id'], $cookie_expires, '/', '', false, true);
    }
    
    log_login_attempt($username_or_email, true, $ip_address);
    
    // Determine redirect URL
    $redirect_url = '/TechGear/index.php';
    
    // Check if there's a checkout redirect
    if (isset($_SESSION['checkout_redirect'])) {
        $redirect_url = $_SESSION['checkout_redirect'];
        unset($_SESSION['checkout_redirect']);
    }
    
    json_response(true, 'Login successful! Welcome back, ' . $user['fullname'] . '!', [
        'user_id' => $user['id'],
        'username' => $user['username'],
        'fullname' => $user['fullname'],
        'redirect' => $redirect_url
    ]);

} catch (Exception $e) {
    error_log("Login error: " . $e->getMessage());
    json_response(false, 'An error occurred during login. Please try again.');
}
?>