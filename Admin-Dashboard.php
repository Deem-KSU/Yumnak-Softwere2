<?php
// Start the session and connect to the database 
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

if (!isset($_SESSION['admin_id'])) {
    header("Location: Index.php");
    exit();
}

$adminID = $_SESSION['admin_id'];

// Admin Details (Since we have 1 admin, we use AdminID = 1)
$adminQuery = "SELECT UserName, Phone, Email FROM ADMIN WHERE AdminID = 1";
$adminResult = mysqli_query($conn, $adminQuery);
$admin = mysqli_fetch_assoc($adminResult);

// Statistics 
// Total Requests
$totalQuery = "SELECT COUNT(*) AS count FROM ASSISTANCE_REQUEST";
$totalResult = mysqli_query($conn, $totalQuery);
$totalRequests = mysqli_fetch_assoc($totalResult)['count'];

// Pending Requests
$pendingQuery = "SELECT COUNT(*) AS count FROM ASSISTANCE_REQUEST WHERE Status = 'Pending'";
$pendingResult = mysqli_query($conn, $pendingQuery);
$pendingRequests = mysqli_fetch_assoc($pendingResult)['count'];

// Completed Requests
$completedQuery = "SELECT COUNT(*) AS count FROM ASSISTANCE_REQUEST WHERE Status = 'Completed'";
$completedResult = mysqli_query($conn, $completedQuery);
$completedRequests = mysqli_fetch_assoc($completedResult)['count'];

// Get Total Assistants
$assistantsQuery = "SELECT COUNT(*) AS count FROM ASSISTANT";
$assistantsResult = mysqli_query($conn, $assistantsQuery);
$totalAssistants = mysqli_fetch_assoc($assistantsResult)['count'];

// Calculate Completed Percentage
if ($totalRequests > 0) {
    $completedPercentage = round(($completedRequests / $totalRequests) * 100, 1);
} else {
    $completedPercentage = 0; 
}

$busyQuery = "
    SELECT COUNT(DISTINCT AssistantID) AS busy_count 
    FROM ASSISTANCE_REQUEST 
    WHERE Status = 'Accepted' 
    AND AssistantID IS NOT NULL 
    AND DATE(PreferredTime) = CURRENT_DATE()
";
$busyResult = mysqli_query($conn, $busyQuery);
$busyAssistants = mysqli_fetch_assoc($busyResult)['busy_count'];

// Math: Total Assistants minus Assistants booked for today
$availableAssistants = $totalAssistants - $busyAssistants;

$todayQuery = "SELECT COUNT(*) AS count FROM ASSISTANCE_REQUEST WHERE DATE(PreferredTime) = CURRENT_DATE()";
$todayResult = mysqli_query($conn, $todayQuery);
$requestsToday = mysqli_fetch_assoc($todayResult)['count'];
?>
<!DOCTYPE html>
<html lang="ar" dir="ltr">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Yumnak | Admin Dashboard</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
  <link rel="stylesheet" href="StyleR.css">
</head>

<body>

  <header>
    <div class="logo">
      <img src="Image/Yumnak-Logo.png" alt="Yumnak Logo">
    </div>
    <div class="logout">
      <button onclick="window.location.href='logout.php'">
        <i class="fas fa-sign-out-alt"></i>
        Logout
      </button>
    </div>
  </header>

  <div class="admin-container">
    <aside class="sidebar">
      <a href="Admin-Dashboard.php" class="sidebar-item active">
        <i class="fa-solid fa-chart-line"></i> Dashboard
      </a>
      <a href="request-management.php" class="sidebar-item">
        <i class="fa-solid fa-clipboard-list"></i> Request Management
      </a>
      <a href="Assistant_Management.php" class="sidebar-item">
        <i class="fa-solid fa-users"></i> Assistant Management
      </a>
      <a href="view-requests.php" class="sidebar-item">
        <i class="fa-solid fa-clock-rotate-left"></i> View Requests
      </a>
      <a href="Weekly_Performance.php" class="sidebar-item">
        <i class="fa-solid fa-file-lines"></i> Weekly Performance Report
      </a>
    </aside>
    <main class="admin-main">
      <section class="dashboard-section">
        <h1 class="dashboard-title" style="color: black;">Admin Dashboard</h1>
        <p class="dashboard-subtitle">Monitor and manage airport assistance operations</p>

        <div class="admin-profile-card">
          <img class="manager-photo"
            src="https://ui-avatars.com/api/?name=<?php echo urlencode($admin['UserName']); ?>&background=d1d5db&color=111827&size=128"
            alt="Admin Photo">

          <div class="manager-details">
            <h2><?php echo htmlspecialchars($admin['UserName']); /* htmlspecialchars prevents XSS */?></h2> 

            <div class="manager-contact-row">
              <div class="manager-contact">
                <div class="contact-icon-box">
                  <i class="fa-solid fa-phone"></i>
                </div>
                <div>
                  <span>Phone Number</span>
                  <p><?php echo htmlspecialchars($admin['Phone']); ?></p>
                </div>
              </div>

              <div class="manager-contact">
                <div class="contact-icon-box">
                  <i class="fa-solid fa-envelope"></i>
                </div>
                <div>
                  <span>Email Address</span>
                  <p><?php echo htmlspecialchars($admin['Email']); ?></p>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="stats-cards">
          <div class="stat-card">
            <div class="stat-card-top">
              <div class="stat-icon requests-icon">
                <i class="fa-solid fa-list-check"></i>
              </div>
              <span class="stat-tag green-tag">Today: +<?php echo $requestsToday; ?></span>
            </div>
            <h3><?php echo $totalRequests; ?></h3>
            <p>Total Requests</p>
          </div>

          <div class="stat-card">
            <div class="stat-card-top">
              <div class="stat-icon pending-icon">
                <i class="fa-regular fa-clock"></i>
              </div>
              <!--<span class="stat-tag orange-tag">Active</span>-->
            </div>
            <h3><?php echo $pendingRequests; ?></h3>
            <p>Pending Requests</p>
          </div>

          <div class="stat-card">
            <div class="stat-card-top">
              <div class="stat-icon completed-icon">
                <i class="fa-solid fa-circle-check"></i>
              </div>
              <span class="stat-tag green-tag"><?php echo $completedPercentage; ?>%</span>
            </div>
            <h3><?php echo $completedRequests; ?></h3>
            <p>Completed Requests</p>
          </div>

          <div class="stat-card">
            <div class="stat-card-top">
              <div class="stat-icon assistants-icon">
                <i class="fa-solid fa-user-group"></i>
              </div>
              <span class="stat-tag blue-tag"><?php echo $availableAssistants; ?> Available</span>
            </div>
            <h3><?php echo $totalAssistants; ?></h3>
            <p>Total Assistants</p>
          </div>
        </div>
      </section>
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
          <li><a href="sitemapAdmin.php">Sitemap</a></li>
          <li><a href="Admin-Dashboard.php">Dashboard</a></li>
        </ul>
      </div>

      <div class="footer-section about-yumnak">
        <h4>About Yumnak</h4>
        <p>Your companion for a world without barriers, making every journey at the airport easier and more inclusive.
        </p>
      </div>
    </div>

    <div class="footer-bottom">
      &copy; 2026 Yumnak Platform. All rights reserved.
    </div>
  </footer>

</body>

</html>