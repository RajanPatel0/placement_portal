<?php
// Force create directories with verification
$tempDir = __DIR__ . '/temp';
$uploadDir = __DIR__ . '/uploads';

echo "<h3>Directory Creation:</h3>";

// Create temp directory
if (!is_dir($tempDir)) {
    if (mkdir($tempDir, 0755, true)) {
        echo "✅ Created temp directory: $tempDir<br>";
    } else {
        echo "❌ FAILED to create temp directory: $tempDir<br>";
    }
} else {
    echo "✅ Temp directory already exists: $tempDir<br>";
}

// Create uploads directory  
if (!is_dir($uploadDir)) {
    if (mkdir($uploadDir, 0755, true)) {
        echo "✅ Created uploads directory: $uploadDir<br>";
    } else {
        echo "❌ FAILED to create uploads directory: $uploadDir<br>";
    }
} else {
    echo "✅ Uploads directory already exists: $uploadDir<br>";
}

// Verify permissions
echo "Temp directory writable: " . (is_writable($tempDir) ? "✅ YES" : "❌ NO") . "<br>";
echo "Uploads directory writable: " . (is_writable($uploadDir) ? "✅ YES" : "❌ NO") . "<br>";

// Add temp folder monitoring BEFORE processing upload
echo "<h3>Temp Folder Status:</h3>";
$tempFilesBefore = scandir($tempDir);
echo "Files in temp before upload: " . count($tempFilesBefore) . "<br>";
if (count($tempFilesBefore) > 2) { // 2 for . and ..
    echo "Files found:<br>";
    foreach ($tempFilesBefore as $file) {
        if ($file != '.' && $file != '..') {
            echo "- $file (" . filesize($tempDir . '/' . $file) . " bytes)<br>";
        }
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    echo "<h3>Upload Attempt:</h3>";
    
    // Check PHP's upload temp directory
    echo "PHP upload_tmp_dir ini setting: " . ini_get('upload_tmp_dir') . "<br>";
    echo "PHP upload_tmp_dir actual: " . sys_get_temp_dir() . "<br>";
    
    if (!isset($_FILES['file'])) {
        echo "❌ No file data received<br>";
    } else {
        echo "<pre>File data: ";
        print_r($_FILES['file']);
        echo "</pre>";
        
        // KEY CHECK: See if file is in temp directory
        echo "Temp file location: " . $_FILES['file']['tmp_name'] . "<br>";
        echo "Temp file exists: " . (file_exists($_FILES['file']['tmp_name']) ? "✅ YES" : "❌ NO") . "<br>";
        
        if ($_FILES['file']['error'] === UPLOAD_ERR_OK) {
            // Show temp folder contents AFTER upload but BEFORE move
            $tempFilesDuring = scandir($tempDir);
            echo "Files in temp DURING upload (before move): " . count($tempFilesDuring) . "<br>";

            $tempDirpath = $tempDir . '/' . basename($_FILES['file']['name']);
            
            $destination = $uploadDir . '/' . basename($_FILES['file']['name']);
            if (move_uploaded_file($_FILES['file']['tmp_name'], $tempDirpath)) {               

                copy($tempDirpath, $destination);
                
echo "✅ <strong>SUCCESS: File uploaded and saved!</strong><br>";
                
                // Show temp folder contents AFTER move
                $tempFilesAfter = scandir($tempDir);
                echo "Files in temp AFTER move: " . count($tempFilesAfter) . "<br>";
            } else {
                echo "❌ Failed to move uploaded file<br>";
            }
        } else {
            echo "❌ Upload error: " . $_FILES['file']['error'] . "<br>";
            echo "Error meaning: " . getUploadError($_FILES['file']['error']) . "<br>";
        }
    }
}

// Helper function for error codes
function getUploadError($code) {
    $errors = [
        UPLOAD_ERR_OK => 'No error',
        UPLOAD_ERR_INI_SIZE => 'File exceeds upload_max_filesize',
        UPLOAD_ERR_FORM_SIZE => 'File exceeds MAX_FILE_SIZE in form',
        UPLOAD_ERR_PARTIAL => 'File only partially uploaded',
        UPLOAD_ERR_NO_FILE => 'No file was uploaded',
        UPLOAD_ERR_NO_TMP_DIR => 'Missing temporary folder',
        UPLOAD_ERR_CANT_WRITE => 'Failed to write file to disk',
        UPLOAD_ERR_EXTENSION => 'PHP extension stopped the upload',
    ];
    return $errors[$code] ?? "Unknown error ($code)";
}
?>

<form method="post" enctype="multipart/form-data">
    <input type="file" name="file" required>
    <input type="submit" value="Upload Test">
</form>