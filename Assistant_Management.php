<?php
/*
session_start();
if (!isset($_SESSION[''])!== ) {
    header("Location: LogIn.html");
    exit(); 
} */
require 'db_connection.php';

if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];
    $stmt = $conn->prepare("DELETE FROM ASSISTANT WHERE AssistantID = ?");
    $stmt->bind_param("i", $delete_id);
    if ($stmt->execute()) {
        echo "<script>alert('The Assistant Deleted Successfully'); window.location.href='Assistant_Management.php';</script>";
    }
    $stmt->close();
}

$result = $conn->query("SELECT * FROM ASSISTANT");
?>
<!DOCTYPE html>
<html lang="ar" dir="ltr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Yumnak | Assistant Management</title>
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
                <i class="fas fa-sign-out-alt"></i> Logout
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
    <a href="Assistant_Management.php" class="sidebar-item active">
        <i class="fa-solid fa-users"></i> Assistant Management
    </a>
    <a href="view-requests.php" class="sidebar-item">
        <i class="fa-solid fa-clock-rotate-left"></i> View Requests
    </a>
    <a href="Weekly_Performance.php" class="sidebar-item">
        <i class="fa-solid fa-file-lines"></i> Weekly Performance Report
    </a>
</aside>

        <main class="main-content">
            <div class="headline">
                <h1>Assistant Management</h1>
                <p>Manage assistants, Monitor their availability.</p>
            </div>

            <div class="action-bar">
                <div class="search-container">
                 <i class="fa-solid fa-magnifying-glass"></i>
                 <input type="text" id="searchInput" placeholder="Search assistant by name or specialization">
            </div>
                <button class="btn-add" onclick="window.location.href='Add_Assistant.php'"><i class="fa-solid fa-plus"></i> Add Assistant</button>
            </div>

            <div class="table-container">
                <div class="table-header-title">All Assistants</div>
                <table>
                    <thead>
                        <tr>
                            <th>ASSISTANT</th>
                            <th>CONTACT</th>
                            <th>SPECIALIZATION</th>
                            <th>DELETE</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        if ($result->num_rows > 0) {
                            while($row = $result->fetch_assoc()) { 
                                $avatarUrl = "https://ui-avatars.com/api/?name=" . urlencode($row['Name']) . "&background=random";
                        ?>
                        <tr>
                            <td>
                                <div class="user-info">
                                    <img src="<?php echo $avatarUrl; ?>" alt="Avatar">
                                    <div>
                                        <div class="name"><?php echo htmlspecialchars($row['Name']); ?></div>
                                        <div class="id">ID: AS00<?php echo htmlspecialchars($row['AssistantID']); ?></div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="contact-info">
                                    <span><?php echo htmlspecialchars($row['Phone']); ?></span>
                                    <span class="email"><?php echo htmlspecialchars($row['Email']); ?></span>
                                </div>
                            </td>
                            <td><?php echo htmlspecialchars($row['Specialization']); ?></td>
                            <td>
                                <a href="?delete_id=<?php echo $row['AssistantID']; ?>" class="btn-delete" onclick="return confirm('Are You Sure You Want To Delete This Assistant?');">
                                    <i class="fa-solid fa-trash-can"></i>
                                </a>
                            </td>
                        </tr>
                        <?php 
                            }
                        } else {
                            echo "<tr><td colspan='4'>No Assistants Found.</td></tr>";
                        }
                        ?>
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
<script>
    document.getElementById('searchInput').addEventListener('keyup', function() {
        let filter = this.value.toLowerCase(); 
        let rows = document.querySelectorAll('.table-container tbody tr'); 

        rows.forEach(row => {
            
            if(row.querySelector('.name')) {
                let name = row.querySelector('.name').textContent.toLowerCase();
                let specialization = row.querySelectorAll('td')[2].textContent.toLowerCase();
                if (name.includes(filter) || specialization.includes(filter)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            }
        });
    });
</script>
</body>
</html>