<?php
/**
 * Payments API Endpoint
 * 
 * Handles CRUD operations for payment management
 * This API provides endpoints for:
 * - GET: Retrieve payments list with pagination and filtering
 * - POST: Create new payment record
 * - PUT: Update payment status or details
 * - DELETE: Void/cancel payment
 */

// Set content type to JSON
header('Content-Type: application/json');

// Enable CORS for development (remove in production)
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

// Handle preflight OPTIONS request
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

// Include database connection
require_once('../includes/config.php');

// Get request method and data
$method = $_SERVER['REQUEST_METHOD'];
$input = json_decode(file_get_contents('php://input'), true);

// Initialize response
$response = [
    'success' => false,
    'message' => '',
    'data' => null
];

try {
    switch ($method) {
        case 'GET':
            $response = handleGetPayments();
            break;
            
        case 'POST':
            $response = handleCreatePayment($input);
            break;
            
        case 'PUT':
            $response = handleUpdatePayment($input);
            break;
            
        case 'DELETE':
            $response = handleVoidPayment();
            break;
            
        default:
            throw new Exception('Method not allowed');
    }
    
} catch (Exception $e) {
    $response = [
        'success' => false,
        'message' => $e->getMessage(),
        'data' => null
    ];
    
    // Set appropriate HTTP status code
    http_response_code(500);
}

// Return JSON response
echo json_encode($response);

/**
 * Handle GET request - Retrieve payments
 */
function handleGetPayments() {
    global $pdo;
    
    // Get query parameters
    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 10;
    $search = isset($_GET['search']) ? $_GET['search'] : '';
    $status = isset($_GET['status']) ? $_GET['status'] : '';
    $method = isset($_GET['method']) ? $_GET['method'] : '';
    $dateFrom = isset($_GET['date_from']) ? $_GET['date_from'] : '';
    $dateTo = isset($_GET['date_to']) ? $_GET['date_to'] : '';
    
    // Calculate offset
    $offset = ($page - 1) * $limit;
    
    // Build base query
    $whereConditions = [];
    $params = [];
    
    // Add search condition
    if (!empty($search)) {
        $whereConditions[] = "(payment_id LIKE ? OR customer_name LIKE ? OR details LIKE ?)";
        $params[] = "%{$search}%";
        $params[] = "%{$search}%";
        $params[] = "%{$search}%";
    }
    
    // Add status filter
    if (!empty($status)) {
        $whereConditions[] = "status = ?";
        $params[] = $status;
    }
    
    // Add payment method filter
    if (!empty($method)) {
        $whereConditions[] = "payment_method = ?";
        $params[] = $method;
    }
    
    // Add date range filter
    if (!empty($dateFrom)) {
        $whereConditions[] = "payment_date >= ?";
        $params[] = $dateFrom;
    }
    
    if (!empty($dateTo)) {
        $whereConditions[] = "payment_date <= ?";
        $params[] = $dateTo . ' 23:59:59';
    }
    
    $whereClause = !empty($whereConditions) ? 'WHERE ' . implode(' AND ', $whereConditions) : '';
    
    try {
        // Check if database connection exists, otherwise use mock data
        if (isset($pdo)) {
            // Get total count
            $countQuery = "SELECT COUNT(*) as total FROM payments {$whereClause}";
            $countStmt = $pdo->prepare($countQuery);
            $countStmt->execute($params);
            $totalPayments = $countStmt->fetch(PDO::FETCH_ASSOC)['total'];
            
            // Get payments data
            $query = "SELECT payment_id, customer_name, amount, payment_date, status, 
                             payment_method, details, created_at, updated_at
                     FROM payments 
                     {$whereClause} 
                     ORDER BY payment_date DESC 
                     LIMIT ? OFFSET ?";
            
            $params[] = $limit;
            $params[] = $offset;
            
            $stmt = $pdo->prepare($query);
            $stmt->execute($params);
            $payments = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            // Get financial summary
            $summaryQuery = "SELECT 
                                SUM(CASE WHEN status = 'Completed' THEN amount ELSE 0 END) as total_revenue,
                                SUM(CASE WHEN status = 'Pending' OR status = 'Processing' THEN amount ELSE 0 END) as pending_amount,
                                SUM(CASE WHEN status = 'Refunded' THEN amount ELSE 0 END) as refunded_amount,
                                SUM(CASE WHEN status = 'Failed' THEN amount ELSE 0 END) as failed_amount,
                                COUNT(*) as total_transactions
                             FROM payments";
            
            $summaryStmt = $pdo->prepare($summaryQuery);
            $summaryStmt->execute();
            $summary = $summaryStmt->fetch(PDO::FETCH_ASSOC);
            
        } else {
            // Fallback to mock data when no database connection
            $allPayments = getMockPayments();
            $payments = $allPayments;
            $totalPayments = count($payments);
            
            // Apply filters to mock data
            if (!empty($search)) {
                $payments = array_filter($payments, function($payment) use ($search) {
                    return stripos($payment['payment_id'], $search) !== false || 
                           stripos($payment['customer_name'], $search) !== false ||
                           stripos($payment['details'], $search) !== false;
                });
            }
            
            if (!empty($status)) {
                $payments = array_filter($payments, function($payment) use ($status) {
                    return $payment['status'] === $status;
                });
            }
            
            if (!empty($method)) {
                $payments = array_filter($payments, function($payment) use ($method) {
                    return $payment['payment_method'] === $method;
                });
            }
            
            // Apply pagination
            $payments = array_slice($payments, $offset, $limit);
            
            // Mock financial summary
            $summary = [
                'total_revenue' => 125647.50,
                'pending_amount' => 8945.75,
                'refunded_amount' => 2134.25,
                'failed_amount' => 3567.89,
                'total_transactions' => count($allPayments)
            ];
        }
        
        return [
            'success' => true,
            'message' => 'Payments retrieved successfully',
            'data' => [
                'payments' => $payments,
                'summary' => $summary,
                'pagination' => [
                    'current_page' => $page,
                    'per_page' => $limit,
                    'total' => $totalPayments,
                    'total_pages' => ceil($totalPayments / $limit)
                ]
            ]
        ];
        
    } catch (PDOException $e) {
        throw new Exception('Database error: ' . $e->getMessage());
    }
}

/**
 * Handle POST request - Create new payment record
 */
function handleCreatePayment($input) {
    global $pdo;
    
    // Validate required fields
    $required = ['customer_name', 'amount', 'payment_method'];
    foreach ($required as $field) {
        if (empty($input[$field])) {
            throw new Exception("Missing required field: {$field}");
        }
    }
    
    // Validate amount
    $amount = floatval($input['amount']);
    if ($amount <= 0) {
        throw new Exception('Amount must be greater than 0');
    }
    
    // Generate payment ID
    $paymentId = 'PAY-' . date('Ymd') . '-' . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT);
    
    // Set default values
    $paymentData = [
        'payment_id' => $paymentId,
        'customer_name' => trim($input['customer_name']),
        'amount' => $amount,
        'payment_method' => $input['payment_method'],
        'status' => $input['status'] ?? 'Pending',
        'details' => $input['details'] ?? '',
        'payment_date' => date('Y-m-d H:i:s'),
        'created_at' => date('Y-m-d H:i:s')
    ];
    
    try {
        if (isset($pdo)) {
            // Insert new payment
            $query = "INSERT INTO payments (payment_id, customer_name, amount, payment_method, status, details, payment_date, created_at) 
                     VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
            
            $stmt = $pdo->prepare($query);
            $stmt->execute([
                $paymentData['payment_id'],
                $paymentData['customer_name'],
                $paymentData['amount'],
                $paymentData['payment_method'],
                $paymentData['status'],
                $paymentData['details'],
                $paymentData['payment_date'],
                $paymentData['created_at']
            ]);
            
            $id = $pdo->lastInsertId();
            
            // Return created payment data
            $paymentQuery = "SELECT * FROM payments WHERE id = ?";
            $paymentStmt = $pdo->prepare($paymentQuery);
            $paymentStmt->execute([$id]);
            $newPayment = $paymentStmt->fetch(PDO::FETCH_ASSOC);
            
        } else {
            // Mock response when no database
            $newPayment = $paymentData;
            $newPayment['id'] = rand(1000, 9999);
        }
        
        return [
            'success' => true,
            'message' => 'Payment recorded successfully',
            'data' => $newPayment
        ];
        
    } catch (PDOException $e) {
        throw new Exception('Database error: ' . $e->getMessage());
    }
}

/**
 * Handle PUT request - Update payment status or details
 */
function handleUpdatePayment($input) {
    global $pdo;
    
    // Validate payment ID
    if (empty($input['payment_id'])) {
        throw new Exception('Payment ID is required');
    }
    
    $paymentId = $input['payment_id'];
    
    try {
        if (isset($pdo)) {
            // Check if payment exists
            $checkQuery = "SELECT id FROM payments WHERE payment_id = ?";
            $checkStmt = $pdo->prepare($checkQuery);
            $checkStmt->execute([$paymentId]);
            
            if (!$checkStmt->fetch()) {
                throw new Exception('Payment not found');
            }
            
            // Build update query dynamically
            $updateFields = [];
            $params = [];
            
            $allowedFields = ['status', 'payment_method', 'details', 'customer_name'];
            
            foreach ($allowedFields as $field) {
                if (isset($input[$field]) && $input[$field] !== '') {
                    $updateFields[] = "{$field} = ?";
                    $params[] = trim($input[$field]);
                }
            }
            
            // Handle amount update (with validation)
            if (isset($input['amount'])) {
                $amount = floatval($input['amount']);
                if ($amount > 0) {
                    $updateFields[] = "amount = ?";
                    $params[] = $amount;
                }
            }
            
            if (empty($updateFields)) {
                throw new Exception('No fields to update');
            }
            
            // Add updated_at timestamp
            $updateFields[] = "updated_at = ?";
            $params[] = date('Y-m-d H:i:s');
            
            // Add payment ID for WHERE clause
            $params[] = $paymentId;
            
            $query = "UPDATE payments SET " . implode(', ', $updateFields) . " WHERE payment_id = ?";
            $stmt = $pdo->prepare($query);
            $stmt->execute($params);
            
            // Return updated payment data
            $paymentQuery = "SELECT * FROM payments WHERE payment_id = ?";
            $paymentStmt = $pdo->prepare($paymentQuery);
            $paymentStmt->execute([$paymentId]);
            $updatedPayment = $paymentStmt->fetch(PDO::FETCH_ASSOC);
            
        } else {
            // Mock response when no database
            $updatedPayment = [
                'payment_id' => $paymentId,
                'status' => $input['status'] ?? 'Updated',
                'amount' => $input['amount'] ?? 100.00,
                'customer_name' => $input['customer_name'] ?? 'Updated Customer',
                'payment_method' => $input['payment_method'] ?? 'Credit Card',
                'details' => $input['details'] ?? 'Updated payment',
                'updated_at' => date('Y-m-d H:i:s')
            ];
        }
        
        return [
            'success' => true,
            'message' => 'Payment updated successfully',
            'data' => $updatedPayment
        ];
        
    } catch (PDOException $e) {
        throw new Exception('Database error: ' . $e->getMessage());
    }
}

/**
 * Handle DELETE request - Void/cancel payment
 */
function handleVoidPayment() {
    global $pdo;
    
    // Get payment ID from URL parameter
    $paymentId = isset($_GET['payment_id']) ? $_GET['payment_id'] : null;
    
    if (!$paymentId) {
        throw new Exception('Payment ID is required');
    }
    
    try {
        if (isset($pdo)) {
            // Check if payment exists
            $checkQuery = "SELECT payment_id, status FROM payments WHERE payment_id = ?";
            $checkStmt = $pdo->prepare($checkQuery);
            $checkStmt->execute([$paymentId]);
            $payment = $checkStmt->fetch(PDO::FETCH_ASSOC);
            
            if (!$payment) {
                throw new Exception('Payment not found');
            }
            
            // Check if payment can be voided
            if (in_array($payment['status'], ['Completed', 'Refunded'])) {
                throw new Exception('Cannot void a completed or refunded payment');
            }
            
            // Update payment status to 'Voided' instead of hard delete
            $updateQuery = "UPDATE payments SET status = 'Voided', updated_at = ? WHERE payment_id = ?";
            $updateStmt = $pdo->prepare($updateQuery);
            $updateStmt->execute([date('Y-m-d H:i:s'), $paymentId]);
            
        } else {
            // Mock response when no database
            $payment = ['payment_id' => $paymentId];
        }
        
        return [
            'success' => true,
            'message' => "Payment {$paymentId} voided successfully",
            'data' => ['voided_payment_id' => $paymentId]
        ];
        
    } catch (PDOException $e) {
        throw new Exception('Database error: ' . $e->getMessage());
    }
}

/**
 * Get mock payments data for testing when no database connection
 */
function getMockPayments() {
    return [
        [
            'id' => 1,
            'payment_id' => 'PAY-1234567',
            'customer_name' => 'Sarah Johnson',
            'amount' => 199.99,
            'payment_date' => '2025-09-08 14:30:00',
            'status' => 'Completed',
            'payment_method' => 'Credit Card',
            'details' => 'Premium Headphones - Order #ORD-8452',
            'created_at' => '2025-09-08 14:30:00'
        ],
        [
            'id' => 2,
            'payment_id' => 'PAY-1234568',
            'customer_name' => 'Michael Chen',
            'amount' => 249.99,
            'payment_date' => '2025-09-07 16:45:00',
            'status' => 'Completed',
            'payment_method' => 'PayPal',
            'details' => 'Ergonomic Chair - Order #ORD-8451',
            'created_at' => '2025-09-07 16:45:00'
        ],
        [
            'id' => 3,
            'payment_id' => 'PAY-1234569',
            'customer_name' => 'John Smith',
            'amount' => 599.99,
            'payment_date' => '2025-09-06 10:15:00',
            'status' => 'Processing',
            'payment_method' => 'Bank Transfer',
            'details' => 'Smartphone - Order #ORD-8450',
            'created_at' => '2025-09-06 10:15:00'
        ],
        [
            'id' => 4,
            'payment_id' => 'PAY-1234570',
            'customer_name' => 'Lisa Wong',
            'amount' => 149.99,
            'payment_date' => '2025-09-05 11:20:00',
            'status' => 'Completed',
            'payment_method' => 'Credit Card',
            'details' => 'Wireless Earbuds - Order #ORD-8449',
            'created_at' => '2025-09-05 11:20:00'
        ],
        [
            'id' => 5,
            'payment_id' => 'PAY-1234571',
            'customer_name' => 'Alex Rodriguez',
            'amount' => 1299.99,
            'payment_date' => '2025-09-04 09:30:00',
            'status' => 'Failed',
            'payment_method' => 'Credit Card',
            'details' => 'Laptop - Order #ORD-8448',
            'created_at' => '2025-09-04 09:30:00'
        ],
        [
            'id' => 6,
            'payment_id' => 'PAY-1234572',
            'customer_name' => 'Emily Wilson',
            'amount' => 49.99,
            'payment_date' => '2025-09-03 15:45:00',
            'status' => 'Refunded',
            'payment_method' => 'PayPal',
            'details' => 'Phone Case - Order #ORD-8447',
            'created_at' => '2025-09-03 15:45:00'
        ],
        [
            'id' => 7,
            'payment_id' => 'PAY-1234573',
            'customer_name' => 'David Brown',
            'amount' => 89.99,
            'payment_date' => '2025-09-02 12:10:00',
            'status' => 'Completed',
            'payment_method' => 'Credit Card',
            'details' => 'USB Cable - Order #ORD-8446',
            'created_at' => '2025-09-02 12:10:00'
        ],
        [
            'id' => 8,
            'payment_id' => 'PAY-1234574',
            'customer_name' => 'Jessica Davis',
            'amount' => 329.99,
            'payment_date' => '2025-09-01 08:25:00',
            'status' => 'Pending',
            'payment_method' => 'Bank Transfer',
            'details' => 'Tablet - Order #ORD-8445',
            'created_at' => '2025-09-01 08:25:00'
        ]
    ];
}
?>
