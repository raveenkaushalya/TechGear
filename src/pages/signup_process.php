<?php
// Signup Process - Handle user registration
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

// Function to validate email format
function is_valid_email($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

// Function to validate password strength
function is_strong_password($password) {
    // At least 8 characters, contains at least one letter and one number
    return strlen($password) >= 8 && preg_match('/[A-Za-z]/', $password) && preg_match('/[0-9]/', $password);
}

// Function to sanitize input
function sanitize_input($data) {
    return htmlspecialchars(strip_tags(trim($data)));
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
    $fullname = sanitize_input($_POST['fullname'] ?? '');
    $email = sanitize_input($_POST['email'] ?? '');
    $username = sanitize_input($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';
    $terms = isset($_POST['terms']) ? true : false;

    // Validation
    $errors = [];

    // Check required fields
    if (empty($fullname)) {
        $errors[] = 'Full name is required';
    }

    if (empty($email)) {
        $errors[] = 'Email is required';
    } elseif (!is_valid_email($email)) {
        $errors[] = 'Please enter a valid email address';
    }

    if (empty($username)) {
        $errors[] = 'Username is required';
    } elseif (strlen($username) < 3) {
        $errors[] = 'Username must be at least 3 characters long';
    } elseif (!preg_match('/^[a-zA-Z0-9_]+$/', $username)) {
        $errors[] = 'Username can only contain letters, numbers, and underscores';
    }

    if (empty($password)) {
        $errors[] = 'Password is required';
    } elseif (!is_strong_password($password)) {
        $errors[] = 'Password must be at least 8 characters long and contain both letters and numbers';
    }

    if ($password !== $confirm_password) {
        $errors[] = 'Passwords do not match';
    }

    if (!$terms) {
        $errors[] = 'You must agree to the Terms of Service and Privacy Policy';
    }

    // If there are validation errors, return them
    if (!empty($errors)) {
        json_response(false, implode('; ', $errors));
    }

    // Check if username already exists
    $stmt = $conn->prepare("SELECT id FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        json_response(false, 'Username already exists. Please choose a different username.');
    }
    $stmt->close();

    // Check if email already exists
    $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        json_response(false, 'Email already exists. Please use a different email address.');
    }
    $stmt->close();

    // Hash the password
    $password_hash = password_hash($password, PASSWORD_DEFAULT);

    // Generate email verification token (for future use)
    $email_verification_token = bin2hex(random_bytes(32));

    // Insert new user
    $stmt = $conn->prepare("
        INSERT INTO users (username, email, fullname, password_hash, email_verification_token) 
        VALUES (?, ?, ?, ?, ?)
    ");
    
    $stmt->bind_param("sssss", $username, $email, $fullname, $password_hash, $email_verification_token);
    
    if ($stmt->execute()) {
        $user_id = $conn->insert_id;
        $stmt->close();
        
        // Don't log the user in automatically - redirect to login page
        json_response(true, 'Account created successfully! Please login with your credentials.', [
            'user_id' => $user_id,
            'username' => $username,
            'redirect' => '/TechGear/src/pages/login.php'
        ]);
    } else {
        $stmt->close();
        json_response(false, 'Failed to create account. Please try again.');
    }

} catch (Exception $e) {
    error_log("Signup error: " . $e->getMessage());
    json_response(false, 'An error occurred during registration. Please try again.');
}
?>