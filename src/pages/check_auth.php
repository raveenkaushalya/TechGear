<?php
// Check Authentication Status
session_start();

header('Content-Type: application/json');

// Check if user is logged in
$logged_in = isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true;

$response = [
    'logged_in' => $logged_in
];

if ($logged_in) {
    $response['user_data'] = [
        'user_id' => $_SESSION['user_id'] ?? null,
        'username' => $_SESSION['username'] ?? null,
        'email' => $_SESSION['email'] ?? null,
        'fullname' => $_SESSION['fullname'] ?? null
    ];
}

echo json_encode($response);
?>