<?php

if (isset($_FILES['file'])) {

    $uploadDir = "uploads/";

    $allowedExtensions = ['jpg', 'jpeg', 'png', 'pdf'];
    $allowedMimeTypes = [
        'image/jpeg',
        'image/png',
        'application/pdf'
    ];

    $maxSize = 2 * 1024 * 1024;

    $originalName = $_FILES['file']['name'];
    $tmpName      = $_FILES['file']['tmp_name'];
    $fileSize     = $_FILES['file']['size'];

    // =========================
    // VALIDATION
    // =========================

    // Check extension
    $extension = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));

    if (!in_array($extension, $allowedExtensions)) {
        die("Validation Failed: Invalid extension.");
    }

    // Check file size
    if ($fileSize > $maxSize) {
        die("Validation Failed: File too large.");
    }

    // Check MIME type
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mimeType = finfo_file($finfo, $tmpName);

    if (!in_array($mimeType, $allowedMimeTypes)) {
        die("Validation Failed: Invalid MIME type.");
    }

    // =========================
    // SANITIZATION
    // =========================

    // Remove dangerous characters
    $safeName = preg_replace("/[^a-zA-Z0-9\._-]/", "_", $originalName);

    // Prevent filename collisions
    $safeName = time() . "_" . $safeName;

    // Upload file
    if (move_uploaded_file($tmpName, $uploadDir . $safeName)) {
        echo "Validated and sanitized upload successful!";
    } else {
        echo "Upload failed.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Validated and Sanitized Upload</title>
</head>
<body>

<h2>Validated + Sanitized Upload</h2>

<form method="POST" enctype="multipart/form-data">
    <input type="file" name="file">
    <button type="submit">Upload</button>
</form>

</body>
</html>