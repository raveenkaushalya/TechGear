<?php
// Logout Process
session_start();

// Clear all session variables
$_SESSION = array();

// Destroy the session cookie if it exists
if (isset($_COOKIE[session_name()])) {
    setcookie(session_name(), '', time()-3600, '/');
}

// Destroy the session
session_destroy();

// Clear any remember me cookies
if (isset($_COOKIE['remember_user'])) {
    setcookie('remember_user', '', time()-3600, '/');
}

// Send JSON response for AJAX requests
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json');
    echo json_encode(['success' => true, 'message' => 'Logged out successfully']);
    exit;
}

// For direct access, redirect to home page
header('Location: /TechGear/index.php');
exit;
?>