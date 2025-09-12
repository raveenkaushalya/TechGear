<?php
// Payment processing endpoint
session_start();

// Include database connection
require_once(__DIR__ . '/../includes/db_connection.php');

// Function to send JSON response
function json_response($success, $message, $data = null) {
    header('Content-Type: application/json');
    echo json_encode([
        'success' => $success,
        'message' => $message,
        'data' => $data,
        'timestamp' => date('Y-m-d H:i:s')
    ]);
    exit;
}

// Function to sanitize input
function sanitize_input($data) {
    return htmlspecialchars(strip_tags(trim($data)));
}

// Function to validate payment data
function validate_payment_data($payment_data) {
    $errors = [];

    // Validate payment method
    if (empty($payment_data['payment_method']) || !in_array($payment_data['payment_method'], ['visa', 'mastercard', 'paypal'])) {
        $errors[] = 'Invalid payment method selected';
    }

    // Validate billing information
    $required_billing = ['first_name', 'last_name', 'email', 'address', 'city', 'zip'];
    foreach ($required_billing as $field) {
        if (empty($payment_data[$field])) {
            $errors[] = ucfirst(str_replace('_', ' ', $field)) . ' is required';
        }
    }

    // Validate email format
    if (!empty($payment_data['email']) && !filter_var($payment_data['email'], FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Invalid email format';
    }

    // Validate payment-specific fields
    if ($payment_data['payment_method'] === 'visa' || $payment_data['payment_method'] === 'mastercard') {
        $card_fields = ['card_number', 'expiry', 'cvv', 'cardholder_name'];
        foreach ($card_fields as $field) {
            if (empty($payment_data[$field])) {
                $errors[] = ucfirst(str_replace('_', ' ', $field)) . ' is required';
            }
        }

        // Validate card number format (basic validation)
        if (!empty($payment_data['card_number'])) {
            $card_number = str_replace(' ', '', $payment_data['card_number']);
            if (!ctype_digit($card_number) || strlen($card_number) < 13 || strlen($card_number) > 19) {
                $errors[] = 'Invalid card number format';
            }
        }

        // Validate expiry format (MM/YY)
        if (!empty($payment_data['expiry'])) {
            if (!preg_match('/^(0[1-9]|1[0-2])\/([0-9]{2})$/', $payment_data['expiry'])) {
                $errors[] = 'Invalid expiry date format (MM/YY)';
            } else {
                // Check if card is not expired
                list($month, $year) = explode('/', $payment_data['expiry']);
                $expiry_date = mktime(0, 0, 0, $month, 1, 2000 + $year);
                if ($expiry_date < time()) {
                    $errors[] = 'Card has expired';
                }
            }
        }

        // Validate CVV
        if (!empty($payment_data['cvv'])) {
            if (!ctype_digit($payment_data['cvv']) || strlen($payment_data['cvv']) < 3 || strlen($payment_data['cvv']) > 4) {
                $errors[] = 'Invalid CVV format';
            }
        }
    } elseif ($payment_data['payment_method'] === 'paypal') {
        if (empty($payment_data['paypal_email']) || !filter_var($payment_data['paypal_email'], FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Valid PayPal email is required';
        }
    }

    return $errors;
}

// Function to generate order ID
function generate_order_id() {
    return 'TG' . date('Ymd') . rand(1000, 9999);
}

// Function to save order to database
function save_order($user_id, $order_data, $payment_data, $conn) {
    try {
        // Start transaction
        $conn->autocommit(false);

        // Insert order
        $order_id = generate_order_id();
        $total_amount = $order_data['total'];
        $payment_method = $payment_data['payment_method'];
        $billing_info = json_encode([
            'first_name' => $payment_data['first_name'],
            'last_name' => $payment_data['last_name'],
            'email' => $payment_data['email'],
            'address' => $payment_data['address'],
            'city' => $payment_data['city'],
            'zip' => $payment_data['zip']
        ]);

        $stmt = $conn->prepare("
            INSERT INTO orders (order_id, user_id, total_amount, payment_method, billing_info, order_status, created_at) 
            VALUES (?, ?, ?, ?, ?, 'pending', NOW())
        ");
        $stmt->bind_param("sidss", $order_id, $user_id, $total_amount, $payment_method, $billing_info);
        $stmt->execute();
        
        $order_table_id = $conn->insert_id;
        $stmt->close();

        // Insert order items
        $items = $order_data['items'];
        $stmt = $conn->prepare("
            INSERT INTO order_items (order_id, product_name, product_price, quantity, subtotal) 
            VALUES (?, ?, ?, ?, ?)
        ");

        foreach ($items as $item) {
            $subtotal = $item['price'] * $item['quantity'];
            $stmt->bind_param("isdid", $order_table_id, $item['name'], $item['price'], $item['quantity'], $subtotal);
            $stmt->execute();
        }
        $stmt->close();

        // Commit transaction
        $conn->commit();
        $conn->autocommit(true);

        return ['success' => true, 'order_id' => $order_id, 'order_table_id' => $order_table_id];

    } catch (Exception $e) {
        // Rollback transaction
        $conn->rollback();
        $conn->autocommit(true);
        error_log("Order save error: " . $e->getMessage());
        return ['success' => false, 'error' => 'Failed to save order'];
    }
}

// Only handle POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    json_response(false, 'Only POST requests are allowed');
}

// Check if user is logged in
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    json_response(false, 'User must be logged in to place order');
}

// Check if database connection exists
global $conn, $db_connected;
if (!$db_connected || !$conn) {
    json_response(false, 'Database connection is not available. Please try again later.');
}

try {
    // Get JSON input
    $input = json_decode(file_get_contents('php://input'), true);
    
    if (!$input) {
        json_response(false, 'Invalid JSON input');
    }

    // Extract and sanitize payment data
    $payment_data = [];
    $required_fields = ['payment_method', 'first_name', 'last_name', 'email', 'address', 'city', 'zip'];
    
    foreach ($required_fields as $field) {
        $payment_data[$field] = sanitize_input($input[$field] ?? '');
    }

    // Add payment-specific fields
    if ($payment_data['payment_method'] === 'visa' || $payment_data['payment_method'] === 'mastercard') {
        $card_fields = ['card_number', 'expiry', 'cvv', 'cardholder_name'];
        foreach ($card_fields as $field) {
            $payment_data[$field] = sanitize_input($input[$field] ?? '');
        }
    } elseif ($payment_data['payment_method'] === 'paypal') {
        $payment_data['paypal_email'] = sanitize_input($input['paypal_email'] ?? '');
    }

    // Get order data
    $order_data = $input['order_data'] ?? [];
    
    if (empty($order_data['items']) || !is_array($order_data['items'])) {
        json_response(false, 'No items in order');
    }

    // Validate payment data
    $validation_errors = validate_payment_data($payment_data);
    if (!empty($validation_errors)) {
        json_response(false, 'Validation failed: ' . implode(', ', $validation_errors));
    }

    // Process payment (simulate payment processing)
    // In a real application, you would integrate with actual payment gateways here
    
    $payment_success = false;
    $payment_response = '';

    if ($payment_data['payment_method'] === 'paypal') {
        // Simulate PayPal processing
        $payment_success = true;
        $payment_response = 'PayPal payment processed successfully';
    } else {
        // Simulate credit card processing
        // In reality, you'd integrate with Stripe, Square, or other payment processors
        $payment_success = true;
        $payment_response = 'Credit card payment processed successfully';
    }

    if (!$payment_success) {
        json_response(false, 'Payment processing failed: ' . $payment_response);
    }

    // Save order to database
    $order_result = save_order($_SESSION['user_id'], $order_data, $payment_data, $conn);
    
    if (!$order_result['success']) {
        json_response(false, 'Order could not be saved: ' . ($order_result['error'] ?? 'Unknown error'));
    }

    // Log successful order
    error_log("Order placed successfully - Order ID: " . $order_result['order_id'] . ", User ID: " . $_SESSION['user_id']);

    // Return success response
    json_response(true, 'Order placed successfully!', [
        'order_id' => $order_result['order_id'],
        'payment_method' => $payment_data['payment_method'],
        'total_amount' => $order_data['total']
    ]);

} catch (Exception $e) {
    error_log("Payment processing error: " . $e->getMessage());
    json_response(false, 'An error occurred while processing your payment. Please try again.');
}
?>