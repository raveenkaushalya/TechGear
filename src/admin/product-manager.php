<?php
/**
 * Product Management Page
 * 
 * Displays product management functionality
 */

// Include the main layout component
require_once('components/main-layout.php');

// Include any other required components
require_once('components/page-header.php');
require_once('components/ui/card.php');
require_once('components/ui/table.php');
require_once('components/ui/badge.php');
require_once('components/ui/dropdown-menu.php');

// Include database connection
require_once(__DIR__ . '/../includes/db_connection.php');

// Simple admin guard placeholder (customize as needed)
// if (!isset($_SESSION['admin_logged_in'])) { header('Location: login.php'); exit; }

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
                        Manage your product inventory and availability.
                    </div>
                </div>
                <button class="button" onclick="toggleAddProductSection()">
                    <svg class="mr-2 h-4 w-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="10"></circle>
                        <line x1="12" y1="8" x2="12" y2="16"></line>
                        <line x1="8" y1="12" x2="16" y2="12"></line>
                    </svg>
                    <span id="addButtonText">Add Product</span>
                </button>
            </div>
        </div>
        <div class="card-content">
            <div class="table-container">
                <table class="table" id="productsTable">
                    <thead class="table-header">
                        <tr class="table-row">
                            <th class="table-head hidden w-[50px] sm:table-cell">
                                <span class="sr-only">Image</span>
                            </th>
                            <th class="table-head">Name</th>
                            <th class="table-head">Price</th>
                            <th class="table-head">Quantity</th>
                            <th class="table-head">Status</th>
                            <th class="table-head hidden md:table-cell">Category</th>
                            <th class="table-head">
                                <span class="sr-only">Actions</span>
                            </th>
                        </tr>
                    </thead>
                    <tbody class="table-body" id="productsTableBody">
                        <!-- Products will be loaded here via JavaScript -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
    <!-- Add Product Section (Collapsible) -->
    <div class="card mt-6" id="addProductSection" style="display: none;">
        <div class="card-header">
            <div class="flex items-center justify-between">
                <div>
                    <div class="card-title" id="formSectionTitle">Add New Product</div>
                    <div class="card-description">
                        Fill in the details below to add a new product to your inventory.
                    </div>
                </div>
                <button class="button button-secondary" onclick="cancelAddProduct()">
                    <svg class="mr-2 h-4 w-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="18" y1="6" x2="6" y2="18"></line>
                        <line x1="6" y1="6" x2="18" y2="18"></line>
                    </svg>
                    Cancel
                </button>
            </div>
        </div>
        <div class="card-content">
            <form id="productForm" onsubmit="saveProduct(event)" enctype="multipart/form-data">
                <input type="hidden" name="action" id="formAction" value="add" />
                <input type="hidden" name="id" id="productId" />
                <input type="hidden" name="current_image" id="currentImageInput" />
                
                <div class="form-grid">
                    <div class="form-group">
                        <label class="form-label" for="name">Product Name</label>
                        <input type="text" class="form-input" name="name" id="name" required />
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label" for="price">Price ($)</label>
                        <input type="number" step="0.01" class="form-input" name="price" id="price" required />
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label" for="quantity">Quantity</label>
                        <input type="number" min="0" class="form-input" name="quantity" id="quantity" required />
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label" for="category">Category</label>
                        <select class="form-select" name="category" id="category">
                            <option value="">Select Category</option>
                            <option value="keyboards">Keyboards</option>
                            <option value="mice">Mice</option>
                            <option value="monitors">Monitors</option>
                            <option value="headphones">Headphones</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label" for="status">Status</label>
                        <select class="form-select" name="status" id="status">
                            <option value="active">Active</option>
                            <option value="hidden">Hidden</option>
                            <option value="out_of_stock">Out of Stock</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label" for="image">Product Image</label>
                        <input type="file" class="form-input" name="image" id="image" accept="image/*" />
                        <div id="currentImageContainer" class="mt-2 hidden">
                            <span class="text-sm text-gray-600">Current Image:</span>
                            <img id="currentImagePreview" class="product-image-thumb mt-1" />
                        </div>
                    </div>
                    
                    <div class="form-group full-width">
                        <label class="form-label" for="description">Description</label>
                        <textarea class="form-textarea" name="description" id="description" rows="3" placeholder="Enter product description..."></textarea>
                    </div>
                </div>
                
                <div class="flex gap-3 mt-6">
                    <button type="submit" class="button" id="saveButton">Save Product</button>
                    <button type="button" class="button button-secondary" onclick="resetForm()">Reset Form</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Product Modal (Keep modal for editing) -->
<div class="dialog-overlay" id="product-dialog" style="display: none;">
    <div class="dialog">
        <div class="dialog-header">
            <h2 class="dialog-title">Edit Product</h2>
            <button class="dialog-close" onclick="closeProductDialog()">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="18" y1="6" x2="6" y2="18"></line>
                    <line x1="6" y1="6" x2="18" y2="18"></line>
                </svg>
            </button>
        </div>
        <form id="editProductForm" onsubmit="saveEditProduct(event)" enctype="multipart/form-data">
            <div class="dialog-content">
                <input type="hidden" name="action" value="edit" />
                <input type="hidden" name="id" id="editProductId" />
                <input type="hidden" name="current_image" id="editCurrentImageInput" />
                
                <div class="form-grid">
                    <div class="form-group">
                        <label class="form-label" for="editName">Product Name</label>
                        <input type="text" class="form-input" name="name" id="editName" required />
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label" for="editPrice">Price ($)</label>
                        <input type="number" step="0.01" class="form-input" name="price" id="editPrice" required />
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label" for="editQuantity">Quantity</label>
                        <input type="number" min="0" class="form-input" name="quantity" id="editQuantity" required />
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label" for="editCategory">Category</label>
                        <select class="form-select" name="category" id="editCategory">
                            <option value="">Select Category</option>
                            <option value="keyboards">Keyboards</option>
                            <option value="mice">Mice</option>
                            <option value="monitors">Monitors</option>
                            <option value="headphones">Headphones</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label" for="editStatus">Status</label>
                        <select class="form-select" name="status" id="editStatus">
                            <option value="active">Active</option>
                            <option value="hidden">Hidden</option>
                            <option value="out_of_stock">Out of Stock</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label" for="editImage">Product Image</label>
                        <input type="file" class="form-input" name="image" id="editImage" accept="image/*" />
                        <div id="editCurrentImageContainer" class="mt-2 hidden">
                            <span class="text-sm text-gray-600">Current Image:</span>
                            <img id="editCurrentImagePreview" class="product-image-thumb mt-1" />
                        </div>
                    </div>
                    
                    <div class="form-group full-width">
                        <label class="form-label" for="editDescription">Description</label>
                        <textarea class="form-textarea" name="description" id="editDescription" rows="3"></textarea>
                    </div>
                </div>
            </div>
            <div class="dialog-footer">
                <button type="button" class="button button-secondary" onclick="closeProductDialog()">Cancel</button>
                <button type="submit" class="button" id="editSaveButton">Update Product</button>
            </div>
        </form>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="dialog-overlay" id="delete-dialog" style="display: none;">
    <div class="dialog">
        <div class="dialog-header">
            <h2 class="dialog-title">Confirm Delete</h2>
            <button class="dialog-close" onclick="closeDeleteDialog()">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="18" y1="6" x2="6" y2="18"></line>
                    <line x1="6" y1="6" x2="18" y2="18"></line>
                </svg>
            </button>
        </div>
        <div class="dialog-content">
            <p>Are you sure you want to delete this product? This action cannot be undone.</p>
            <input type="hidden" id="deleteProductId" />
        </div>
        <div class="dialog-footer">
            <button type="button" class="button button-secondary" onclick="closeDeleteDialog()">Cancel</button>
            <button type="button" class="button button-danger" onclick="confirmDelete()">Delete Product</button>
        </div>
    </div>
</div>

<style>
.product-image-thumb { 
    width: 40px; 
    height: 40px; 
    object-fit: cover; 
    border-radius: 6px; 
}

.form-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 1rem;
}

.form-group.full-width {
    grid-column: span 2;
}

.button-danger {
    background-color: #dc2626;
    color: white;
}

.button-danger:hover {
    background-color: #b91c1c;
}

@media (max-width: 768px) {
    .form-grid {
        grid-template-columns: 1fr;
    }
    
    .form-group.full-width {
        grid-column: span 1;
    }
}
</style>

<script>
    // Helper to serialize form data, including files
    async function postForm(url, form) {
      const fd = new FormData(form);
      
      const res = await fetch(url, { method: "POST", body: fd });
      const json = await res.json();
      
      return json;
    }

    async function fetchProducts() {
      try {
        showLoading();
        const res = await fetch("api/products.php?all=1");
        const json = await res.json();
        if (!json.success) { 
            console.error(json.error); 
            alert("Error loading products: " + json.error);
            return; 
        }
        renderTable(json.data || []);
      } catch (error) {
        console.error("Failed to fetch products:", error);
        alert("Failed to load products. Check console for details.");
      } finally {
        hideLoading();
      }
    }

    function showLoading() {
        const tbody = document.querySelector("#productsTableBody");
        tbody.innerHTML = "<tr><td colspan=\"7\" class=\"text-center py-8\"><div class=\"animate-pulse\">Loading products...</div></td></tr>";
    }

    function hideLoading() {
        // Loading state is automatically removed when table renders
    }

    // Helper function to fix image paths for admin interface
    function getImagePath(imagePath) {
      if (!imagePath) return "";
      
      // Handle uploads directory (new uploaded files)
      if (imagePath.startsWith("../uploads/")) {
        return imagePath;
      }
      
      // Handle assets directory paths
      if (imagePath.startsWith("../assets/")) {
        return imagePath;
      }
      
      // If it is just assets/, add ../
      if (imagePath.startsWith("assets/")) {
        return "../" + imagePath;
      }
      
      // If it starts with src/assets, replace with ../assets
      if (imagePath.startsWith("src/assets/")) {
        return imagePath.replace("src/assets/", "../assets/");
      }
      
      // Handle absolute paths from old system
      if (imagePath.startsWith("/TechGear/uploads/")) {
        return imagePath.replace("/TechGear/uploads/", "../uploads/");
      }
      
      return imagePath;
    }

    function renderTable(products) {
      const tbody = document.querySelector("#productsTableBody");
      tbody.innerHTML = "";
      
      if (products.length === 0) {
        tbody.innerHTML = "<tr><td colspan=\"7\" class=\"text-center py-8 text-gray-500\">No products found. Add your first product!</td></tr>";
        return;
      }
      
      products.forEach(p => {
        const tr = document.createElement("tr");
        tr.className = "table-row";
        tr.setAttribute("data-product-id", p.id);
        
        const imagePath = getImagePath(p.image);
        let statusClass = "badge badge-";
        let statusText = p.status;
        
        switch(p.status) {
            case "active":
                statusClass += "success";
                break;
            case "hidden":
                statusClass += "secondary";
                statusText = "Hidden";
                break;
            case "out_of_stock":
                statusClass += "warning";
                statusText = "Out of Stock";
                break;
            default:
                statusClass += "secondary";
        }
        
        tr.innerHTML = 
            "<td class=\"table-cell hidden sm:table-cell\">" +
                (imagePath ? "<img src=\"" + imagePath + "\" alt=\"" + p.name + "\" class=\"product-image-thumb rounded\"/>" : "<div class=\"w-10 h-10 bg-gray-200 rounded flex items-center justify-center\"><span class=\"text-gray-500 text-xs\">No img</span></div>") +
            "</td>" +
            "<td class=\"table-cell font-medium\">" + p.name + "</td>" +
            "<td class=\"table-cell\">$" + (p.price?.toFixed ? p.price.toFixed(2) : p.price) + "</td>" +
            "<td class=\"table-cell\">" + (p.quantity || 0) + "</td>" +
            "<td class=\"table-cell\">" +
                "<span class=\"" + statusClass + "\">" + statusText + "</span>" +
            "</td>" +
            "<td class=\"table-cell hidden md:table-cell\">" + (p.category || "N/A") + "</td>" +
            "<td class=\"table-cell\">" +
                "<div class=\"dropdown-menu\">" +
                    "<button class=\"dropdown-trigger\" onclick=\"toggleDropdown(this)\">" +
                        "<svg xmlns=\"http://www.w3.org/2000/svg\" width=\"16\" height=\"16\" viewBox=\"0 0 24 24\" fill=\"none\" stroke=\"currentColor\" stroke-width=\"2\" stroke-linecap=\"round\" stroke-linejoin=\"round\">" +
                            "<circle cx=\"12\" cy=\"12\" r=\"1\"></circle>" +
                            "<circle cx=\"19\" cy=\"12\" r=\"1\"></circle>" +
                            "<circle cx=\"5\" cy=\"12\" r=\"1\"></circle>" +
                        "</svg>" +
                    "</button>" +
                    "<div class=\"dropdown-menu-content\">" +
                        "<a href=\"#\" onclick=\"openEdit(" + p.id + ", \'" + encodeURIComponent(p.name) + "\', \'" + encodeURIComponent(p.description || "") + "\', \'" + p.price + "\', \'" + (p.quantity || 0) + "\', \'" + p.status + "\', \'" + encodeURIComponent(p.category || "") + "\', \'" + encodeURIComponent(p.image || "") + "\')\">Edit</a>" +
                        "<a href=\"#\" onclick=\"toggleStatus(" + p.id + ")\">" + (p.status === "active" ? "Hide" : "Show") + " Product</a>" +
                        "<a href=\"#\" onclick=\"showDeleteDialog(" + p.id + ")\" class=\"text-red-600\">Delete</a>" +
                    "</div>" +
                "</div>" +
            "</td>";
        tbody.appendChild(tr);
      });
    }

    function toggleDropdown(button) {
        const dropdown = button.nextElementSibling;
        const allDropdowns = document.querySelectorAll(".dropdown-menu-content");
        
        // Close all other dropdowns
        allDropdowns.forEach(d => {
            if (d !== dropdown) {
                d.style.display = "none";
            }
        });
        
        // Toggle current dropdown
        dropdown.style.display = dropdown.style.display === "block" ? "none" : "block";
    }

    function openEdit(id, nameEnc, descEnc, price, quantity, status, categoryEnc, imageEnc) {
      document.getElementById("product-dialog").style.display = "block";
      document.getElementById("editProductId").value = id;
      document.getElementById("editName").value = decodeURIComponent(nameEnc);
      document.getElementById("editDescription").value = decodeURIComponent(descEnc);
      document.getElementById("editPrice").value = price;
      document.getElementById("editQuantity").value = quantity;
      document.getElementById("editStatus").value = status;
      document.getElementById("editCategory").value = decodeURIComponent(categoryEnc || "");
      document.getElementById("editImage").value = "";
      
      // Set current image
      const imagePath = getImagePath(decodeURIComponent(imageEnc || ""));
      document.getElementById("editCurrentImageInput").value = imagePath;
      
      // Show current image if exists
      const currentImageContainer = document.getElementById("editCurrentImageContainer");
      const currentImagePreview = document.getElementById("editCurrentImagePreview");
      
      if (imagePath) {
        currentImagePreview.src = imagePath;
        currentImagePreview.alt = decodeURIComponent(nameEnc);
        currentImageContainer.classList.remove("hidden");
      } else {
        currentImageContainer.classList.add("hidden");
      }
    }

    function toggleAddProductSection() {
      const section = document.getElementById("addProductSection");
      const button = document.getElementById("addButtonText");
      
      if (section.style.display === "none" || section.style.display === "") {
        section.style.display = "block";
        button.textContent = "Hide Form";
        // Scroll to the form section
        section.scrollIntoView({ behavior: "smooth" });
        resetForm();
      } else {
        section.style.display = "none";
        button.textContent = "Add Product";
      }
    }
    
    function cancelAddProduct() {
      document.getElementById("addProductSection").style.display = "none";
      document.getElementById("addButtonText").textContent = "Add Product";
      resetForm();
    }
    
    function resetForm() {
      document.getElementById("productForm").reset();
      document.getElementById("formAction").value = "add";
      document.getElementById("productId").value = "";
      document.getElementById("currentImageInput").value = "";
      document.getElementById("currentImageContainer").classList.add("hidden");
      document.getElementById("formSectionTitle").textContent = "Add New Product";
      document.getElementById("saveButton").textContent = "Save Product";
    }
    
    function closeProductDialog() {
        document.getElementById("product-dialog").style.display = "none";
    }

    function showDeleteDialog(id) {
        document.getElementById("delete-dialog").style.display = "block";
        document.getElementById("deleteProductId").value = id;
    }
    
    function closeDeleteDialog() {
        document.getElementById("delete-dialog").style.display = "none";
    }

    async function saveProduct(e) {
      e.preventDefault();
      const form = document.getElementById("productForm");
      const saveButton = document.getElementById("saveButton");
      const originalText = saveButton.textContent;
      
      try {
        saveButton.textContent = "Saving...";
        saveButton.disabled = true;
        
        const json = await postForm("api/products.php", form);
        if (!json.success) { 
          alert(json.error || "Operation failed"); 
          return; 
        }
        
        // Hide the add section and reset form
        cancelAddProduct();
        await fetchProducts();
        alert("Product saved successfully!");
      } catch (error) {
        console.error("Save product error:", error);
        alert("Failed to save product. Check console for details.");
      } finally {
        saveButton.textContent = originalText;
        saveButton.disabled = false;
      }
    }

    async function saveEditProduct(e) {
      e.preventDefault();
      const form = document.getElementById("editProductForm");
      const saveButton = document.getElementById("editSaveButton");
      const originalText = saveButton.textContent;
      
      try {
        saveButton.textContent = "Updating...";
        saveButton.disabled = true;
        
        const json = await postForm("api/products.php", form);
        if (!json.success) { 
          alert(json.error || "Operation failed"); 
          return; 
        }
        
        closeProductDialog();
        await fetchProducts();
        alert("Product updated successfully!");
      } catch (error) {
        console.error("Update product error:", error);
        alert("Failed to update product. Check console for details.");
      } finally {
        saveButton.textContent = originalText;
        saveButton.disabled = false;
      }
    }

    async function confirmDelete() {
      const id = document.getElementById("deleteProductId").value;
      
      try {
        const fd = new FormData();
        fd.append("action", "delete");
        fd.append("id", id);
        const res = await fetch("api/products.php", { method: "POST", body: fd });
        const json = await res.json();
        if (!json.success) { 
          alert(json.error || "Delete failed"); 
          return; 
        }
        
        closeDeleteDialog();
        await fetchProducts();
        alert("Product deleted successfully!");
      } catch (error) {
        console.error("Delete product error:", error);
        alert("Failed to delete product. Check console for details.");
      }
    }

    async function toggleStatus(id) {
      try {
        const fd = new FormData();
        fd.append("action", "toggle");
        fd.append("id", id);
        const res = await fetch("api/products.php", { method: "POST", body: fd });
        const json = await res.json();
        if (!json.success) { 
          alert(json.error || "Toggle failed"); 
          return; 
        }
        
        await fetchProducts();
        alert("Product status updated successfully!");
      } catch (error) {
        console.error("Toggle status error:", error);
        alert("Failed to toggle status. Check console for details.");
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

    document.addEventListener("DOMContentLoaded", fetchProducts);
</script>';

// Set page title
$pageTitle = 'TechGear - Product Management';

// Page-specific JavaScript
$pageSpecificJS = ['assets/js/pages/products.js'];

// Include header
include_once('includes/header.php');

// Render the layout with our content
$fullPage = renderMainLayout($pageContent);

// Output the layout content
echo $fullPage;

// Include footer
include_once('includes/footer.php');
?>