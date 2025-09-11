<?php
header('Content-Type: application/json');

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Simple upload test
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $result = ['success' => false, 'debug' => []];
    
    $result['debug']['post_data'] = $_POST;
    $result['debug']['files_data'] = $_FILES;
    
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = realpath(__DIR__ . '/') . DIRECTORY_SEPARATOR . 'uploads';
        $result['debug']['upload_dir'] = $uploadDir;
        $result['debug']['dir_exists'] = is_dir($uploadDir);
        $result['debug']['dir_writable'] = is_writable($uploadDir);
        
        $filename = 'test_' . time() . '_' . basename($_FILES['image']['name']);
        $targetPath = $uploadDir . DIRECTORY_SEPARATOR . $filename;
        $result['debug']['target_path'] = $targetPath;
        
        if (move_uploaded_file($_FILES['image']['tmp_name'], $targetPath)) {
            $result['success'] = true;
            $result['file_path'] = '../uploads/' . $filename;
            $result['message'] = 'Upload successful';
        } else {
            $result['error'] = 'Failed to move uploaded file';
        }
    } else {
        $result['error'] = 'No file uploaded or upload error';
        if (isset($_FILES['image'])) {
            $result['upload_error_code'] = $_FILES['image']['error'];
        }
    }
    
    echo json_encode($result, JSON_PRETTY_PRINT);
} else {
    echo json_encode(['error' => 'Only POST method allowed']);
}
?>
