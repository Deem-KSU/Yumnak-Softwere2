<?php
session_start();
require 'db_connection.php';

// Query to fetch ONLY Pending requests
// We use JOIN to connect the request to the Gate, and then the Gate to the Airport
$sql = "SELECT ar.RequestID, ar.PreferredTime, ar.Status, 
               GROUP_CONCAT(at.AssistanceName SEPARATOR ', ') AS AssistanceName, 
               a.AirportName 
        FROM ASSISTANCE_REQUEST ar
        JOIN GATE g ON ar.GateID = g.GateID
        JOIN AIRPORT a ON g.AirportID = a.AirportID
        JOIN REQUEST_TYPE rt ON ar.RequestID = rt.RequestID
        JOIN ASSISTANCE_TYPE at ON rt.AssistanceTypeID = at.AssistanceTypeID
        WHERE ar.Status = 'Pending'
        GROUP BY ar.RequestID, ar.PreferredTime, ar.Status, a.AirportName
        ORDER BY ar.PreferredTime ASC";

$result = mysqli_query($conn, $sql);
?>
<!DOCTYPE html>
<html lang="ar" dir="ltr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Yumnak | Request Management </title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="styleF.css">
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
            <a href="request-management.html" class="sidebar-item active">
                <i class="fa-solid fa-clipboard-list"></i> Request Management
            </a>
            <a href="Assistant_Management.html" class="sidebar-item">
                <i class="fa-solid fa-users"></i> Assistant Management
            </a>
            <a href="view-requests.html" class="sidebar-item">
                <i class="fa-solid fa-clock-rotate-left"></i> View Requests
            </a>
            <a href="Weekly_Performance.html" class="sidebar-item">
                <i class="fa-solid fa-file-lines"></i> Weekly Performance Report
            </a>
        </aside>

        <!--main container-->
        <main class="main-content">
            <div class="headline">
                <h1>Request management</h1>
                <p>manage assistance requests.</p>
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
                        <?php 
                        // 2. Check if there are any pending requests
                        if (mysqli_num_rows($result) > 0): 
                            // 3. Loop through the database results and create a table row for each one
                            while($row = mysqli_fetch_assoc($result)): 
                        ?>
                            <tr>
                                <td class="reqNO"><?php echo htmlspecialchars($row['RequestID']); ?></td>
                                <td><?php echo htmlspecialchars($row['AirportName']); ?></td>
                                
                                <td><?php echo htmlspecialchars($row['AssistanceName']); ?></td>
                                
                                <td><?php echo date('M j, Y', strtotime($row['PreferredTime'])); ?></td>
                                
                                <td><span class="status pending"><?php echo htmlspecialchars($row['Status']); ?></span></td>
                                
                                <td><a href="handeling.php?id=<?php echo urlencode($row['RequestID']); ?>">Handle</a></td>
                            </tr>
                        <?php 
                            endwhile; 
                        else: 
                        ?>
                            <tr>
                                <td colspan="6" style="text-align: center; padding: 20px;">No pending requests at this time. Great job!</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </main>


        <!--footer-->
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
                <p>Your companion for a world without barriers, making every journey at the airport easier and more
                    inclusive.</p>
            </div>
        </div>
        

        <div class="footer-bottom">
            &copy; 2026 Yumnak Platform. All rights reserved.
        </div>
    </footer>
</body>