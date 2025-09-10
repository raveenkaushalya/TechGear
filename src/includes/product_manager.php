<?php
/**
 * Product Manager Class
 * Handles all product-related operations
 * With database connection error handling and static data fallback
 */

require_once(__DIR__ . '/db_connection.php');

class ProductManager {
    // Store database connection status
    private $db_connected = false;
    private $db_error_message = "";
    
    /**
     * Constructor
     * Check database connection status
     */
    public function __construct() {
        global $db_connected;
        $this->db_connected = $db_connected;
        
        if (!$this->db_connected) {
            $this->db_error_message = "Database connection unavailable. Using static data instead.";
            error_log($this->db_error_message);
            $_SESSION['db_status'] = [
                'connected' => false,
                'message' => $this->db_error_message,
                'time' => date('Y-m-d H:i:s')
            ];
        } else {
            $_SESSION['db_status'] = [
                'connected' => true,
                'message' => 'Connected to database successfully',
                'time' => date('Y-m-d H:i:s')
            ];
        }
    }
    
    /**
     * Check if database is connected
     * @return bool Database connection status
     */
    public function isDatabaseConnected() {
        return $this->db_connected;
    }
    
    /**
     * Get database status message
     * @return string Error message or empty string if connected
     */
    public function getDatabaseStatus() {
        return $this->db_connected ? "Connected" : $this->db_error_message;
    }
    /**
     * Get all products
     * @param string|null $categoryId (Optional) Filter by category ID
     * @param bool|null $featured (Optional) Filter by featured status
     * @return array Array of products
     */
    public function getAllProducts($categoryId = null, $featured = null) {
        $query = "
            SELECT p.*, c.name as category_name, pi.image_path 
            FROM products p
            JOIN categories c ON p.category_id = c.id
            LEFT JOIN product_images pi ON p.id = pi.product_id AND pi.is_primary = 1
            WHERE 1=1
        ";
        
        $params = [];
        
        if ($categoryId !== null) {
            $query .= " AND p.category_id = ?";
            $params[] = $categoryId;
        }
        
        if ($featured !== null) {
            $query .= " AND p.featured = ?";
            $params[] = $featured ? 1 : 0;
        }
        
        $query .= " ORDER BY p.name ASC";
        
        return fetchAll($query, $params);
    }
    
    /**
     * Get product by ID
     * @param string $id Product ID
     * @return array|null Product data or null if not found
     */
    public function getProductById($id) {
        $query = "
            SELECT p.*, c.name as category_name, pi.image_path 
            FROM products p
            JOIN categories c ON p.category_id = c.id
            LEFT JOIN product_images pi ON p.id = pi.product_id AND pi.is_primary = 1
            WHERE p.id = ?
        ";
        
        return fetchOne($query, [$id]);
    }
    
    /**
     * Create a new product
     * @param array $productData Product data
     * @return int|bool ID of the new product or false on failure
     */
    public function createProduct($productData) {
        // Check if database is connected
        if (!$this->db_connected) {
            $_SESSION['admin_message'] = [
                'type' => 'error',
                'text' => 'Cannot create product: Database is not available. Please check your database connection.'
            ];
            return false;
        }
        
        try {
            // Extract image path if provided
            $imagePath = isset($productData['image_path']) ? $productData['image_path'] : null;
            unset($productData['image_path']);
            
            // Insert the product
            $productId = insert('products', $productData);
            
            // If product was created successfully and image path was provided, add the image
            if ($productId && $imagePath) {
                insert('product_images', [
                    'product_id' => $productData['id'],
                    'image_path' => $imagePath,
                    'is_primary' => 1
                ]);
            }
            
            return $productId;
        } catch (Exception $e) {
            error_log("Error creating product: " . $e->getMessage());
            $_SESSION['admin_message'] = [
                'type' => 'error',
                'text' => 'Error creating product: ' . $e->getMessage()
            ];
            return false;
        }
    }
    
    /**
     * Update a product
     * @param string $id Product ID
     * @param array $productData Updated product data
     * @return bool Success or failure
     */
    public function updateProduct($id, $productData) {
        // Check if database is connected
        if (!$this->db_connected) {
            $_SESSION['admin_message'] = [
                'type' => 'error',
                'text' => 'Cannot update product: Database is not available. Please check your database connection.'
            ];
            return false;
        }
        
        try {
            // Extract image path if provided
            $imagePath = isset($productData['image_path']) ? $productData['image_path'] : null;
            unset($productData['image_path']);
            
            // Update the product
            $result = update('products', $productData, 'id', $id);
            
            // If image path was provided, update the image
            if ($imagePath) {
                // Check if image already exists
                $existingImage = fetchOne("SELECT * FROM product_images WHERE product_id = ? AND is_primary = 1", [$id]);
                
                if ($existingImage) {
                    // Update existing image
                    update('product_images', ['image_path' => $imagePath], 'id', $existingImage['id']);
                } else {
                    // Insert new image
                    insert('product_images', [
                        'product_id' => $id,
                        'image_path' => $imagePath,
                        'is_primary' => 1
                    ]);
                }
            }
            
            return $result;
        } catch (Exception $e) {
            error_log("Error updating product: " . $e->getMessage());
            $_SESSION['admin_message'] = [
                'type' => 'error',
                'text' => 'Error updating product: ' . $e->getMessage()
            ];
            return false;
        }
    }
    
    /**
     * Delete a product
     * @param string $id Product ID
     * @return bool Success or failure
     */
    public function deleteProduct($id) {
        // Check if database is connected
        if (!$this->db_connected) {
            $_SESSION['admin_message'] = [
                'type' => 'error',
                'text' => 'Cannot delete product: Database is not available. Please check your database connection.'
            ];
            return false;
        }
        
        try {
            return delete('products', 'id', $id);
        } catch (Exception $e) {
            error_log("Error deleting product: " . $e->getMessage());
            $_SESSION['admin_message'] = [
                'type' => 'error',
                'text' => 'Error deleting product: ' . $e->getMessage()
            ];
            return false;
        }
    }
    
    /**
     * Get all categories
     * @return array Array of categories
     */
    public function getAllCategories() {
        $query = "SELECT * FROM categories ORDER BY name ASC";
        return fetchAll($query);
    }
    
    /**
     * Get category by ID
     * @param string $id Category ID
     * @return array|null Category data or null if not found
     */
    public function getCategoryById($id) {
        $query = "SELECT * FROM categories WHERE id = ?";
        return fetchOne($query, [$id]);
    }
}

// Create an instance of the ProductManager
$productManager = new ProductManager();
?>
