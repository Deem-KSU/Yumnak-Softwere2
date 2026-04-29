<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

session_start();
include('db_connection.php');

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $username = trim($_POST['username']);
    $password = $_POST['password'];

    if (empty($username) || empty($password)) {
        $error = "Please enter username and password.";
    } else {

        // Check Traveler
        $stmt = $conn->prepare("SELECT UserID, UserName, Password FROM TRAVELER WHERE UserName = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows == 1) {
            $user = $result->fetch_assoc();

            if (password_verify($password, $user['Password'])) {
                $_SESSION['user_id'] = $user['UserID'];
                $_SESSION['username'] = $user['UserName'];
                $_SESSION['role'] = "user";

                header("Location: User-Dashboard.php");
                exit();
            }
        }

        // Check Admin
        $stmt = $conn->prepare("SELECT AdminID, UserName, Password FROM ADMIN WHERE UserName = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows == 1) {
            $admin = $result->fetch_assoc();

            if (password_verify($password, $admin['Password'])) {
                $_SESSION['admin_id'] = $admin['AdminID'];
                $_SESSION['username'] = $admin['UserName'];
                $_SESSION['role'] = "admin";

                header("Location: Admin-Dashboard.php");
                exit();
            }
        }

        $error = "Invalid username or password.";
    }
}
?>

<!DOCTYPE html>
<html lang="ar" dir="ltr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Yumnak | Log In</title>
<link rel="stylesheet" href="StyleD.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>

<body>
<header>
    <div class="logo">
        <img src="Image/Yumnak-Logo.png" alt="Yumnak Logo">
    </div>
</header>

<section class="signup-section">
  <div class="signup-container">

    <div class="signup-left">
      <div class="left-content">
        <img src="Image/Yumnak-Logo.png" class="signup-image" alt="Yumnak Logo">
        <h2>Welcome Back</h2>
        <p>Log in to request airport assistance easily and make your travel experience seamless.</p>
      </div>
    </div>

    <div class="signup-right">
      <h2>Log In</h2>
      <p class="subtitle">Enter your credentials to access your account</p>

      <?php if (!empty($error)) { ?>
        <div class="form-message error-message"><?php echo htmlspecialchars($error); ?></div>
      <?php } ?>

      <form method="POST" class="signup-form">

        <div class="form-group">
          <label>Username</label>
          <div class="input-box">
            <i class="fa-regular fa-user"></i>
            <input type="text" name="username" placeholder="Enter your username"
            >
          </div>
        </div>

        <div class="form-group">
          <label>Password</label>
          <div class="input-box password-box">
            <i class="fa-solid fa-lock"></i>
            <input type="password" name="password" placeholder="Enter your password">
            <i class="fa-regular fa-eye eye-icon" id="loginTogglePassword"></i>
          </div>
        </div>

        <button type="submit" class="create-btn">Log In</button>

        <p class="signin-text">
          Don't have an account? <a href="SignUp.php">Sign Up</a>
        </p>

      </form>
    </div>

  </div>
</section>

<footer class="footer">
    <div class="footer-content">
        <div class="footer-section">
            <h4>Contact Us</h4>
            <p><i class="fas fa-envelope"></i> support@yumnak.com</p>
            <div class="social-icons">
                <a href="#"><i class="fa-brands fa-x-twitter"></i></a>
                <a href="#"><i class="fab fa-linkedin"></i></a>
                <a href="#"><i class="fab fa-instagram"></i></a>
            </div>
        </div>

        <div class="footer-section"></div>

        <div class="footer-section about-yumnak">
            <h4>About Yumnak</h4>
            <p>Your companion for a world without barriers, making every journey at the airport easier and more inclusive.</p>
        </div>
    </div>
    
    <div class="footer-bottom">
        &copy; 2026 Yumnak Platform. All rights reserved.
    </div>
</footer>

<script src="scriptD.js"></script>
</body>
</html>