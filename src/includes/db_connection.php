<?php
/**
 * Database Connection File
 * Establishes connection to MySQL database
 */

// Start session if not already started to store database connection status
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Database configuration
define('DB_HOST', '127.0.0.1'); // Using IP instead of hostname
define('DB_PORT', 3306); // Default MySQL port
define('DB_USERNAME', 'root');
define('DB_PASSWORD', '');
define('DB_NAME', 'techgear');

// Flag to enable fallback to static data if database connection fails
define('ENABLE_DB_FALLBACK', true);

// Attempt database connection
$conn = null;
$db_connected = false;

try {
    // Use explicit port in connection
    $conn = mysqli_connect(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME, DB_PORT);
    
    // Check if connection was successful
    if ($conn) {
        $db_connected = true;
    }
} catch (Exception $e) {
    // Log error message (in a production environment, you'd use a proper logging mechanism)
    error_log("Database connection failed: " . $e->getMessage());
}

/**
 * Execute a query and return the result
 * 
 * @param string $query The SQL query to execute
 * @param array $params (Optional) Parameters to bind to the query
 * @return mysqli_result|bool Result of the query
 */
function executeQuery($query, $params = []) {
    global $conn, $db_connected;
    
    // If database is not connected, return false
    if (!$db_connected || !$conn) {
        return false;
    }
    
    try {
        $stmt = mysqli_prepare($conn, $query);
        
        if (!$stmt) {
            error_log("Query preparation failed: " . mysqli_error($conn));
            return false;
        }
        
        if (!empty($params)) {
            $types = '';
            $bindParams = [];
            
            // Determine parameter types
            foreach ($params as $param) {
                if (is_int($param)) {
                    $types .= 'i'; // integer
                } elseif (is_float($param)) {
                    $types .= 'd'; // double
                } elseif (is_string($param)) {
                    $types .= 's'; // string
                } else {
                    $types .= 'b'; // blob
                }
            }
            
            // Create an array with references
            $bindParams[] = &$types;
            foreach ($params as &$param) {
                $bindParams[] = &$param;
            }
            
            // Bind parameters
            call_user_func_array([$stmt, 'bind_param'], $bindParams);
        }
        
        // Execute the statement
        if (mysqli_stmt_execute($stmt)) {
            $result = mysqli_stmt_get_result($stmt);
            mysqli_stmt_close($stmt);
            return $result;
        } else {
            $error = mysqli_stmt_error($stmt);
            mysqli_stmt_close($stmt);
            error_log("Query execution failed: " . $error);
            return false;
        }
    } catch (Exception $e) {
        error_log("Database error: " . $e->getMessage());
        return false;
    }
}

/**
 * Get static product data for fallback when database is not available
 * 
 * @return array Array of product data
 */
function getStaticProductData() {
    return [
        [
            'id' => 'k1',
            'name' => 'RGB Mechanical Keyboard',
            'description' => 'Customizable RGB Mechanical Gaming Keyboard with Blue Switches',
            'price' => 89.99,
            'quantity' => 50,
            'category_id' => 'keyboards',
            'category_name' => 'Keyboards',
            'status' => 'available',
            'featured' => 1,
            'limited_edition' => 0,
            'image_path' => '../assets/images/k1.jpg'
        ],
        [
            'id' => 'k2',
            'name' => 'Wireless Mechanical Keyboard',
            'description' => 'Low-latency Wireless Mechanical Keyboard with Brown Switches',
            'price' => 129.99,
            'quantity' => 30,
            'category_id' => 'keyboards',
            'category_name' => 'Keyboards',
            'status' => 'available',
            'featured' => 0,
            'limited_edition' => 0,
            'image_path' => '../assets/images/k2.jpg'
        ],
        [
            'id' => 'm1',
            'name' => 'Cyberpunk Edition Mouse',
            'description' => 'Exclusive Cyberpunk RGB Wireless Gaming Mouse – Only while stocks last!',
            'price' => 129.99,
            'quantity' => 10,
            'category_id' => 'mice',
            'category_name' => 'Mice',
            'status' => 'available',
            'featured' => 1,
            'limited_edition' => 1,
            'image_path' => '../assets/images/m1-cyberpunk.jpg'
        ],
        [
            'id' => 'mn1',
            'name' => '144Hz Gaming Monitor',
            'description' => '27-inch 1440p 144Hz IPS Gaming Monitor with 1ms Response Time',
            'price' => 299.99,
            'quantity' => 15,
            'category_id' => 'monitors',
            'category_name' => 'Monitors',
            'status' => 'available',
            'featured' => 1,
            'limited_edition' => 0,
            'image_path' => '../assets/images/mn1.jpg'
        ],
        [
            'id' => 'h1',
            'name' => 'Wireless Gaming Headset',
            'description' => 'Low-latency Wireless Gaming Headset with 7.1 Surround Sound',
            'price' => 129.99,
            'quantity' => 25,
            'category_id' => 'headphones',
            'category_name' => 'Headphones',
            'status' => 'available',
            'featured' => 1,
            'limited_edition' => 0,
            'image_path' => '../assets/images/h1.jpg'
        ]
    ];
}

/**
 * Get static category data for fallback when database is not available
 * 
 * @return array Array of category data
 */
function getStaticCategoryData() {
    return [
        [
            'id' => 'keyboards',
            'name' => 'Keyboards',
            'description' => 'Discover our selection of premium mechanical keyboards, perfect for gaming and productivity.'
        ],
        [
            'id' => 'mice',
            'name' => 'Mice',
            'description' => 'Find the perfect gaming and productivity mice with precision sensors and ergonomic designs.'
        ],
        [
            'id' => 'monitors',
            'name' => 'Monitors',
            'description' => 'Upgrade your visual experience with our high-performance gaming and professional monitors.'
        ],
        [
            'id' => 'headphones',
            'name' => 'Headphones',
            'description' => 'Experience superior audio with our range of gaming headsets and professional headphones.'
        ]
    ];
}

/**
 * Fetch all rows from a result set as an associative array
 * 
 * @param string $query The SQL query to execute
 * @param array $params (Optional) Parameters to bind to the query
 * @return array Array of associative arrays
 */
function fetchAll($query, $params = []) {
    global $db_connected, $conn;
    
    // If using static data fallback and database is not connected
    if (ENABLE_DB_FALLBACK && !$db_connected) {
        // Check query to determine what kind of data to return
        if (strpos($query, 'categories') !== false) {
            return getStaticCategoryData();
        } elseif (strpos($query, 'products') !== false) {
            $products = getStaticProductData();
            
            // Check if we need to filter by category
            if (!empty($params) && strpos($query, 'category_id = ?') !== false) {
                $categoryId = $params[0];
                return array_filter($products, function($product) use ($categoryId) {
                    return $product['category_id'] === $categoryId;
                });
            }
            
            // Check if we need to filter by featured status
            if (!empty($params) && strpos($query, 'featured = ?') !== false) {
                $featured = $params[0];
                return array_filter($products, function($product) use ($featured) {
                    return $product['featured'] == $featured;
                });
            }
            
            return $products;
        }
        return [];
    }
    
    // Normal database operation
    $result = executeQuery($query, $params);
    $rows = [];
    
    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $rows[] = $row;
        }
        mysqli_free_result($result);
    }
    
    return $rows;
}

/**
 * Fetch a single row from a result set as an associative array
 * 
 * @param string $query The SQL query to execute
 * @param array $params (Optional) Parameters to bind to the query
 * @return array|null Associative array or null if no result
 */
function fetchOne($query, $params = []) {
    global $db_connected;
    
    // If using static data fallback and database is not connected
    if (ENABLE_DB_FALLBACK && !$db_connected) {
        // Check if we're looking for a specific product by ID
        if (strpos($query, 'products') !== false && strpos($query, 'id = ?') !== false && !empty($params)) {
            $productId = $params[0];
            $products = getStaticProductData();
            
            foreach ($products as $product) {
                if ($product['id'] === $productId) {
                    return $product;
                }
            }
        }
        
        // Check if we're looking for a specific category by ID
        if (strpos($query, 'categories') !== false && strpos($query, 'id = ?') !== false && !empty($params)) {
            $categoryId = $params[0];
            $categories = getStaticCategoryData();
            
            foreach ($categories as $category) {
                if ($category['id'] === $categoryId) {
                    return $category;
                }
            }
        }
        
        return null;
    }
    
    // Normal database operation
    $result = executeQuery($query, $params);
    
    if ($result) {
        $row = mysqli_fetch_assoc($result);
        mysqli_free_result($result);
        return $row;
    }
    
    return null;
}

/**
 * Insert data into a table
 * 
 * @param string $table Table name
 * @param array $data Associative array of column => value
 * @return int|bool The ID of the inserted row or false on failure
 */
function insert($table, $data) {
    global $conn, $db_connected;
    
    // If database is not connected, return false with an informative error
    if (!$db_connected || !$conn) {
        error_log("Database insert failed: Database connection not available");
        $_SESSION['db_error'] = "Database operation failed: Unable to connect to database";
        return false;
    }
    
    try {
        $columns = implode(', ', array_keys($data));
        $placeholders = implode(', ', array_fill(0, count($data), '?'));
        
        $query = "INSERT INTO $table ($columns) VALUES ($placeholders)";
        $params = array_values($data);
        
        $stmt = mysqli_prepare($conn, $query);
        
        if (!$stmt) {
            error_log("Insert preparation failed: " . mysqli_error($conn));
            $_SESSION['db_error'] = "Database operation failed: " . mysqli_error($conn);
            return false;
        }
        
        if (!empty($params)) {
            $types = '';
            $bindParams = [];
            
            // Determine parameter types
            foreach ($params as $param) {
                if (is_int($param)) {
                    $types .= 'i';
                } elseif (is_float($param)) {
                    $types .= 'd';
                } elseif (is_string($param)) {
                    $types .= 's';
                } else {
                    $types .= 'b';
                }
            }
            
            // Create an array with references
            $bindParams[] = &$types;
            foreach ($params as &$param) {
                $bindParams[] = &$param;
            }
            
            // Bind parameters
            call_user_func_array([$stmt, 'bind_param'], $bindParams);
        }
        
        if (mysqli_stmt_execute($stmt)) {
            $insertId = mysqli_insert_id($conn);
            mysqli_stmt_close($stmt);
            return $insertId;
        }
        
        $error = mysqli_stmt_error($stmt);
        mysqli_stmt_close($stmt);
        error_log("Insert execution failed: " . $error);
        $_SESSION['db_error'] = "Database operation failed: " . $error;
        return false;
    } catch (Exception $e) {
        error_log("Insert error: " . $e->getMessage());
        $_SESSION['db_error'] = "Database operation failed: " . $e->getMessage();
        return false;
    }
}

/**
 * Update data in a table
 * 
 * @param string $table Table name
 * @param array $data Associative array of column => value to update
 * @param string $whereColumn Column name for WHERE clause
 * @param mixed $whereValue Value for WHERE clause
 * @return bool Success or failure
 */
function update($table, $data, $whereColumn, $whereValue) {
    global $conn, $db_connected;
    
    // If database is not connected, return false with an informative error
    if (!$db_connected || !$conn) {
        error_log("Database update failed: Database connection not available");
        $_SESSION['db_error'] = "Database operation failed: Unable to connect to database";
        return false;
    }
    
    try {
        $setClauses = [];
        foreach (array_keys($data) as $column) {
            $setClauses[] = "$column = ?";
        }
        
        $setClause = implode(', ', $setClauses);
        $query = "UPDATE $table SET $setClause WHERE $whereColumn = ?";
        
        $params = array_values($data);
        $params[] = $whereValue;
        
        $stmt = mysqli_prepare($conn, $query);
        
        if (!$stmt) {
            error_log("Update preparation failed: " . mysqli_error($conn));
            $_SESSION['db_error'] = "Database operation failed: " . mysqli_error($conn);
            return false;
        }
        
        if (!empty($params)) {
            $types = '';
            $bindParams = [];
            
            // Determine parameter types
            foreach ($params as $param) {
                if (is_int($param)) {
                    $types .= 'i';
                } elseif (is_float($param)) {
                    $types .= 'd';
                } elseif (is_string($param)) {
                    $types .= 's';
                } else {
                    $types .= 'b';
                }
            }
            
            // Create an array with references
            $bindParams[] = &$types;
            foreach ($params as &$param) {
                $bindParams[] = &$param;
            }
            
            // Bind parameters
            call_user_func_array([$stmt, 'bind_param'], $bindParams);
        }
        
        if (mysqli_stmt_execute($stmt)) {
            $success = (mysqli_stmt_affected_rows($stmt) > 0);
            mysqli_stmt_close($stmt);
            return $success;
        }
        
        $error = mysqli_stmt_error($stmt);
        mysqli_stmt_close($stmt);
        error_log("Update execution failed: " . $error);
        $_SESSION['db_error'] = "Database operation failed: " . $error;
        return false;
    } catch (Exception $e) {
        error_log("Update error: " . $e->getMessage());
        $_SESSION['db_error'] = "Database operation failed: " . $e->getMessage();
        return false;
    }
}

/**
 * Delete data from a table
 * 
 * @param string $table Table name
 * @param string $whereColumn Column name for WHERE clause
 * @param mixed $whereValue Value for WHERE clause
 * @return bool Success or failure
 */
function delete($table, $whereColumn, $whereValue) {
    global $conn, $db_connected;
    
    // If database is not connected, return false with an informative error
    if (!$db_connected || !$conn) {
        error_log("Database delete failed: Database connection not available");
        $_SESSION['db_error'] = "Database operation failed: Unable to connect to database";
        return false;
    }
    
    try {
        $query = "DELETE FROM $table WHERE $whereColumn = ?";
        $params = [$whereValue];
        
        $stmt = mysqli_prepare($conn, $query);
        
        if (!$stmt) {
            error_log("Delete preparation failed: " . mysqli_error($conn));
            $_SESSION['db_error'] = "Database operation failed: " . mysqli_error($conn);
            return false;
        }
        
        $type = is_int($whereValue) ? 'i' : (is_float($whereValue) ? 'd' : 's');
        mysqli_stmt_bind_param($stmt, $type, $whereValue);
        
        if (mysqli_stmt_execute($stmt)) {
            $success = (mysqli_stmt_affected_rows($stmt) > 0);
            mysqli_stmt_close($stmt);
            return $success;
        }
        
        $error = mysqli_stmt_error($stmt);
        mysqli_stmt_close($stmt);
        error_log("Delete execution failed: " . $error);
        $_SESSION['db_error'] = "Database operation failed: " . $error;
        return false;
    } catch (Exception $e) {
        error_log("Delete error: " . $e->getMessage());
        $_SESSION['db_error'] = "Database operation failed: " . $e->getMessage();
        return false;
    }
}
?>
