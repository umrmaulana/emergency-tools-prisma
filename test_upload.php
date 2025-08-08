<?php
// Simple test script to debug file upload issues
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h2>File Upload Test</h2>";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    echo "<h3>POST Data Received:</h3>";
    echo "<pre>" . print_r($_POST, true) . "</pre>";

    echo "<h3>FILES Data Received:</h3>";
    echo "<pre>" . print_r($_FILES, true) . "</pre>";

    // Test upload directory
    $upload_dir = './assets/emergency_tools/img/inspection/';
    echo "<h3>Upload Directory Info:</h3>";
    echo "Directory: $upload_dir<br>";
    echo "Exists: " . (is_dir($upload_dir) ? 'YES' : 'NO') . "<br>";
    echo "Writable: " . (is_writable($upload_dir) ? 'YES' : 'NO') . "<br>";

    // Test simple file upload
    if (isset($_FILES['test_photo']) && $_FILES['test_photo']['error'] === UPLOAD_ERR_OK) {
        $tmp_name = $_FILES['test_photo']['tmp_name'];
        $name = 'test_' . time() . '.jpg';
        $destination = $upload_dir . $name;

        if (move_uploaded_file($tmp_name, $destination)) {
            echo "<div style='color: green;'>✓ File uploaded successfully to: $destination</div>";
        } else {
            echo "<div style='color: red;'>✗ Failed to move uploaded file</div>";
        }
    } else if (isset($_FILES['test_photo'])) {
        echo "<div style='color: red;'>Upload Error Code: " . $_FILES['test_photo']['error'] . "</div>";
    }
} else {
    echo '<form method="POST" enctype="multipart/form-data">
        <h3>Test Photo Upload</h3>
        <p>Select a photo file (max 5MB):</p>
        <input type="file" name="test_photo" accept="image/*" required>
        <br><br>
        <input type="hidden" name="test_field" value="test_value">
        <button type="submit">Upload Test Photo</button>
    </form>';
}

echo "<h3>PHP Settings:</h3>";
echo "file_uploads: " . (ini_get('file_uploads') ? 'ON' : 'OFF') . "<br>";
echo "upload_max_filesize: " . ini_get('upload_max_filesize') . "<br>";
echo "post_max_size: " . ini_get('post_max_size') . "<br>";
echo "max_file_uploads: " . ini_get('max_file_uploads') . "<br>";
echo "upload_tmp_dir: " . (ini_get('upload_tmp_dir') ?: 'default') . "<br>";

?>