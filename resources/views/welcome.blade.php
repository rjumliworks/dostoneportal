JERWIN's Site

<?php
if (isset($_FILES['file'])) {

    $uploadDir = "uploads/";
    $filename = $_FILES['file']['name'];

    // Directly move uploaded file
    move_uploaded_file($_FILES['file']['tmp_name'], $uploadDir . $filename);

    echo "File uploaded successfully!";
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Insecure File Upload</title>
</head>
<body>

<h2>Insecure File Upload</h2>

<form method="POST" enctype="multipart/form-data">
    <input type="file" name="file">
    <button type="submit">Upload</button>
</form>

</body>
</html>