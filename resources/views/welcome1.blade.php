<?php

if (isset($_FILES['file'])) {

    $uploadDir = "uploads/";

    // Allowed extensions
    $allowedExtensions = ['jpg', 'jpeg', 'png', 'pdf'];

    // Max file size (2MB)
    $maxSize = 2 * 1024 * 1024;

    $fileName = $_FILES['file']['name'];
    $fileTmp  = $_FILES['file']['tmp_name'];
    $fileSize = $_FILES['file']['size'];

    // Get extension
    $extension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

    // Validate extension
    if (!in_array($extension, $allowedExtensions)) {
        die("Invalid file type.");
    }

    // Validate file size
    if ($fileSize > $maxSize) {
        die("File too large.");
    }

    // Validate MIME type
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mimeType = finfo_file($finfo, $fileTmp);

    $allowedMimeTypes = [
        'image/jpeg',
        'image/png',
        'application/pdf'
    ];

    if (!in_array($mimeType, $allowedMimeTypes)) {
        die("Invalid MIME type.");
    }

    // Generate random filename
    $newFileName = uniqid() . "." . $extension;

    // Move file
    if (move_uploaded_file($fileTmp, $uploadDir . $newFileName)) {
        echo "Secure upload successful!";
    } else {
        echo "Upload failed.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Secure File Upload</title>
</head>
<body>

<h2>Secure File Upload</h2>

<form method="POST" enctype="multipart/form-data">
    <input type="file" name="file">
    <button type="submit">Upload</button>
</form>

</body>
</html>