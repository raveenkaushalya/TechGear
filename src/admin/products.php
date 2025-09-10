<?php
/**
 * Products Page
 * 
 * Converted from React/TypeScript to PHP
 * Preserving all styling and structure
 */

// Include the main layout component
require_once('components/main-layout.php');

// Include any other required components
require_once('components/page-header.php');
require_once('components/ui/card.php');
require_once('components/ui/table.php');
require_once('components/ui/badge.php');
require_once('components/ui/dropdown-menu.php');

// Sample data (in a real app, this would come from a database)
// Products data
$products = [
    [
        'id' => 'prod-1',
        'name' => 'Premium Headphones',
        'description' => 'High-quality wireless headphones with noise cancellation',
        'price' => 199.99,
        'quantity' => 45,
        'categoryId' => 'electronics',
        'status' => 'available',
        'image' => 'assets/images/products/headphones.jpg'
    ],
    [
        'id' => 'prod-2',
        'name' => 'Ergonomic Chair',
        'description' => 'Office chair designed for comfort during long working hours',
        'price' => 249.99,
        'quantity' => 20,
        'categoryId' => 'furniture',
        'status' => 'available',
        'image' => 'assets/images/products/chair.jpg'
    ],
    [
        'id' => 'prod-3',
        'name' => 'Smart Watch',
        'description' => 'Track your health metrics and stay connected',
        'price' => 299.99,
        'quantity' => 0,
        'categoryId' => 'electronics',
        'status' => 'out_of_stock',
        'image' => 'assets/images/products/smartwatch.jpg'
    ]
];

// Categories data
$categories = [
    ['id' => 'electronics', 'name' => 'Electronics'],
    ['id' => 'furniture', 'name' => 'Furniture'],
    ['id' => 'clothing', 'name' => 'Clothing']
];

// Function to get category name from ID
function getCategoryName($categoryId, $categories) {
    foreach ($categories as $category) {
        if ($category['id'] === $categoryId) {
            return $category['name'];
        }
    }
    return 'Uncategorized';
}

// Generate the products content
$pageContent = '
<div class="flex flex-col gap-8">
    ' . renderPageHeader('Product Management') . '
    
    <div class="card">
        <div class="card-header">
            <div class="flex items-center justify-between">
                <div>
                    <div class="card-title">Products</div>
                    <div class="card-description">
                        Manage your products and view their details.
                    </div>
                </div>
                <button class="button" onclick="openProductDialog()">
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
    
    $pageContent .= '
        <tr class="table-row" data-product-id="' . $product['id'] . '"' . $dataAttributes . '>
            <td class="table-cell hidden sm:table-cell">
                <img
                    alt="' . htmlspecialchars($product['name']) . '"
                    class="aspect-square rounded-md object-cover"
                    height="64"
                    src="' . htmlspecialchars($product['image']) . '"
                    width="64"
                    loading="lazy"
                />
            </td>
            <td class="table-cell font-medium">' . htmlspecialchars($product['name']) . '</td>
            <td class="table-cell">' . htmlspecialchars(getCategoryName($product['categoryId'], $categories)) . '</td>
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
                        <button class="dropdown-menu-item" onclick="editProduct(\'' . $product['id'] . '\')">Edit</button>
                        <button class="dropdown-menu-item text-destructive" onclick="deleteProduct(\'' . $product['id'] . '\')">Delete</button>
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
    
    <!-- Product Dialog (modal) with full form -->
    <div id="product-dialog" class="modal" style="display: none;">
        <div class="modal-content">
            <h2 id="dialog-title" class="mb-4">Add Product</h2>
            <form id="product-form">
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
                    <select id="product-category" name="categoryId" class="form-control" required>
                        <option value="electronics">Electronics</option>
                        <option value="furniture">Furniture</option>
                        <option value="clothing">Clothing</option>
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
                    <label for="product-image">Image URL</label>
                    <input type="text" id="product-image" name="image" class="form-control" />
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
    function toggleDropdown(productId) {
        const dropdown = document.getElementById(`dropdown-${productId}`);
        if (dropdown) {
            dropdown.style.display = dropdown.style.display === "none" ? "block" : "none";
        }
    }
    
    function openProductDialog() {
        document.getElementById("product-dialog").style.display = "block";
        document.getElementById("dialog-title").textContent = "Add Product";
    }
    
    function closeProductDialog() {
        document.getElementById("product-dialog").style.display = "none";
    }
    
    function editProduct(productId) {
        document.getElementById("product-dialog").style.display = "block";
        document.getElementById("dialog-title").textContent = "Edit Product";
        // In a real app, you would fetch the product data and populate the form
    }
    
    function deleteProduct(productId) {
        if (confirm("Are you sure you want to delete this product?")) {
            // In a real app, you would submit a form to delete the product
            console.log("Deleting product:", productId);
        }
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
