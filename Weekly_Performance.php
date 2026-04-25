<!DOCTYPE html>
<html lang="ar" dir="ltr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Yumnak | Weekly Performance</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
<link rel="stylesheet" href="StyleM.css">
</head>

<body>
<header>
    <div class="logo">
        <img src="Image/Yumnak-Logo.png" alt="Yumnak Logo">
    </div>
    <div class="logout">
        <button onclick="window.location.href='LogIn.html'">
            <i class="fas fa-sign-out-alt"></i>
            Logout
        </button>
    </div>
</header>
<div class="container">
            <aside class="sidebar">
    <a href="Admin-Dashboard.html" class="sidebar-item">
        <i class="fa-solid fa-chart-line"></i> Dashboard
    </a>
    <a href="request-management.html" class="sidebar-item">
        <i class="fa-solid fa-clipboard-list"></i> Request Management
    </a>
    <a href="Assistant_Management.html" class="sidebar-item">
        <i class="fa-solid fa-users"></i> Assistant Management
    </a>
    <a href="view-requests.html" class="sidebar-item">
        <i class="fa-solid fa-clock-rotate-left"></i> View Requests
    </a>
    <a href="Weekly_Performance.html" class="sidebar-item active">
        <i class="fa-solid fa-file-lines"></i> Weekly Performance Report
    </a>
</aside>
<main class="main-content">
    <div class="headline">
        <h1>Weekly Performance</h1>
        <p>Manage assistants, Monitor their availability, and review their performance.</p>
    </div>

    <div class="performance-grid">
        <div class="performance-card">
            <div class="card-header">
                <img src="https://ui-avatars.com/api/?name=M+U&background=random" alt="Maryam">
                <div class="user-meta">
                    <h3>Maryam Usama</h3>
                    <span>ID: AS001</span>
                </div>
            </div>
            <div class="card-body">
                <p><i class="fa-solid fa-phone"></i> +966 50 022 651</p>
                <p><i class="fa-solid fa-envelope"></i> Maryu1123@yumnak.com</p>
                <p><i class="fa-solid fa-user-tag"></i> Wheelchair Assistance</p>
                
                <div class="stats-row">
                    <span>Average Rating:</span>
                    <span class="rating"><i class="fa-solid fa-star"></i> 4.9</span>
                </div>
                <div class="stats-row">
                    <span>Completed Requests This Week:</span>
                    <span class="count">14 Requests</span>
                </div>
            </div>
            <a href="Maryam_Performance.html" class="btn-view-performance">View performance</a>
        </div>

        <div class="performance-card">
            <div class="card-header">
                <img src="https://ui-avatars.com/api/?name=L+J&background=random" alt="Latifah">
                <div class="user-meta">
                    <h3>Latifah jassir</h3>
                    <span>ID: AS002</span>
                </div>
            </div>
            <div class="card-body">
                <p><i class="fa-solid fa-phone"></i> +966 55 867 221</p>
                <p><i class="fa-solid fa-envelope"></i> Tetobe1@yumnak.com</p>
                <p><i class="fa-solid fa-user-tag"></i> Language Support</p>
                
                <div class="stats-row">
                    <span>Average Rating:</span>
                    <span class="rating"><i class="fa-solid fa-star"></i> 4.7</span>
                </div>
                <div class="stats-row">
                    <span>Completed Requests This Week:</span>
                    <span class="count">9 Requests</span>
                </div>
            </div>
            <a href="Maryam_Performance.html" class="btn-view-performance">View performance</a>
        </div>

        <div class="performance-card">
            <div class="card-header">
                <img src="https://ui-avatars.com/api/?name=K+T&background=random" alt="Khalid">
                <div class="user-meta">
                    <h3>Khalid Altmimi</h3>
                    <span>ID: AS003</span>
                </div>
            </div>
            <div class="card-body">
                <p><i class="fa-solid fa-phone"></i> +966 53 222 145</p>
                <p><i class="fa-solid fa-envelope"></i> Khalid@yumnak.com</p>
                <p><i class="fa-solid fa-user-tag"></i> Navigation Guide</p>
                
                <div class="stats-row">
                    <span>Average Rating:</span>
                    <span class="rating"><i class="fa-solid fa-star"></i> 4.6</span>
                </div>
                <div class="stats-row">
                    <span>Completed Requests This Week:</span>
                    <span class="count">12 Requests</span>
                </div>
            </div>
            <a href="Maryam_Performance.html" class="btn-view-performance">View performance</a>
        </div>
    </div>
</main>
</div>
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
                <li><a href="Admin-Dashboard.html">Dashboard</a></li>
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
</body>
</html>