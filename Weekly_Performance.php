<?php
$host = 'localhost:8889';
$db = 'YumnakDB';
$user = 'root';
$pass = 'root';

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$sql = "SELECT a.AssistantID, a.Name, a.Phone, a.Email, a.Specialization,
        (SELECT COUNT(*) FROM ASSISTANCE_REQUEST ar WHERE ar.AssistantID = a.AssistantID AND ar.Status = 'Completed') AS CompletedCount,
        (SELECT ROUND(AVG(r.Stars), 1) FROM REVIEW r JOIN ASSISTANCE_REQUEST ar ON r.RequestID = ar.RequestID WHERE ar.AssistantID = a.AssistantID) AS AvgRating
        FROM ASSISTANT a";
$result = $conn->query($sql);
?>
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
    <a href="Assistant_Management.php" class="sidebar-item">
        <i class="fa-solid fa-users"></i> Assistant Management
    </a>
    <a href="view-requests.html" class="sidebar-item">
        <i class="fa-solid fa-clock-rotate-left"></i> View Requests
    </a>
    <a href="Weekly_Performance.php" class="sidebar-item active">
        <i class="fa-solid fa-file-lines"></i> Weekly Performance Report
    </a>
</aside>
<main class="main-content">
    <div class="headline">
        <h1>Weekly Performance</h1>
        <p>Manage assistants, Monitor their availability, and review their performance.</p>
    </div>

    <div class="performance-grid">
            <?php 
            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) { 
                    $avatarUrl = "https://ui-avatars.com/api/?name=" . urlencode($row['Name']) . "&background=random";
                    $rating = $row['AvgRating'] ? $row['AvgRating'] : "0.0"; // إذا مافي تقييم يحط 0
            ?>
            <div class="performance-card">
                <div class="card-header">
                    <img src="<?php echo $avatarUrl; ?>" alt="Avatar">
                    <div class="user-meta">
                        <h3><?php echo htmlspecialchars($row['Name']); ?></h3>
                        <span>ID: AS00<?php echo htmlspecialchars($row['AssistantID']); ?></span>
                    </div>
                </div>
                <div class="card-body">
                    <p><i class="fa-solid fa-phone"></i> <?php echo htmlspecialchars($row['Phone']); ?></p>
                    <p><i class="fa-solid fa-envelope"></i> <?php echo htmlspecialchars($row['Email']); ?></p>
                    <p><i class="fa-solid fa-user-tag"></i> <?php echo htmlspecialchars($row['Specialization']); ?></p>
                    
                    <div class="stats-row">
                        <span>Average Rating:</span>
                        <span class="rating"><i class="fa-solid fa-star"></i> <?php echo $rating; ?></span>
                    </div>
                    <div class="stats-row">
                        <span>Completed Requests:</span>
                        <span class="count"><?php echo $row['CompletedCount']; ?> Requests</span>
                    </div>
                </div>
                <a href="Assistant_profile.php?id=<?php echo $row['AssistantID']; ?>" class="btn-view-performance">View performance</a>
            </div>
            <?php 
                }
            } else {
                echo "<p>No assistants found in the system.</p>";
            }
            ?>
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