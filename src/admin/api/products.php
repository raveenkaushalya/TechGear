<?php
// Admin Products API: CRUD operations
// Methods via POST 'action' or GET for listing
// Returns JSON

header('Content-Type: application/json');

require_once(__DIR__ . '/../../includes/db_connection.php');

// Define upload directory - should be in src/uploads
$uploadDir = __DIR__ . '/../../uploads';
if (!is_dir($uploadDir)) {
	mkdir($uploadDir, 0755, true);
}

// Simple helper to send JSON and exit
function json_response($data, $code = 200) {
	http_response_code($code);
	echo json_encode($data);
	exit;
}

// Ensure uploads directory exists
$uploadDir = realpath(__DIR__ . '/../../../') . DIRECTORY_SEPARATOR . 'uploads';
if (!is_dir($uploadDir)) {
	@mkdir($uploadDir, 0775, true);
}

// Handle GET: list products (all or by status)
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
	global $db_connected;
	$status = isset($_GET['status']) ? $_GET['status'] : null;
	$all = isset($_GET['all']) ? $_GET['all'] : null;

	if ($db_connected) {
		if ($all) {
			$rows = fetchAll("SELECT id, name, description, price, quantity, image, category, status, created_at FROM products ORDER BY id DESC");
		} elseif ($status && in_array($status, ['active','hidden'])) {
			$rows = fetchAll("SELECT id, name, description, price, quantity, image, category, status, created_at FROM products WHERE status = ? ORDER BY id DESC", [$status]);
		} else {
			$rows = fetchAll("SELECT id, name, description, price, quantity, image, category, status, created_at FROM products WHERE status = 'active' ORDER BY id DESC");
		}
		json_response(['success' => true, 'data' => $rows]);
	} else {
		// Database not connected - return empty result instead of fallback data
		json_response(['success' => true, 'data' => [], 'note' => 'Database not available']);
	}
}

// Helper: save uploaded image and return stored path (absolute-from-root '/uploads/...')
function handle_image_upload($fieldName = 'image') {
	global $uploadDir;
	
	if (!isset($_FILES[$fieldName]) || !is_uploaded_file($_FILES[$fieldName]['tmp_name'])) {
		return null; // no new image
	}
	
	$original = basename($_FILES[$fieldName]['name']);
	$ext = pathinfo($original, PATHINFO_EXTENSION);
	$safeBase = preg_replace('/[^a-zA-Z0-9_-]/', '_', pathinfo($original, PATHINFO_FILENAME));
	$filename = $safeBase . '_' . time() . ($ext ? ('.' . strtolower($ext)) : '');
	$targetPath = $uploadDir . DIRECTORY_SEPARATOR . $filename;
	
	if (!move_uploaded_file($_FILES[$fieldName]['tmp_name'], $targetPath)) {
		return null;
	}
	
	// Return relative path suitable for frontend pages (src/pages/)
	// Frontend pages are in src/pages/ so they need ../uploads/
	return '../uploads/' . $filename;
}

// For non-GET, expect POST with action
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	global $db_connected;
	if (!$db_connected) {
		json_response(['success' => false, 'error' => 'Database not available. Write operations are disabled in offline mode.'], 503);
	}
	$action = isset($_POST['action']) ? $_POST['action'] : '';

	switch ($action) {
		case 'add': {
			$name = trim($_POST['name'] ?? '');
			$description = trim($_POST['description'] ?? '');
			$price = $_POST['price'] ?? '';
			$quantity = intval($_POST['quantity'] ?? 0);
			$status = $_POST['status'] ?? 'active';
			$category = trim($_POST['category'] ?? '');

			if ($name === '' || $price === '') {
				json_response(['success' => false, 'error' => 'Name and price are required'], 400);
			}

			if ($quantity < 0) {
				json_response(['success' => false, 'error' => 'Quantity cannot be negative'], 400);
			}

			$imagePath = handle_image_upload('image');

			$data = [
				'name' => $name,
				'description' => $description,
				'price' => (float)$price,
				'quantity' => $quantity,
				'image' => $imagePath ?? '',
				'category' => $category,
				'status' => in_array($status, ['active','hidden']) ? $status : 'active',
			];

			$id = insert('products', $data);
			if ($id === false) {
				json_response(['success' => false, 'error' => ($_SESSION['db_error'] ?? 'Insert failed')], 500);
			}
			json_response(['success' => true, 'id' => $id]);
		}
		case 'edit': {
			$id = intval($_POST['id'] ?? 0);
			if ($id <= 0) json_response(['success' => false, 'error' => 'Invalid product id'], 400);

			$updates = [];
			if (isset($_POST['name'])) $updates['name'] = trim($_POST['name']);
			if (isset($_POST['description'])) $updates['description'] = trim($_POST['description']);
			if (isset($_POST['price'])) $updates['price'] = (float)$_POST['price'];
			if (isset($_POST['quantity'])) {
				$quantity = intval($_POST['quantity']);
				if ($quantity < 0) {
					json_response(['success' => false, 'error' => 'Quantity cannot be negative'], 400);
				}
				$updates['quantity'] = $quantity;
			}
			if (isset($_POST['category'])) $updates['category'] = trim($_POST['category']);
			if (isset($_POST['status']) && in_array($_POST['status'], ['active','hidden'])) $updates['status'] = $_POST['status'];

			$newImage = handle_image_upload('image');
			if ($newImage) {
				$updates['image'] = $newImage;
			}

			if (empty($updates)) {
				json_response(['success' => false, 'error' => 'No fields to update'], 400);
			}

			$ok = update('products', $updates, 'id', $id);
			if (!$ok) {
				json_response(['success' => false, 'error' => ($_SESSION['db_error'] ?? 'Update failed')], 500);
			}
			json_response(['success' => true]);
		}
		case 'delete': {
			$id = intval($_POST['id'] ?? 0);
			if ($id <= 0) json_response(['success' => false, 'error' => 'Invalid product id'], 400);
			$ok = delete('products', 'id', $id);
			if (!$ok) {
				json_response(['success' => false, 'error' => ($_SESSION['db_error'] ?? 'Delete failed')], 500);
			}
			json_response(['success' => true]);
		}
		case 'toggle': {
			$id = intval($_POST['id'] ?? 0);
			if ($id <= 0) json_response(['success' => false, 'error' => 'Invalid product id'], 400);
			$row = fetchOne('SELECT status FROM products WHERE id = ?', [$id]);
			if (!$row) json_response(['success' => false, 'error' => 'Product not found'], 404);
			$newStatus = ($row['status'] === 'active') ? 'hidden' : 'active';
			$ok = update('products', ['status' => $newStatus], 'id', $id);
			if (!$ok) json_response(['success' => false, 'error' => ($_SESSION['db_error'] ?? 'Toggle failed')], 500);
			json_response(['success' => true, 'status' => $newStatus]);
		}
		case 'purchase': {
			$id = intval($_POST['id'] ?? 0);
			$quantity = intval($_POST['quantity'] ?? 1);
			
			if ($id <= 0) json_response(['success' => false, 'error' => 'Invalid product id'], 400);
			if ($quantity <= 0) json_response(['success' => false, 'error' => 'Invalid quantity'], 400);
			
			// Get current quantity
			$product = fetchOne('SELECT quantity FROM products WHERE id = ?', [$id]);
			if (!$product) json_response(['success' => false, 'error' => 'Product not found'], 404);
			
			if ($product['quantity'] < $quantity) {
				json_response(['success' => false, 'error' => 'Insufficient stock'], 400);
			}
			
			// Decrease quantity
			$newQuantity = $product['quantity'] - $quantity;
			$ok = update('products', ['quantity' => $newQuantity], 'id', $id);
			if (!$ok) json_response(['success' => false, 'error' => ($_SESSION['db_error'] ?? 'Purchase failed')], 500);
			
			json_response(['success' => true, 'remaining_quantity' => $newQuantity]);
		}
		default:
			json_response(['success' => false, 'error' => 'Unsupported action'], 400);
	}
}

// Fallback for unsupported methods
json_response(['success' => false, 'error' => 'Unsupported method'], 405);

?>
