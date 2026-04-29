<?php
session_start();
include('db_connection.php');

if (!isset($_SESSION['admin_id']) || !isset($_SESSION['role']) || $_SESSION['role'] != "admin") {
    header("Location: LogIn.php");
    exit();
}

$query = "
    SELECT 
        ar.RequestID,
        a.AirportName,
        GROUP_CONCAT(at.AssistanceName SEPARATOR ', ') AS AssistanceTypes,
        ar.Date,
        ar.Status
    FROM ASSISTANCE_REQUEST ar
    JOIN GATE g ON ar.GateID = g.GateID
    JOIN AIRPORT a ON g.AirportID = a.AirportID
    LEFT JOIN REQUEST_TYPE rt ON ar.RequestID = rt.RequestID
    LEFT JOIN ASSISTANCE_TYPE at ON rt.AssistanceTypeID = at.AssistanceTypeID
    GROUP BY ar.RequestID, a.AirportName, ar.Date, ar.Status
    ORDER BY ar.RequestID DESC
";

$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="ar" dir="ltr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Yumnak | View Requests</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="styleF.css">
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

    <div class="container">
        <aside class="sidebar">
            <a href="Admin-Dashboard.php" class="sidebar-item">
                <i class="fa-solid fa-chart-line"></i> Dashboard
            </a>

            <a href="request-management.php" class="sidebar-item">
                <i class="fa-solid fa-clipboard-list"></i> Request Management
            </a>

            <a href="Assistant_Management.php" class="sidebar-item">
                <i class="fa-solid fa-users"></i> Assistant Management
            </a>

            <a href="view-requests.php" class="sidebar-item active">
                <i class="fa-solid fa-clock-rotate-left"></i> View Requests
            </a>

            <a href="Weekly_Performance.php" class="sidebar-item">
                <i class="fa-solid fa-file-lines"></i> Weekly Performance Report
            </a>
        </aside>

        <main class="main-content">
            <div class="headline">
                <h1>View Requests</h1>
                <p>Monitor airport assistance operations</p>
            </div>

            <div class="table-container">
                <div class="table-header-title">Recent Requests</div>

                <table>
                    <thead>
                        <tr>
                            <th>REQUEST ID</th>
                            <th>AIRPORT</th>
                            <th>ASSISTANCE TYPE</th>
                            <th>DATE</th>
                            <th>STATUS</th>
                            <th>ACTION</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php if ($result && mysqli_num_rows($result) > 0) { ?>
                            <?php while ($row = mysqli_fetch_assoc($result)) { 
                                $statusClass = strtolower($row['Status']);
                            ?>
                                <tr>
                                    <td class="reqNO"><?php echo htmlspecialchars($row['RequestID']); ?></td>

                                    <td><?php echo htmlspecialchars($row['AirportName']); ?></td>

                                    <td>
                                        <?php 
                                        echo !empty($row['AssistanceTypes']) 
                                            ? htmlspecialchars($row['AssistanceTypes']) 
                                            : "Not specified"; 
                                        ?>
                                    </td>

                                    <td><?php echo date("M d, Y", strtotime($row['Date'])); ?></td>

                                    <td>
                                        <span class="status <?php echo htmlspecialchars($statusClass); ?>">
                                            <?php echo htmlspecialchars($row['Status']); ?>
                                        </span>
                                    </td>

                                    <td>
                                        <a href="view-details.php?id=<?php echo htmlspecialchars($row['RequestID']); ?>">
                                            View
                                        </a>
                                    </td>
                                </tr>
                            <?php } ?>
                        <?php } else { ?>
                            <tr>
                                <td colspan="6">No requests found.</td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
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
                    <li><a href="Admin-Dashboard.php">Dashboard</a></li>
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