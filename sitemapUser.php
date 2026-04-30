<?php
session_start();

$dashboardLink = "User-Dashboard.php";
?>

<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Yumnak | Sitemap</title>
<link rel="stylesheet" href="StyleD.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

<style>
    .sitemap-page {
        min-height: 70vh;
        background: #F5EFE8;
        padding: 50px 8%;
        text-align: center;
    }

    .sitemap-page h1 {
        color: #8B6B4A;
        font-size: 34px;
        margin-bottom: 10px;
    }

    .sitemap-page p {
        color: #5F5F5F;
        font-size: 17px;
        margin-bottom: 35px;
    }

    .sitemap-container {
        background: #fff;
        border-radius: 18px;
        padding: 25px;
        box-shadow: 0 8px 25px rgba(0,0,0,0.08);
        max-width: 1000px;
        margin: auto;
    }

    .sitemap-container img {
        width: 100%;
        max-width: 900px;
        height: auto;
        border-radius: 12px;
        border: 1px solid #e5d8ca;
    }

    .back-dashboard-btn {
        display: inline-block;
        margin-top: 25px;
        padding: 12px 28px;
        background-color: #8B6B4A;
        color: white;
        text-decoration: none;
        border-radius: 10px;
        font-weight: 600;
    }

    .back-dashboard-btn:hover {
        background-color: #755F47;
    }
</style>
</head>

<body>

<header>
    <div class="logo">
      <img src="Image/Yumnak-Logo.png" alt="Yumnak Logo">
    </div>

    <nav>
      <a href="<?php echo $dashboardLink; ?>">Home</a>
      <a href="Request-Management.php">Request Management</a>
      <a class="active" href="Sitemap.php">Sitemap</a>
    </nav>

    <div class="logout">
        <button onclick="window.location.href='logout.php'">
            <i class="fas fa-sign-out-alt"></i>
            Logout
        </button>
    </div>
</header>

<main class="sitemap-page">
    <h1>Sitemap</h1>
    <p>Explore the main pages and structure of the Yumnak platform.</p>

    <div class="sitemap-container">
        <img src="Image/Sitemap.png" alt="Yumnak Sitemap">
    </div>

    <a class="back-dashboard-btn" href="<?php echo $dashboardLink; ?>">
        Back to Dashboard
    </a>
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
                <li><a href="SitemapUser.php">Sitemap</a></li>
                <li><a href="<?php echo $dashboardLink; ?>">Dashboard</a></li>
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