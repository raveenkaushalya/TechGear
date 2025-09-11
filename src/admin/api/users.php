<?php
/**
 * Users API Endpoint
 * 
 * Handles CRUD operations for user management
 * This API provides endpoints for:
 * - GET: Retrieve users list with pagination and filtering
 * - POST: Create new user
 * - PUT: Update existing user
 * - DELETE: Delete user
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
            $response = handleGetUsers();
            break;
            
        case 'POST':
            $response = handleCreateUser($input);
            break;
            
        case 'PUT':
            $response = handleUpdateUser($input);
            break;
            
        case 'DELETE':
            $response = handleDeleteUser();
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
 * Handle GET request - Retrieve users
 */
function handleGetUsers() {
    global $pdo;
    
    // Get query parameters
    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 10;
    $search = isset($_GET['search']) ? $_GET['search'] : '';
    $status = isset($_GET['status']) ? $_GET['status'] : '';
    $role = isset($_GET['role']) ? $_GET['role'] : '';
    
    // Calculate offset
    $offset = ($page - 1) * $limit;
    
    // Build base query
    $whereConditions = [];
    $params = [];
    
    // Add search condition
    if (!empty($search)) {
        $whereConditions[] = "(name LIKE ? OR email LIKE ?)";
        $params[] = "%{$search}%";
        $params[] = "%{$search}%";
    }
    
    // Add status filter
    if (!empty($status)) {
        $whereConditions[] = "status = ?";
        $params[] = $status;
    }
    
    // Add role filter
    if (!empty($role)) {
        $whereConditions[] = "role = ?";
        $params[] = $role;
    }
    
    $whereClause = !empty($whereConditions) ? 'WHERE ' . implode(' AND ', $whereConditions) : '';
    
    try {
        // Check if database connection exists, otherwise use mock data
        if (isset($pdo)) {
            // Get total count
            $countQuery = "SELECT COUNT(*) as total FROM users {$whereClause}";
            $countStmt = $pdo->prepare($countQuery);
            $countStmt->execute($params);
            $totalUsers = $countStmt->fetch(PDO::FETCH_ASSOC)['total'];
            
            // Get users data
            $query = "SELECT id, name, email, role, status, avatar, created_at, last_login 
                     FROM users 
                     {$whereClause} 
                     ORDER BY created_at DESC 
                     LIMIT ? OFFSET ?";
            
            $params[] = $limit;
            $params[] = $offset;
            
            $stmt = $pdo->prepare($query);
            $stmt->execute($params);
            $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
        } else {
            // Fallback to mock data when no database connection
            $users = getMockUsers();
            $totalUsers = count($users);
            
            // Apply filters to mock data
            if (!empty($search)) {
                $users = array_filter($users, function($user) use ($search) {
                    return stripos($user['name'], $search) !== false || 
                           stripos($user['email'], $search) !== false;
                });
            }
            
            if (!empty($status)) {
                $users = array_filter($users, function($user) use ($status) {
                    return $user['status'] === $status;
                });
            }
            
            if (!empty($role)) {
                $users = array_filter($users, function($user) use ($role) {
                    return $user['role'] === $role;
                });
            }
            
            // Apply pagination
            $users = array_slice($users, $offset, $limit);
        }
        
        return [
            'success' => true,
            'message' => 'Users retrieved successfully',
            'data' => [
                'users' => $users,
                'pagination' => [
                    'current_page' => $page,
                    'per_page' => $limit,
                    'total' => $totalUsers,
                    'total_pages' => ceil($totalUsers / $limit)
                ]
            ]
        ];
        
    } catch (PDOException $e) {
        throw new Exception('Database error: ' . $e->getMessage());
    }
}

/**
 * Handle POST request - Create new user
 */
function handleCreateUser($input) {
    global $pdo;
    
    // Validate required fields
    $required = ['name', 'email', 'role', 'password'];
    foreach ($required as $field) {
        if (empty($input[$field])) {
            throw new Exception("Missing required field: {$field}");
        }
    }
    
    // Validate email format
    if (!filter_var($input['email'], FILTER_VALIDATE_EMAIL)) {
        throw new Exception('Invalid email format');
    }
    
    // Set default values
    $userData = [
        'name' => trim($input['name']),
        'email' => trim(strtolower($input['email'])),
        'role' => $input['role'],
        'status' => $input['status'] ?? 'Active',
        'avatar' => $input['avatar'] ?? null,
        'password' => password_hash($input['password'], PASSWORD_DEFAULT),
        'created_at' => date('Y-m-d H:i:s'),
        'last_login' => null
    ];
    
    try {
        if (isset($pdo)) {
            // Check if email already exists
            $checkQuery = "SELECT id FROM users WHERE email = ?";
            $checkStmt = $pdo->prepare($checkQuery);
            $checkStmt->execute([$userData['email']]);
            
            if ($checkStmt->fetch()) {
                throw new Exception('Email already exists');
            }
            
            // Insert new user
            $query = "INSERT INTO users (name, email, role, status, avatar, password, created_at) 
                     VALUES (?, ?, ?, ?, ?, ?, ?)";
            
            $stmt = $pdo->prepare($query);
            $stmt->execute([
                $userData['name'],
                $userData['email'],
                $userData['role'],
                $userData['status'],
                $userData['avatar'],
                $userData['password'],
                $userData['created_at']
            ]);
            
            $userId = $pdo->lastInsertId();
            
            // Return created user data
            $userQuery = "SELECT id, name, email, role, status, avatar, created_at FROM users WHERE id = ?";
            $userStmt = $pdo->prepare($userQuery);
            $userStmt->execute([$userId]);
            $newUser = $userStmt->fetch(PDO::FETCH_ASSOC);
            
        } else {
            // Mock response when no database
            $newUser = [
                'id' => rand(1000, 9999),
                'name' => $userData['name'],
                'email' => $userData['email'],
                'role' => $userData['role'],
                'status' => $userData['status'],
                'avatar' => $userData['avatar'],
                'created_at' => $userData['created_at']
            ];
        }
        
        return [
            'success' => true,
            'message' => 'User created successfully',
            'data' => $newUser
        ];
        
    } catch (PDOException $e) {
        throw new Exception('Database error: ' . $e->getMessage());
    }
}

/**
 * Handle PUT request - Update existing user
 */
function handleUpdateUser($input) {
    global $pdo;
    
    // Validate user ID
    if (empty($input['id'])) {
        throw new Exception('User ID is required');
    }
    
    $userId = (int)$input['id'];
    
    try {
        if (isset($pdo)) {
            // Check if user exists
            $checkQuery = "SELECT id FROM users WHERE id = ?";
            $checkStmt = $pdo->prepare($checkQuery);
            $checkStmt->execute([$userId]);
            
            if (!$checkStmt->fetch()) {
                throw new Exception('User not found');
            }
            
            // Build update query dynamically
            $updateFields = [];
            $params = [];
            
            $allowedFields = ['name', 'email', 'role', 'status', 'avatar'];
            
            foreach ($allowedFields as $field) {
                if (isset($input[$field]) && $input[$field] !== '') {
                    $updateFields[] = "{$field} = ?";
                    $params[] = $field === 'email' ? strtolower(trim($input[$field])) : trim($input[$field]);
                }
            }
            
            // Handle password update
            if (!empty($input['password'])) {
                $updateFields[] = "password = ?";
                $params[] = password_hash($input['password'], PASSWORD_DEFAULT);
            }
            
            if (empty($updateFields)) {
                throw new Exception('No fields to update');
            }
            
            // Add updated_at timestamp
            $updateFields[] = "updated_at = ?";
            $params[] = date('Y-m-d H:i:s');
            
            // Add user ID for WHERE clause
            $params[] = $userId;
            
            $query = "UPDATE users SET " . implode(', ', $updateFields) . " WHERE id = ?";
            $stmt = $pdo->prepare($query);
            $stmt->execute($params);
            
            // Return updated user data
            $userQuery = "SELECT id, name, email, role, status, avatar, created_at, updated_at FROM users WHERE id = ?";
            $userStmt = $pdo->prepare($userQuery);
            $userStmt->execute([$userId]);
            $updatedUser = $userStmt->fetch(PDO::FETCH_ASSOC);
            
        } else {
            // Mock response when no database
            $updatedUser = [
                'id' => $userId,
                'name' => $input['name'] ?? 'Updated User',
                'email' => $input['email'] ?? 'updated@example.com',
                'role' => $input['role'] ?? 'User',
                'status' => $input['status'] ?? 'Active',
                'avatar' => $input['avatar'] ?? null,
                'updated_at' => date('Y-m-d H:i:s')
            ];
        }
        
        return [
            'success' => true,
            'message' => 'User updated successfully',
            'data' => $updatedUser
        ];
        
    } catch (PDOException $e) {
        throw new Exception('Database error: ' . $e->getMessage());
    }
}

/**
 * Handle DELETE request - Delete user
 */
function handleDeleteUser() {
    global $pdo;
    
    // Get user ID from URL parameter
    $userId = isset($_GET['id']) ? (int)$_GET['id'] : null;
    
    if (!$userId) {
        throw new Exception('User ID is required');
    }
    
    try {
        if (isset($pdo)) {
            // Check if user exists
            $checkQuery = "SELECT id, name FROM users WHERE id = ?";
            $checkStmt = $pdo->prepare($checkQuery);
            $checkStmt->execute([$userId]);
            $user = $checkStmt->fetch(PDO::FETCH_ASSOC);
            
            if (!$user) {
                throw new Exception('User not found');
            }
            
            // Soft delete - update status to 'Deleted' instead of hard delete
            // This preserves data integrity and audit trail
            $updateQuery = "UPDATE users SET status = 'Deleted', updated_at = ? WHERE id = ?";
            $updateStmt = $pdo->prepare($updateQuery);
            $updateStmt->execute([date('Y-m-d H:i:s'), $userId]);
            
            // Alternatively, use hard delete if preferred:
            // $deleteQuery = "DELETE FROM users WHERE id = ?";
            // $deleteStmt = $pdo->prepare($deleteQuery);
            // $deleteStmt->execute([$userId]);
            
        } else {
            // Mock response when no database
            $user = ['id' => $userId, 'name' => 'Mock User'];
        }
        
        return [
            'success' => true,
            'message' => "User '{$user['name']}' deleted successfully",
            'data' => ['deleted_user_id' => $userId]
        ];
        
    } catch (PDOException $e) {
        throw new Exception('Database error: ' . $e->getMessage());
    }
}

/**
 * Get mock users data for testing when no database connection
 */
function getMockUsers() {
    return [
        [
            'id' => 1,
            'name' => 'John Smith',
            'email' => 'john.smith@example.com',
            'role' => 'Admin',
            'status' => 'Active',
            'avatar' => 'assets/images/users/user1.jpg',
            'created_at' => '2025-08-15 10:30:00',
            'last_login' => '2025-09-09 14:32:15'
        ],
        [
            'id' => 2,
            'name' => 'Sarah Johnson',
            'email' => 'sarah.j@example.com',
            'role' => 'Editor',
            'status' => 'Active',
            'avatar' => 'assets/images/users/user2.jpg',
            'created_at' => '2025-08-10 09:15:00',
            'last_login' => '2025-09-08 10:15:42'
        ],
        [
            'id' => 3,
            'name' => 'Michael Chen',
            'email' => 'michael.c@example.com',
            'role' => 'User',
            'status' => 'Active',
            'avatar' => 'assets/images/users/user3.jpg',
            'created_at' => '2025-08-05 16:20:00',
            'last_login' => '2025-09-07 16:45:21'
        ],
        [
            'id' => 4,
            'name' => 'Alex Rodriguez',
            'email' => 'alex.r@example.com',
            'role' => 'User',
            'status' => 'Inactive',
            'avatar' => 'assets/images/users/user4.jpg',
            'created_at' => '2025-07-28 11:45:00',
            'last_login' => '2025-08-25 09:10:33'
        ],
        [
            'id' => 5,
            'name' => 'Lisa Wong',
            'email' => 'lisa.w@example.com',
            'role' => 'Editor',
            'status' => 'Pending',
            'avatar' => 'assets/images/users/user5.jpg',
            'created_at' => '2025-08-01 14:30:00',
            'last_login' => '2025-09-01 11:23:45'
        ],
        [
            'id' => 6,
            'name' => 'David Wilson',
            'email' => 'david.w@example.com',
            'role' => 'User',
            'status' => 'Active',
            'avatar' => 'assets/images/users/user6.jpg',
            'created_at' => '2025-07-20 08:15:00',
            'last_login' => '2025-09-06 12:30:15'
        ],
        [
            'id' => 7,
            'name' => 'Emma Thompson',
            'email' => 'emma.t@example.com',
            'role' => 'Editor',
            'status' => 'Active',
            'avatar' => 'assets/images/users/user7.jpg',
            'created_at' => '2025-07-15 13:45:00',
            'last_login' => '2025-09-05 15:20:30'
        ],
        [
            'id' => 8,
            'name' => 'James Brown',
            'email' => 'james.b@example.com',
            'role' => 'User',
            'status' => 'Active',
            'avatar' => 'assets/images/users/user8.jpg',
            'created_at' => '2025-07-10 10:00:00',
            'last_login' => '2025-09-04 09:45:22'
        ]
    ];
}
?>
