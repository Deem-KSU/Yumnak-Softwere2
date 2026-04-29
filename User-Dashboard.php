<?php
session_start();
$timeout = 900;

if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity']) > $timeout) {
    session_unset();
    session_destroy();
    header("Location: Index.php?msg=timeout");
    exit();
}

$_SESSION['last_activity'] = time();
require 'db_connection.php';


if (!isset($_SESSION['user_id']) || !isset($_SESSION['role']) || $_SESSION['role'] != "user") {
    header("Location: Index.php");
    exit();
}

$userID = $_SESSION['user_id'];

$stmt = $conn->prepare("SELECT UserName, Email FROM TRAVELER WHERE UserID = ?");
$stmt->bind_param("i", $userID);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows != 1) {
    session_unset();
    session_destroy();
    header("Location: Index.php");
    exit();
}

$user = $result->fetch_assoc();
$username = $user['UserName'];
$email = $user['Email'];
?>

<!DOCTYPE html>
<html lang="ar" dir="ltr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Yumnak | Dashboard</title>
<link rel="stylesheet" href="StyleD.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>

<body>

<header>
    <div class="logo">
      <img src="Image/Yumnak-Logo.png" alt="Yumnak Logo">
    </div>

    <nav>
      <a class="active" href="User-Dashboard.php">Home</a>
      <a href="my-requests.php">My Requests</a>
    </nav>

    <div class="logout">
        <button onclick="window.location.href='logout.php'">
            <i class="fas fa-sign-out-alt"></i>
            Logout
        </button>
    </div>
</header>

<main class="dashboard-page">
    <section class="dashboard-hero">
        <div class="hero-left">
            <h1>Welcome back, <span><?php echo htmlspecialchars($username); ?></span></h1>

            <p class="hero-text">
                Request professional airport assistance easily and confidently.
                We're here to make your journey smooth, comfortable, and stress-free.
            </p>

            <div class="user-summary">
                <div class="summary-item">
                    <i class="fa-solid fa-user"></i>
                    <div>
                        <small>Username</small>
                        <p><?php echo htmlspecialchars($username); ?></p>
                    </div>
                </div>

                <div class="summary-item">
                    <i class="fa-solid fa-envelope"></i>
                    <div>
                        <small>Email</small>
                        <p><?php echo htmlspecialchars($email); ?></p>
                    </div>
                </div>
            </div>

            <button class="main-request-btn" onclick="window.location.href='Airport_Selection.php'">
                <i class="fa-solid fa-circle-plus"></i>
                Add Request
            </button>
        </div>

        <div class="hero-right">
            <div class="support-card-bg"></div>
            <div class="support-card">
                <div class="support-icon">
                    <i class="fa-solid fa-wheelchair-move"></i>
                </div>
                <h3>Professional Support</h3>
                <p>Dedicated assistance tailored to your needs at every airport</p>
            </div>
        </div>
    </section>

    <section class="quick-actions-section">
        <h2>Quick Actions</h2>

        <div class="quick-actions-grid">

            <a href="Airport_Selection.php" class="action-card">
                <div class="action-icon">
                    <i class="fa-solid fa-plus"></i>
                </div>
                <h3>Add Request</h3>
                <p>Submit a new assistance request for your upcoming flight</p>
            </a>

            <a href="my-requests.php" class="action-card">
                <div class="action-icon">
                    <i class="fa-solid fa-list-check"></i>
                </div>
                <h3>My Requests</h3>
                <p>View and manage all your assistance requests</p>
            </a>

        </div>
    </section>

    <section class="why-yumnak-section">
        <h2>Why Choose Yumnak</h2>

        <div class="features-grid">
            <div class="feature-box">
                <div class="feature-icon">
                    <i class="fa-solid fa-clock"></i>
                </div>
                <h3>Easy Booking</h3>
                <p>Request airport assistance in just a few clicks, anytime, anywhere</p>
            </div>

            <div class="feature-box">
                <div class="feature-icon">
                    <i class="fa-solid fa-heart"></i>
                </div>
                <h3>Personalized Support</h3>
                <p>Tailored assistance that meets your specific needs and preferences</p>
            </div>

            <div class="feature-box">
                <div class="feature-icon">
                    <i class="fa-solid fa-shield-halved"></i>
                </div>
                <h3>Reliable Service</h3>
                <p>Professional, organized, and dependable support every step of the way</p>
            </div>
        </div>
    </section>

    <section class="how-it-works-section">
        <h2>How It Works</h2>

        <div class="steps-grid">
            <div class="step-card">
                <div class="step-number">1</div>
                <h3>Create Request</h3>
                <p>Fill out a simple form with your flight details and assistance needs</p>
            </div>

            <div class="step-card">
                <div class="step-number">2</div>
                <h3>Get Confirmation</h3>
                <p>Receive instant confirmation and details about your assistance booking</p>
            </div>

            <div class="step-card">
                <div class="step-number">3</div>
                <h3>Travel with Ease</h3>
                <p>Enjoy professional support throughout your airport journey</p>
            </div>
        </div>
    </section>
</main>

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

        <div class="footer-section">
            <h4>Quick Links</h4>
            <ul class="footer-links">
                <li><a href="#">Sitemap</a></li>
                <li><a href="User-Dashboard.php">Dashboard</a></li>
            </ul>
        </div>

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