/**
 * Products Page Specific JavaScript
 */
document.addEventListener('DOMContentLoaded', function() {
    initializeProductsPage();
});

/**
 * Initialize the products page functionality
 */
function initializeProductsPage() {
    // Setup the product form submission
    const productForm = document.getElementById('product-form');
    if (productForm) {
        productForm.addEventListener('submit', handleProductFormSubmit);
    }
    
    // Initialize tooltips and other Bootstrap components
    if (typeof bootstrap !== 'undefined') {
        // Initialize tooltips
        const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]');
        [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl));
    }
}

/**
 * Handle product dialog opening
 * @param {string|null} productId - The product ID to edit, or null for a new product
 */
function openProductDialog(productId = null) {
    const dialog = document.getElementById('product-dialog');
    const dialogTitle = document.getElementById('dialog-title');
    const form = document.getElementById('product-form');
    
    if (!dialog || !form) return;
    
    // Reset the form
    form.reset();
    
    if (productId) {
        // Edit mode - populate the form with product data
        dialogTitle.textContent = 'Edit Product';
        
        // Find the product from the DOM data attributes
        const productRow = document.querySelector(`tr[data-product-id="${productId}"]`);
        if (productRow) {
            // Get all data attributes from the row
            const dataset = productRow.dataset;
            
            // Populate form fields
            document.getElementById('product-id').value = dataset.productId;
            document.getElementById('product-name').value = dataset.name;
            document.getElementById('product-description').value = dataset.description || '';
            document.getElementById('product-price').value = dataset.price;
            document.getElementById('product-quantity').value = dataset.quantity;
            
            // Select the correct option in dropdown menus
            const categorySelect = document.getElementById('product-category');
            if (categorySelect) {
                for (let i = 0; i < categorySelect.options.length; i++) {
                    if (categorySelect.options[i].value === dataset.categoryId) {
                        categorySelect.selectedIndex = i;
                        break;
                    }
                }
            }
            
            const statusSelect = document.getElementById('product-status');
            if (statusSelect) {
                for (let i = 0; i < statusSelect.options.length; i++) {
                    if (statusSelect.options[i].value === dataset.status) {
                        statusSelect.selectedIndex = i;
                        break;
                    }
                }
            }
            
            document.getElementById('product-image').value = dataset.image || '';
        }
    } else {
        // Add mode
        dialogTitle.textContent = 'Add Product';
        document.getElementById('product-id').value = '';
    }
    
    dialog.style.display = 'flex';
}

/**
 * Close the product dialog
 */
function closeProductDialog() {
    const dialog = document.getElementById('product-dialog');
    if (dialog) {
        dialog.style.display = 'none';
    }
}

/**
 * Edit a product
 * @param {string} productId - The ID of the product to edit
 */
function editProduct(productId) {
    openProductDialog(productId);
    
    // Close any open dropdown
    document.querySelectorAll('.dropdown-menu-content').forEach(dropdown => {
        dropdown.style.display = 'none';
    });
}

/**
 * Delete a product
 * @param {string} productId - The ID of the product to delete
 */
function deleteProduct(productId) {
    if (confirm('Are you sure you want to delete this product?')) {
        // In a real application, this would be an AJAX request to the server
        // For now, we'll just remove the row from the DOM to simulate deletion
        const row = document.querySelector(`tr[data-product-id="${productId}"]`);
        if (row) {
            row.remove();
            
            // Show a success message using Bootstrap toast or our custom function
            if (window.showToast) {
                window.showToast('Product deleted successfully', 'success');
            } else {
                alert('Product deleted successfully');
            }
        }
        
        // Close any open dropdown
        document.querySelectorAll('.dropdown-menu-content').forEach(dropdown => {
            dropdown.style.display = 'none';
        });
    }
}

/**
 * Toggle the dropdown menu for a product
 * @param {string} productId - The ID of the product
 */
function toggleDropdown(productId) {
    const dropdown = document.getElementById(`dropdown-${productId}`);
    if (dropdown) {
        // Close all other open dropdowns first
        document.querySelectorAll('.dropdown-menu-content').forEach(content => {
            if (content !== dropdown && content.style.display !== 'none') {
                content.style.display = 'none';
            }
        });
        
        // Toggle current dropdown
        dropdown.style.display = dropdown.style.display === 'none' ? 'block' : 'none';
    }
}

/**
 * Handle product form submission
 * @param {Event} event - The form submit event
 */
function handleProductFormSubmit(event) {
    event.preventDefault();
    const form = event.target;
    const formData = new FormData(form);
    const productData = {};
    
    // Convert form data to object
    for (const [key, value] of formData.entries()) {
        productData[key] = value;
    }
    
    // Check if this is an edit or a new product
    const isEdit = !!productData.id;
    
    if (isEdit) {
        // Update the existing row
        const row = document.querySelector(`tr[data-product-id="${productData.id}"]`);
        if (row) {
            // Update data attributes
            for (const [key, value] of Object.entries(productData)) {
                row.setAttribute(`data-${key}`, value);
            }
            
            // Update visible content
            const cells = row.querySelectorAll('td');
            if (cells.length >= 6) {
                // Image
                const img = cells[0].querySelector('img');
                if (img) {
                    img.src = productData.image;
                    img.alt = productData.name;
                }
                
                // Name
                cells[1].textContent = productData.name;
                
                // Category - get the category name from the select element
                const categorySelect = document.getElementById('product-category');
                const selectedOption = categorySelect.options[categorySelect.selectedIndex];
                cells[2].textContent = selectedOption.textContent;
                
                // Status
                const statusBadge = cells[3].querySelector('.badge');
                if (statusBadge) {
                    statusBadge.textContent = productData.status;
                    // Update badge class based on status
                    statusBadge.className = productData.status === 'available' 
                        ? 'badge badge-outline' 
                        : 'badge badge-secondary';
                }
                
                // Price
                cells[4].textContent = '$' + parseFloat(productData.price).toFixed(2);
                
                // Quantity
                cells[5].textContent = productData.quantity;
            }
            
            // Show success message
            if (window.showToast) {
                window.showToast('Product updated successfully', 'success');
            } else {
                alert('Product updated successfully');
            }
        }
    } else {
        // Create a new product row
        const tableBody = document.querySelector('.table-body');
        if (tableBody) {
            // Generate a new product ID
            const newId = 'prod-' + Date.now();
            productData.id = newId;
            
            // Get the category name
            const categorySelect = document.getElementById('product-category');
            const categoryName = categorySelect.options[categorySelect.selectedIndex].textContent;
            
            // Create row HTML
            const tr = document.createElement('tr');
            tr.className = 'table-row';
            tr.setAttribute('data-product-id', newId);
            
            // Add data attributes
            for (const [key, value] of Object.entries(productData)) {
                tr.setAttribute(`data-${key}`, value);
            }
            
            // Create cells
            const statusClass = productData.status === 'available' ? 'badge badge-outline' : 'badge badge-secondary';
            
            tr.innerHTML = `
                <td class="table-cell hidden sm:table-cell">
                    <img
                        alt="${productData.name}"
                        class="aspect-square rounded-md object-cover"
                        height="64"
                        src="${productData.image}"
                        width="64"
                        loading="lazy"
                    />
                </td>
                <td class="table-cell font-medium">${productData.name}</td>
                <td class="table-cell">${categoryName}</td>
                <td class="table-cell">
                    <span class="${statusClass}">${productData.status}</span>
                </td>
                <td class="table-cell hidden md:table-cell">
                    $${parseFloat(productData.price).toFixed(2)}
                </td>
                <td class="table-cell hidden md:table-cell">
                    ${productData.quantity}
                </td>
                <td class="table-cell">
                    <div class="dropdown-menu">
                        <button class="dropdown-menu-trigger button size-icon variant-ghost" 
                                onclick="toggleDropdown('${newId}')"
                                data-bs-toggle="tooltip"
                                data-bs-title="Actions">
                            <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="12" cy="5" r="1"></circle>
                                <circle cx="12" cy="12" r="1"></circle>
                                <circle cx="12" cy="19" r="1"></circle>
                            </svg>
                            <span class="sr-only">Toggle menu</span>
                        </button>
                        <div class="dropdown-menu-content" id="dropdown-${newId}" style="display: none;">
                            <div class="dropdown-menu-label">Actions</div>
                            <button class="dropdown-menu-item" onclick="editProduct('${newId}')">Edit</button>
                            <button class="dropdown-menu-item text-destructive" onclick="deleteProduct('${newId}')">Delete</button>
                        </div>
                    </div>
                </td>
            `;
            
            // Add to table
            tableBody.insertBefore(tr, tableBody.firstChild);
            
            // Initialize tooltip on the new action button
            if (typeof bootstrap !== 'undefined' && bootstrap.Tooltip) {
                const tooltipTrigger = tr.querySelector('[data-bs-toggle="tooltip"]');
                if (tooltipTrigger) {
                    new bootstrap.Tooltip(tooltipTrigger);
                }
            }
            
            // Show success message
            if (window.showToast) {
                window.showToast('Product added successfully', 'success');
            } else {
                alert('Product added successfully');
            }
        }
    }
    
    // Close the dialog
    closeProductDialog();
}

// Export functions to global scope for inline event handlers
window.openProductDialog = openProductDialog;
window.closeProductDialog = closeProductDialog;
window.editProduct = editProduct;
window.deleteProduct = deleteProduct;
window.toggleDropdown = toggleDropdown;
