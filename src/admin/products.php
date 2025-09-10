<?php
/**
 * Products Page
 * 
 * Admin interface for managing products
 * Connected to database for real-time updates with fallback
 */

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Include the main layout component
require_once('components/main-layout.php');

// Include any other required components
require_once('components/page-header.php');
require_once('components/ui/card.php');
require_once('components/ui/table.php');
require_once('components/ui/badge.php');
require_once('components/ui/dropdown-menu.php');

// Include product manager
require_once('../includes/product_manager.php');

// Check database connection status
$dbConnected = $productManager->isDatabaseConnected();
$dbStatus = $productManager->getDatabaseStatus();

// Process form submissions
$message = '';
$messageType = '';

// Check for existing admin messages
if (isset($_SESSION['admin_message'])) {
    $message = $_SESSION['admin_message']['text'];
    $messageType = $_SESSION['admin_message']['type'];
    unset($_SESSION['admin_message']);
}

// Add database connection status message if not connected
if (!$dbConnected) {
    $message = "Database connection is not available. Limited functionality mode active.";
    $messageType = "warning";
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        switch ($_POST['action']) {
            case 'add':
                // Prepare product data
                $productData = [
                    'id' => $_POST['id'],
                    'name' => $_POST['name'],
                    'description' => $_POST['description'],
                    'price' => floatval($_POST['price']),
                    'quantity' => intval($_POST['quantity']),
                    'category_id' => $_POST['category_id'],
                    'status' => $_POST['status'],
                    'featured' => isset($_POST['featured']) ? 1 : 0,
                    'limited_edition' => isset($_POST['limited_edition']) ? 1 : 0,
                    'image_path' => $_POST['image_path']
                ];
                
                // Create product
                if ($productManager->createProduct($productData)) {
                    $message = "Product added successfully!";
                    $messageType = "success";
                } else {
                    $message = "Error adding product.";
                    $messageType = "error";
                }
                break;
                
            case 'update':
                // Prepare product data
                $productId = $_POST['id'];
                $productData = [
                    'name' => $_POST['name'],
                    'description' => $_POST['description'],
                    'price' => floatval($_POST['price']),
                    'quantity' => intval($_POST['quantity']),
                    'category_id' => $_POST['category_id'],
                    'status' => $_POST['status'],
                    'featured' => isset($_POST['featured']) ? 1 : 0,
                    'limited_edition' => isset($_POST['limited_edition']) ? 1 : 0
                ];
                
                // Add image path if provided
                if (!empty($_POST['image_path'])) {
                    $productData['image_path'] = $_POST['image_path'];
                }
                
                // Update product
                if ($productManager->updateProduct($productId, $productData)) {
                    $message = "Product updated successfully!";
                    $messageType = "success";
                } else {
                    $message = "Error updating product.";
                    $messageType = "error";
                }
                break;
                
            case 'delete':
                $productId = $_POST['id'];
                
                // Delete product
                if ($productManager->deleteProduct($productId)) {
                    $message = "Product deleted successfully!";
                    $messageType = "success";
                } else {
                    $message = "Error deleting product.";
                    $messageType = "error";
                }
                break;
        }
    }
}

// Get all products from database
$products = $productManager->getAllProducts();

// Get all categories from database
$categories = $productManager->getAllCategories();

// Function to get category name from ID (for backward compatibility)
function getCategoryName($categoryId, $categories) {
    foreach ($categories as $category) {
        if ($category['id'] === $categoryId) {
            return $category['name'];
        }
    }
    return 'Uncategorized';
}

// Function to render category options for select dropdown
function renderCategoryOptions($categories) {
    $options = '';
    foreach ($categories as $category) {
        $id = htmlspecialchars($category['id']);
        $name = htmlspecialchars($category['name']);
        $options .= "<option value=\"$id\">$name</option>";
    }
    return $options;
}

// Generate the products content
$pageContent = '
<div class="flex flex-col gap-8">
    ' . renderPageHeader('Product Management') . '
    
    ' . (!$dbConnected ? '
    <div class="alert alert-warning">
        <div class="flex items-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
            </svg>
            <div>
                <h3 class="font-bold">Database Connection Unavailable</h3>
                <div class="text-sm">Using static data mode. Product creation, editing and deletion are disabled.</div>
            </div>
        </div>
    </div>
    ' : '') . '
    
    <div class="card">
        <div class="card-header">
            <div class="flex items-center justify-between">
                <div>
                    <div class="card-title">Products</div>
                    <div class="card-description">
                        Manage your products and view their details.
                    </div>
                </div>
                <button class="button' . (!$dbConnected ? ' disabled" disabled="disabled"' : '"') . ' onclick="openProductDialog()">
                    <svg class="mr-2 h-4 w-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="10"></circle>
                        <line x1="12" y1="8" x2="12" y2="16"></line>
                        <line x1="8" y1="12" x2="16" y2="12"></line>
                    </svg>
                    Add Product
                </button>
            </div>
        </div>
        <div class="card-content">
            <div class="table-container">
                <table class="table">
                    <thead class="table-header">
                        <tr class="table-row">
                            <th class="table-head hidden w-[100px] sm:table-cell">
                                <span class="sr-only">Image</span>
                            </th>
                            <th class="table-head">Name</th>
                            <th class="table-head">Category</th>
                            <th class="table-head">Status</th>
                            <th class="table-head hidden md:table-cell">Price</th>
                            <th class="table-head hidden md:table-cell">Quantity</th>
                            <th class="table-head">
                                <span class="sr-only">Actions</span>
                            </th>
                        </tr>
                    </thead>
                    <tbody class="table-body">';

// Generate table rows for each product
foreach ($products as $product) {
    $statusVariant = $product['status'] === 'available' ? 'outline' : 'secondary';
    $badgeClass = 'badge badge-' . $statusVariant;
    
    // Store product data in data attributes for JavaScript access
    $dataAttributes = '';
    foreach ($product as $key => $value) {
        if (!is_array($value)) {
            $dataAttributes .= ' data-' . $key . '="' . htmlspecialchars($value, ENT_QUOTES) . '"';
        }
    }
    
    // Get image path
    $imagePath = !empty($product['image_path']) ? $product['image_path'] : '../assets/images/placeholder.jpg';
    
    $pageContent .= '
        <tr class="table-row" data-product-id="' . $product['id'] . '"' . $dataAttributes . '>
            <td class="table-cell hidden sm:table-cell">
                <img
                    alt="' . htmlspecialchars($product['name']) . '"
                    class="aspect-square rounded-md object-cover"
                    height="64"
                    src="' . htmlspecialchars($imagePath) . '"
                    width="64"
                    loading="lazy"
                />
            </td>
            <td class="table-cell font-medium">' . htmlspecialchars($product['name']) . '</td>
            <td class="table-cell">' . htmlspecialchars($product['category_name']) . '</td>
            <td class="table-cell">
                <span class="' . $badgeClass . '">' . htmlspecialchars($product['status']) . '</span>
            </td>
            <td class="table-cell hidden md:table-cell">
                $' . number_format($product['price'], 2) . '
            </td>
            <td class="table-cell hidden md:table-cell">
                ' . $product['quantity'] . '
            </td>
            <td class="table-cell">
                <div class="dropdown-menu">
                    <button class="dropdown-menu-trigger button size-icon variant-ghost" 
                            onclick="toggleDropdown(\'' . $product['id'] . '\')"
                            data-bs-toggle="tooltip"
                            data-bs-title="Actions">
                        <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="5" r="1"></circle>
                            <circle cx="12" cy="12" r="1"></circle>
                            <circle cx="12" cy="19" r="1"></circle>
                        </svg>
                        <span class="sr-only">Toggle menu</span>
                    </button>
                    <div class="dropdown-menu-content" id="dropdown-' . $product['id'] . '" style="display: none;">
                        <div class="dropdown-menu-label">Actions</div>
                        <button class="dropdown-menu-item' . (!$dbConnected ? ' disabled" disabled="disabled"' : '"') . ' onclick="editProduct(\'' . $product['id'] . '\')">Edit</button>
                        <form method="post" style="display:inline;" onsubmit="return ' . ($dbConnected ? 'confirm(\'Are you sure you want to delete this product?\')' : 'false') . '">
                            <input type="hidden" name="action" value="delete">
                            <input type="hidden" name="id" value="' . $product['id'] . '">
                            <button type="submit" class="dropdown-menu-item text-destructive' . (!$dbConnected ? ' disabled" disabled="disabled"' : '"') . '>Delete</button>
                        </form>
                    </div>
                </div>
            </td>
        </tr>';
}

$pageContent .= '
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
    <!-- Status Message Display -->
    <?php if (!empty($message)): ?>
    <div class="alert alert-<?php echo $messageType; ?> mb-4">
        <?php echo $message; ?>
    </div>
    <?php endif; ?>
    
    <!-- Product Dialog (modal) with full form -->
    <div id="product-dialog" class="modal" style="display: none;">
        <div class="modal-content">
            <h2 id="dialog-title" class="mb-4">Add Product</h2>
            <form id="product-form" method="post" action="">
                <input type="hidden" name="action" id="form-action" value="add">
                <input type="hidden" name="id" id="product-id">
                
                <div class="form-group">
                    <label for="product-name">Product Name</label>
                    <input type="text" id="product-name" name="name" class="form-control" required />
                </div>
                
                <div class="form-group">
                    <label for="product-description">Description</label>
                    <textarea id="product-description" name="description" class="form-control" rows="3"></textarea>
                </div>
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="product-price">Price ($)</label>
                            <input type="number" id="product-price" name="price" class="form-control" step="0.01" min="0" required />
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="product-quantity">Quantity</label>
                            <input type="number" id="product-quantity" name="quantity" class="form-control" min="0" required />
                        </div>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="product-category">Category</label>
                    <select id="product-category" name="category_id" class="form-control" required>
                        ' . renderCategoryOptions($categories) . '
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="product-status">Status</label>
                    <select id="product-status" name="status" class="form-control" required>
                        <option value="available">Available</option>
                        <option value="out_of_stock">Out of Stock</option>
                        <option value="discontinued">Discontinued</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <div class="checkbox-group">
                        <input type="checkbox" id="product-featured" name="featured" class="form-check-input" />
                        <label for="product-featured">Featured Product</label>
                    </div>
                </div>
                
                <div class="form-group">
                    <div class="checkbox-group">
                        <input type="checkbox" id="product-limited" name="limited_edition" class="form-check-input" />
                        <label for="product-limited">Limited Edition</label>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="product-image">Image Path</label>
                    <input type="text" id="product-image" name="image_path" class="form-control" />
                    <small class="form-text text-muted">Relative path from web root (e.g., ../assets/images/k1.jpg)</small>
                </div>
                
                <div class="form-actions">
                    <button type="button" onclick="closeProductDialog()" class="button variant-outline">Cancel</button>
                    <button type="submit" class="button">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Simple JavaScript to make interactive elements work -->
<script>
    // Database connection status from server
    const dbConnected = ' . ($dbConnected ? 'true' : 'false') . ';
    
    function toggleDropdown(productId) {
        const dropdown = document.getElementById(`dropdown-${productId}`);
        if (dropdown) {
            dropdown.style.display = dropdown.style.display === "none" ? "block" : "none";
        }
    }
    
    function openProductDialog() {
        if (!dbConnected) {
            alert("Database is not available. You cannot add products in offline mode.");
            return;
        }
        
        // Reset form
        document.getElementById("product-form").reset();
        document.getElementById("form-action").value = "add";
        document.getElementById("product-id").value = generateProductId();
        document.getElementById("dialog-title").textContent = "Add Product";
        document.getElementById("product-dialog").style.display = "block";
    }
    
    function closeProductDialog() {
        document.getElementById("product-dialog").style.display = "none";
    }
    
    function editProduct(productId) {
        if (!dbConnected) {
            alert("Database is not available. You cannot edit products in offline mode.");
            return;
        }
        
        const row = document.querySelector(`tr[data-product-id="\${productId}"]`);
        if (!row) return;
        
        document.getElementById("dialog-title").textContent = "Edit Product";
        document.getElementById("form-action").value = "update";
        
        // Populate form with product data from data attributes
        document.getElementById("product-id").value = productId;
        document.getElementById("product-name").value = row.dataset.name || \'\';
        document.getElementById("product-description").value = row.dataset.description || \'\';
        document.getElementById("product-price").value = row.dataset.price || \'\';
        document.getElementById("product-quantity").value = row.dataset.quantity || \'\';
        document.getElementById("product-category").value = row.dataset.category_id || \'\';
        document.getElementById("product-status").value = row.dataset.status || \'\';
        document.getElementById("product-featured").checked = row.dataset.featured === \'1\';
        document.getElementById("product-limited").checked = row.dataset.limited_edition === \'1\';
        document.getElementById("product-image").value = row.dataset.image_path || \'\';
        
        document.getElementById("product-dialog").style.display = "block";
    }
    
    // Generate a simple product ID for new products
    function generateProductId() {
        const prefix = document.getElementById("product-category").value.substring(0, 1);
        const timestamp = Date.now().toString().substring(8);
        return prefix + '-' + timestamp;
    }
    
    // Close dropdowns when clicking outside
    document.addEventListener("click", function(event) {
        if (!event.target.closest(".dropdown-menu")) {
            const dropdowns = document.querySelectorAll(".dropdown-menu-content");
            dropdowns.forEach(dropdown => {
                dropdown.style.display = "none";
            });
        }
    });
    
    // Handle form category change to update product ID
    document.getElementById("product-category").addEventListener("change", function() {
        if (document.getElementById("form-action").value === "add") {
            document.getElementById("product-id").value = generateProductId();
        }
    });
</script>';

// Render the layout with our content
$fullPage = renderMainLayout($pageContent);

// Set page title
$pageTitle = 'IndigoFlow - Products';

// Page-specific CSS and JS
$pageSpecificJS = ['assets/js/pages/products.js'];

// Add page-specific JavaScript
$inlineJS = '
// Make products data available to our JavaScript
window.products = ' . json_encode($products) . ';
window.categories = ' . json_encode($categories) . ';
';

// Include header
include_once('includes/header.php');

// Output the main content
echo $fullPage;

// Include footer
include_once('includes/footer.php');
?>
