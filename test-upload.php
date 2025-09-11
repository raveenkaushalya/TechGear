<?php
// PHP Upload Configuration Test
echo "<h2>PHP Upload Configuration</h2>";
echo "<table border='1' cellpadding='5'>";
echo "<tr><th>Setting</th><th>Value</th></tr>";
echo "<tr><td>file_uploads</td><td>" . (ini_get('file_uploads') ? 'ON' : 'OFF') . "</td></tr>";
echo "<tr><td>upload_max_filesize</td><td>" . ini_get('upload_max_filesize') . "</td></tr>";
echo "<tr><td>post_max_size</td><td>" . ini_get('post_max_size') . "</td></tr>";
echo "<tr><td>max_file_uploads</td><td>" . ini_get('max_file_uploads') . "</td></tr>";
echo "<tr><td>max_execution_time</td><td>" . ini_get('max_execution_time') . "</td></tr>";
echo "<tr><td>memory_limit</td><td>" . ini_get('memory_limit') . "</td></tr>";
echo "</table>";

echo "<h2>Upload Directory Test</h2>";
$uploadDir = realpath(__DIR__ . '/') . DIRECTORY_SEPARATOR . 'uploads';
echo "<p>Upload directory: $uploadDir</p>";
echo "<p>Directory exists: " . (is_dir($uploadDir) ? 'YES' : 'NO') . "</p>";
echo "<p>Directory writable: " . (is_writable($uploadDir) ? 'YES' : 'NO') . "</p>";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['test_file'])) {
    echo "<h2>Upload Test Result</h2>";
    echo "<pre>";
    print_r($_FILES);
    echo "</pre>";
    
    if ($_FILES['test_file']['error'] === UPLOAD_ERR_OK) {
        $testFile = $uploadDir . DIRECTORY_SEPARATOR . 'test_' . time() . '.txt';
        if (move_uploaded_file($_FILES['test_file']['tmp_name'], $testFile)) {
            echo "<p style='color: green;'>Upload successful! File saved to: $testFile</p>";
            // Clean up
            unlink($testFile);
        } else {
            echo "<p style='color: red;'>Upload failed: Could not move file</p>";
        }
    } else {
        echo "<p style='color: red;'>Upload error: " . $_FILES['test_file']['error'] . "</p>";
    }
}
?>

<h2>Test File Upload</h2>
<form method="POST" enctype="multipart/form-data">
    <input type="file" name="test_file" required>
    <button type="submit">Test Upload</button>
</form>
