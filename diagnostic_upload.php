<!DOCTYPE html>
<html>

<head>
    <title>Photo Upload Diagnostic</title>
    <style>
        body {
            font-family: Arial;
            margin: 20px;
        }

        .section {
            margin: 20px 0;
            padding: 15px;
            border: 1px solid #ccc;
        }

        .success {
            color: green;
        }

        .error {
            color: red;
        }

        .info {
            color: blue;
        }
    </style>
</head>

<body>
    <h1>Photo Upload Diagnostic</h1>

    <?php if ($_SERVER['REQUEST_METHOD'] === 'POST'): ?>
        <div class="section">
            <h2>Diagnostic Results</h2>

            <h3>1. Form Data Received</h3>
            <div class="info">
                POST data: <?= !empty($_POST) ? 'YES' : 'NO' ?><br>
                FILES data: <?= !empty($_FILES) ? 'YES' : 'NO' ?><br>
            </div>

            <?php if (!empty($_POST)): ?>
                <pre><?= htmlspecialchars(print_r($_POST, true)) ?></pre>
            <?php endif; ?>

            <?php if (!empty($_FILES)): ?>
                <pre><?= htmlspecialchars(print_r($_FILES, true)) ?></pre>
            <?php endif; ?>

            <h3>2. Directory Check</h3>
            <?php
            $upload_dir = './assets/emergency_tools/img/inspection/';
            echo "Upload directory: $upload_dir<br>";
            echo "Exists: " . (is_dir($upload_dir) ? '<span class="success">YES</span>' : '<span class="error">NO</span>') . "<br>";
            echo "Writable: " . (is_writable($upload_dir) ? '<span class="success">YES</span>' : '<span class="error">NO</span>') . "<br>";

            if (!is_dir($upload_dir)) {
                echo "Creating directory...<br>";
                if (mkdir($upload_dir, 0777, true)) {
                    echo '<span class="success">Directory created successfully</span><br>';
                } else {
                    echo '<span class="error">Failed to create directory</span><br>';
                }
            }
            ?>

            <h3>3. PHP Settings</h3>
            <?php
            echo "file_uploads: " . (ini_get('file_uploads') ? '<span class="success">ON</span>' : '<span class="error">OFF</span>') . "<br>";
            echo "upload_max_filesize: " . ini_get('upload_max_filesize') . "<br>";
            echo "post_max_size: " . ini_get('post_max_size') . "<br>";
            echo "max_file_uploads: " . ini_get('max_file_uploads') . "<br>";
            ?>

            <h3>4. File Upload Test</h3>
            <?php if (!empty($_FILES['photo_14'])): ?>
                <?php
                $file = $_FILES['photo_14'];
                echo "File name: " . htmlspecialchars($file['name']) . "<br>";
                echo "File size: " . $file['size'] . " bytes<br>";
                echo "File type: " . htmlspecialchars($file['type']) . "<br>";
                echo "Temp file: " . htmlspecialchars($file['tmp_name']) . "<br>";
                echo "Error code: " . $file['error'] . "<br>";

                if ($file['error'] === UPLOAD_ERR_OK) {
                    $target_file = $upload_dir . 'diagnostic_' . time() . '_' . basename($file['name']);
                    if (move_uploaded_file($file['tmp_name'], $target_file)) {
                        echo '<span class="success">✓ File uploaded successfully to: ' . htmlspecialchars($target_file) . '</span><br>';

                        // Test file exists and is readable
                        if (file_exists($target_file)) {
                            echo '<span class="success">✓ File exists and is accessible</span><br>';
                            echo "File size on disk: " . filesize($target_file) . " bytes<br>";
                        } else {
                            echo '<span class="error">✗ File was uploaded but cannot be found</span><br>';
                        }
                    } else {
                        echo '<span class="error">✗ Failed to move uploaded file</span><br>';
                    }
                } else {
                    echo '<span class="error">Upload error code: ' . $file['error'] . '</span><br>';
                }
                ?>
            <?php else: ?>
                <span class="error">No file uploaded</span>
            <?php endif; ?>
        </div>
    <?php endif; ?>

    <div class="section">
        <h2>Test Form</h2>
        <p>This form mimics the inspection form structure:</p>
        <form method="POST" enctype="multipart/form-data">
            <!-- Hidden fields like in inspection form -->
            <input type="hidden" name="equipment_id" value="12">

            <!-- Checksheet item data -->
            <input type="hidden" name="checksheet_items[14][status]" value="ok">
            <input type="hidden" name="checksheet_items[14][note]" value="Test note">

            <!-- Photo upload for item 14 -->
            <label>Photo for Item 14:</label><br>
            <input type="file" name="photo_14" accept="image/*"><br><br>

            <label>General Notes:</label><br>
            <textarea name="notes">Test inspection notes</textarea><br><br>

            <button type="submit">Test Upload</button>
        </form>
    </div>
</body>

</html>