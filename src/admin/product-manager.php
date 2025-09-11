<?php
require_once(__DIR__ . '/../includes/db_connection.php');

// Simple admin guard placeholder (customize as needed)
// if (!isset($_SESSION['admin_logged_in'])) { header('Location: login.php'); exit; }

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Product Manager</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
  <style>
    .product-image-thumb { width: 64px; height: 64px; object-fit: cover; border-radius: 6px; }
  </style>
  <script>
    // Helper to serialize form data, including files
    async function postForm(url, form) {
      const fd = new FormData(form);
      
      // Debug: Log form data
      console.log('Sending form data to:', url);
      for (let pair of fd.entries()) {
        console.log(pair[0] + ':', pair[1]);
      }
      
      const res = await fetch(url, { method: 'POST', body: fd });
      const json = await res.json();
      
      console.log('Response:', json);
      return json;
    }

    async function fetchProducts() {
      const res = await fetch('api/products.php?all=1');
      const json = await res.json();
      if (!json.success) { console.error(json.error); return; }
      renderTable(json.data || []);
    }

    // Helper function to fix image paths for admin interface
    function getImagePath(imagePath) {
      if (!imagePath) return '';
      
      // Handle uploads directory (new uploaded files)
      if (imagePath.startsWith('../uploads/')) {
        // For admin interface, uploads are at same level: ../uploads/
        return imagePath;
      }
      
      // Handle assets directory paths
      if (imagePath.startsWith('../assets/')) {
        return imagePath; // This is correct for admin directory
      }
      
      // If it's just assets/, add ../
      if (imagePath.startsWith('assets/')) {
        return '../' + imagePath;
      }
      
      // If it starts with src/assets, replace with ../assets
      if (imagePath.startsWith('src/assets/')) {
        return imagePath.replace('src/assets/', '../assets/');
      }
      
      // Handle absolute paths from old system
      if (imagePath.startsWith('/TechGear/uploads/')) {
        return imagePath.replace('/TechGear/uploads/', '../uploads/');
      }
      
      return imagePath;
    }

    function renderTable(products) {
      const tbody = document.querySelector('#productsTable tbody');
      tbody.innerHTML = '';
      products.forEach(p => {
        const tr = document.createElement('tr');
        const imagePath = getImagePath(p.image);
        tr.innerHTML = `
          <td>${p.id}</td>
          <td>${p.name}</td>
          <td>${p.price?.toFixed ? p.price.toFixed(2) : p.price}</td>
          <td>${p.quantity || 0}</td>
          <td>${p.status}</td>
          <td>${imagePath ? `<img src="${imagePath}" alt="${p.name}" class="product-image-thumb"/>` : 'No image'}</td>
          <td>
            <button class="btn btn-sm btn-primary me-1" onclick="openEdit(${p.id}, '${encodeURIComponent(p.name)}', '${encodeURIComponent(p.description || '')}', '${p.price}', '${p.quantity || 0}', '${p.status}', '${encodeURIComponent(p.category || '')}')">Edit</button>
            <button class="btn btn-sm btn-warning me-1" onclick="toggleStatus(${p.id})">Toggle</button>
            <button class="btn btn-sm btn-danger" onclick="deleteProduct(${p.id})">Delete</button>
          </td>`;
        tbody.appendChild(tr);
      });
    }

    function openEdit(id, nameEnc, descEnc, price, quantity, status, categoryEnc) {
      const modal = new bootstrap.Modal(document.getElementById('productModal'));
      document.getElementById('formAction').value = 'edit';
      document.getElementById('productId').value = id;
      document.getElementById('name').value = decodeURIComponent(nameEnc);
      document.getElementById('description').value = decodeURIComponent(descEnc);
      document.getElementById('price').value = price;
      document.getElementById('quantity').value = quantity;
      document.getElementById('status').value = status;
      document.getElementById('category').value = decodeURIComponent(categoryEnc || '');
      document.getElementById('image').value = '';
      document.getElementById('modalTitle').textContent = 'Edit Product';
      modal.show();
    }

    function openAdd() {
      const modal = new bootstrap.Modal(document.getElementById('productModal'));
      document.getElementById('formAction').value = 'add';
      document.getElementById('productId').value = '';
      document.getElementById('name').value = '';
      document.getElementById('description').value = '';
      document.getElementById('price').value = '';
      document.getElementById('quantity').value = '0';
      document.getElementById('status').value = 'active';
      document.getElementById('category').value = '';
      document.getElementById('image').value = '';
      document.getElementById('modalTitle').textContent = 'Add Product';
      modal.show();
    }

    async function saveProduct(e) {
      e.preventDefault();
      const form = document.getElementById('productForm');
      const json = await postForm('api/products.php', form);
      if (!json.success) { alert(json.error || 'Operation failed'); return; }
      bootstrap.Modal.getInstance(document.getElementById('productModal')).hide();
      await fetchProducts();
    }

    async function deleteProduct(id) {
      if (!confirm('Delete this product?')) return;
      const fd = new FormData();
      fd.append('action', 'delete');
      fd.append('id', id);
      const res = await fetch('api/products.php', { method: 'POST', body: fd });
      const json = await res.json();
      if (!json.success) { alert(json.error || 'Delete failed'); return; }
      fetchProducts();
    }

    async function toggleStatus(id) {
      const fd = new FormData();
      fd.append('action', 'toggle');
      fd.append('id', id);
      const res = await fetch('api/products.php', { method: 'POST', body: fd });
      const json = await res.json();
      if (!json.success) { alert(json.error || 'Toggle failed'); return; }
      fetchProducts();
    }

    document.addEventListener('DOMContentLoaded', fetchProducts);
  </script>
</head>
<body class="bg-light">
  <div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <h1 class="h3">Product Manager</h1>
      <button class="btn btn-success" onclick="openAdd()">Add Product</button>
    </div>

    <div class="card">
      <div class="card-body">
        <div class="table-responsive">
          <table class="table table-striped" id="productsTable">
            <thead>
              <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Price</th>
                <th>Quantity</th>
                <th>Status</th>
                <th>Image</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody></tbody>
          </table>
        </div>
      </div>
    </div>
  </div>

  <!-- Modal -->
  <div class="modal fade" id="productModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
      <div class="modal-content">
        <form id="productForm" onsubmit="saveProduct(event)" enctype="multipart/form-data">
          <div class="modal-header">
            <h5 class="modal-title" id="modalTitle">Add Product</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <input type="hidden" name="action" id="formAction" value="add" />
            <input type="hidden" name="id" id="productId" />
            <div class="row g-3">
              <div class="col-md-6">
                <label class="form-label">Name</label>
                <input type="text" class="form-control" name="name" id="name" required />
              </div>
              <div class="col-md-3">
                <label class="form-label">Price</label>
                <input type="number" step="0.01" class="form-control" name="price" id="price" required />
              </div>
              <div class="col-md-3">
                <label class="form-label">Quantity</label>
                <input type="number" min="0" class="form-control" name="quantity" id="quantity" required />
              </div>
              <div class="col-md-3">
                <label class="form-label">Status</label>
                <select class="form-select" name="status" id="status">
                  <option value="active">Active</option>
                  <option value="hidden">Hidden</option>
                </select>
              </div>
              <div class="col-md-3">
                <label class="form-label">Category</label>
                <select class="form-select" name="category" id="category">
                  <option value="">Select Category</option>
                  <option value="keyboards">Keyboards</option>
                  <option value="mice">Mice</option>
                  <option value="monitors">Monitors</option>
                  <option value="headphones">Headphones</option>
                </select>
              </div>
              <div class="col-12">
                <label class="form-label">Description</label>
                <textarea class="form-control" name="description" id="description" rows="3"></textarea>
              </div>
              <div class="col-12">
                <label class="form-label">Image</label>
                <input type="file" class="form-control" name="image" id="image" accept="image/*" />
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary">Save</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
