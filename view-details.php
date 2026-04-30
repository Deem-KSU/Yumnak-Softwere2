<?php
session_start();
include('db_connection.php');

if (!isset($_SESSION['admin_id'])) {
    header("Location: Index.php");
    exit();
}

if (!isset($_GET['id'])) {
    header("Location: view-requests.php");
    exit();
}

$requestID = $_GET['id'];

$query = "
SELECT 
    ar.RequestID,
    ar.Status,
    ar.IsPaid,
    ar.Date,
    ar.PreferredTime,
    ar.ExtraNote,
    t.UserName,
    t.Email,
    t.Phone,
    TIMESTAMPDIFF(YEAR, t.DOB, CURDATE()) AS Age,
    g.GateID,
    a.AirportName,
    GROUP_CONCAT(at.AssistanceName SEPARATOR ', ') AS AssistanceTypes,
   ass.Name AS AssistantName,
ass.Specialization AS AssistantSpecialization
FROM ASSISTANCE_REQUEST ar
JOIN TRAVELER t ON ar.TravelerID = t.UserID
JOIN GATE g ON ar.GateID = g.GateID
JOIN AIRPORT a ON g.AirportID = a.AirportID
LEFT JOIN REQUEST_TYPE rt ON ar.RequestID = rt.RequestID
LEFT JOIN ASSISTANCE_TYPE at ON rt.AssistanceTypeID = at.AssistanceTypeID
LEFT JOIN ASSISTANT ass ON ar.AssistantID = ass.AssistantID
WHERE ar.RequestID = ?
GROUP BY ar.RequestID
";

$stmt = $conn->prepare($query);
$stmt->bind_param("i", $requestID);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    echo "Request not found.";
    exit();
}

$data = $result->fetch_assoc();
?>

<!--Admin header + sidebar -->

<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Yumnak | Request Details </title>
    <style>
.assigned-assistant-info {
  display: flex;
  align-items: center;
  gap: 15px;
  padding: 25px;
  background: #ffffff;
}

.assigned-assistant-info img {
  width: 55px;
  height: 55px;
  border-radius: 50%;
}

.assigned-assistant-name {
  font-weight: 600;
  color: #111827;
  padding: 5px 0;
  margin: 0;
  font-size: 16px;
}

.assigned-assistant-info span {
  color: #6B7280;
  font-size: 14px;
}
</style>
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

        <!--main container-->
        <main class="main-content">
            <div class="headline">
                <h1>Request Details</h1>
                <p>Review full traveler request information and assign a suitable assistant</p>
            </div>
            <div class="cards-row">
                <div class="small-table-container">
                    <div class="small-table-header-title">
                        Request Summary
                    </div>
                    <table class="detail-table">
                        <tbody>
                            <tr>
                                <th>Request ID:</th>
                                <td><?php echo htmlspecialchars($data['RequestID']); ?></td>
                            </tr>
                            <tr>
                                <th>Request Status:</th>
                                <td>
                                    <span class="status <?php echo strtolower($data['Status']); ?>">
                                        <?php echo htmlspecialchars($data['Status']); ?>
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <th>Payment Status:</th>
                                <td>
                                    <span class="status <?php echo $data['IsPaid'] ? 'paid' : 'pending'; ?>">
                                        <?php echo $data['IsPaid'] ? 'Paid' : 'Not Paid'; ?>
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <th>Date Submitted:</th>
                                <td><?php echo htmlspecialchars($data['Date']); ?></td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="small-table-container">
                    <div class="small-table-header-title">
                        Traveler Information
                    </div>
                    <table class="detail-table">
                        <tbody>
                            <tr>
                                <th>UserName:</th>
                                <td><?php echo htmlspecialchars($data['UserName']); ?></td>
                            </tr>
                            <tr>
                                <th>Age:</th>
                                <td><?php echo htmlspecialchars($data['Age']); ?> years</td>
                            </tr>
                            <tr>
                                <th>Traveler Type:</th>
                                <td><span class="status adult">Adult</span></td>
                            </tr>
                            <tr>
                                <th>Phone Number:</th>
                                <td><?php echo htmlspecialchars($data['Phone']); ?></td>
                            </tr>
                            <tr>
                                <th>Email Address:</th>
                                <td><?php echo htmlspecialchars($data['Email']); ?></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="table-container full-width-card">
                <div class="small-table-header-title">
                    Assistance Request Details
                </div>
                <dl class="details-grid">
                    <div class="detail-block">
                        <dt>Airport:</dt>
                        <dd><?php echo htmlspecialchars($data['AirportName']); ?></dd>
                    </div>
                    <div class="detail-block">
                        <dt>Preferred Time:</dt>
                        <dd><?php echo htmlspecialchars($data['PreferredTime']); ?></dd>
                    </div>
                    <div class="detail-block">
                        <dt>Entrance Gate:</dt>
                        <dd><?php echo htmlspecialchars($data['GateID']); ?></dd>
                    </div>
                    <div class="detail-block">
                        <dt>Extra Note:</dt>
                        <dd>
                            <?php 
                            echo !empty($data['ExtraNote']) 
                                ? htmlspecialchars($data['ExtraNote']) 
                                : "No extra note provided."; 
                            ?>
                        </dd>
                    </div>
                    <div class="detail-block border-none">
                        <dt>Assistance Type:</dt>
                       <dd>
    <?php
    $types = explode(',', $data['AssistanceTypes'] ?? '');
    foreach ($types as $type) {
        if (trim($type) !== '') {
            echo '<span class="badge purple">' . htmlspecialchars(trim($type)) . '</span> ';
        }
    }
    ?>
</dd>
                    </div>
                    <div class="detail-block border-none">
                        <dt>Preferred date:</dt>
                        <dd><?php echo date("F d, Y", strtotime($data['PreferredTime'])); ?></dd>
                    </div>
                </dl>
            </div>

            <div class="table-container full-width-card">
                <div class="small-table-header-title">
                    Assigned Assistant
                </div>

             <?php if (!empty($data['AssistantName'])) { ?>
    <div class="assigned-assistant-info">
    <img src="https://ui-avatars.com/api/?name=<?php echo urlencode($data['AssistantName']); ?>&background=random" alt="Assigned Assistant">

    <div>
        <p class="assigned-assistant-name"><?php echo htmlspecialchars($data['AssistantName']); ?></p>
        <span><?php echo htmlspecialchars($data['AssistantSpecialization']); ?></span>
    </div>
</div>
<?php } else { ?>
    <div class="empty-state-container">
        <p class="empty-title">No assistant assigned yet.</p>
        <p class="empty-subtitle">Please assign a suitable assistant.</p>
    </div>
<?php } ?>
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