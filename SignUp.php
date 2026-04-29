<?php
session_start();
include('db_connection.php');

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $dob = $_POST['dob'];
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirmPassword'];

    if (empty($username) || empty($email) || empty($phone) || empty($dob) || empty($password) || empty($confirmPassword)) {
        $error = "All fields are required.";

    } elseif (!preg_match("/^[A-Za-z0-9_ ]{3,100}$/", $username)) {
        $error = "Username must be at least 3 characters.";

    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email format.";

    } elseif (!preg_match("/^05[0-9]{8}$/", $phone)) {
        $error = "Phone must start with 05 and be 10 digits.";

    } elseif (new DateTime($dob) > new DateTime()) {
        $error = "Date of birth cannot be in the future.";

    } else {

        $birthDate = new DateTime($dob);
        $today = new DateTime();
        $age = $today->diff($birthDate)->y;

        if ($age < 18) {
            $error = "You must be at least 18 years old.";

        } elseif (!preg_match("/^(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}$/", $password)) {
            $error = "Password must be at least 8 characters and include uppercase, number, and special character.";

        } elseif ($password !== $confirmPassword) {
            $error = "Passwords do not match.";

        } else {

            $stmt = $conn->prepare("SELECT UserID FROM TRAVELER WHERE UserName = ? OR Email = ?");
            $stmt->bind_param("ss", $username, $email);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $error = "Username or Email already exists.";
            } else {
                $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

                $stmt = $conn->prepare("INSERT INTO TRAVELER (UserName, Email, Phone, Password, DOB) VALUES (?, ?, ?, ?, ?)");
                $stmt->bind_param("sssss", $username, $email, $phone, $hashedPassword, $dob);

                if ($stmt->execute()) {
                    header("Location: LogIn.php?success=1");
                    exit();
                } else {
                    $error = "Something went wrong.";
                }
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="ar" dir="ltr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Yumnak | Sign Up</title>
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
        <img src="Image/Yumnak-Logo.png" alt="Yumnak Logo" class="signup-image">
        <h2>Join Yumnak</h2>
        <p>Create your account to request airport assistance easily.</p>
      </div>
    </div>

    <div class="signup-right">
      <h2>Create Account</h2>
      <p class="subtitle">Enter your details to get started</p>

      <?php if (!empty($error)) { ?>
        <div class="form-message error-message"><?php echo $error; ?></div>
      <?php } ?>

      <form class="signup-form" id="signupForm" method="POST" action="SignUp.php" novalidate>

        <div class="form-group">
          <label for="username">Username</label>
          <div class="input-box">
            <i class="fa-regular fa-user"></i>
            <input type="text" id="username" name="username" placeholder="Enter your username"
            value="<?php echo isset($username) ? htmlspecialchars($username) : ''; ?>">
          </div>
          <small class="field-error" id="usernameError"></small>
        </div>

        <div class="form-group">
          <label for="email">Email Address</label>
          <div class="input-box">
            <i class="fa-regular fa-envelope"></i>
            <input type="email" id="email" name="email" placeholder="Enter your email"
            value="<?php echo isset($email) ? htmlspecialchars($email) : ''; ?>">
          </div>
          <small class="field-error" id="emailError"></small>
        </div>

        <div class="form-group">
          <label for="phone">Phone Number</label>
          <div class="input-box">
            <i class="fa-solid fa-phone"></i>
            <input type="text" id="phone" name="phone" placeholder="Enter your phone number"
            value="<?php echo isset($phone) ? htmlspecialchars($phone) : ''; ?>">
          </div>
          <small class="field-error" id="phoneError"></small>
        </div>

        <div class="form-group">
          <label for="dob">Date of Birth</label>
          <div class="input-box">
            <input type="date" id="dob" name="dob"
            value="<?php echo isset($dob) ? htmlspecialchars($dob) : ''; ?>">
          </div>
          <small class="field-error" id="dobError"></small>
        </div>

        <div class="form-group">
          <label for="password">Password</label>
          <div class="input-box password-box">
            <i class="fa-solid fa-lock"></i>
            <input type="password" id="password" name="password" placeholder="Enter your password">
            <i class="fa-regular fa-eye eye-icon" id="togglePassword"></i>
          </div>
          <small class="field-error" id="passwordError"></small>
        </div>

        <div class="form-group">
          <label for="confirmPassword">Confirm Password</label>
          <div class="input-box password-box">
            <i class="fa-solid fa-lock"></i>
            <input type="password" id="confirmPassword" name="confirmPassword" placeholder="Confirm your password">
            <i class="fa-regular fa-eye eye-icon" id="toggleConfirmPassword"></i>
          </div>
          <small class="field-error" id="confirmPasswordError"></small>
        </div>

        <button type="submit" class="create-btn">Create Account</button>

        <p class="signin-text">
          Already have an account? <a href="LogIn.php">Sign In</a>
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