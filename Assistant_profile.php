<?php
$host = 'localhost:8889';
$db = 'YumnakDB';
$user = 'root';
$pass = 'root';

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// التأكد من وجود ID في الرابط
$assistant_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($assistant_id == 0) {
    echo "<script>alert('Invalid Assistant ID'); window.location.href='Weekly_Performance.php';</script>";
    exit();
}

// جلب معلومات المساعد الأساسية
$stmt = $conn->prepare("SELECT * FROM ASSISTANT WHERE AssistantID = ?");
$stmt->bind_param("i", $assistant_id);
$stmt->execute();
$assistant_result = $stmt->get_result();

if ($assistant_result->num_rows == 0) {
    echo "<script>alert('Assistant not found'); window.location.href='Weekly_Performance.php';</script>";
    exit();
}
$assistant = $assistant_result->fetch_assoc();

// جلب الإحصائيات
$stats_sql = "SELECT
    (SELECT COUNT(*) FROM ASSISTANCE_REQUEST WHERE AssistantID = $assistant_id AND Status = 'Completed') AS CompletedCount,
    (SELECT COUNT(*) FROM ASSISTANCE_REQUEST WHERE AssistantID = $assistant_id AND Status IN ('Pending', 'Accepted')) AS AssignedCount,
    (SELECT ROUND(AVG(r.Stars), 1) FROM REVIEW r JOIN ASSISTANCE_REQUEST ar ON r.RequestID = ar.RequestID WHERE ar.AssistantID = $assistant_id) AS AvgRating";
$stats_result = $conn->query($stats_sql);
$stats = $stats_result->fetch_assoc();
$rating = $stats['AvgRating'] ? $stats['AvgRating'] : "0.0";

// جلب التقييمات
$reviews_sql = "SELECT r.Stars, r.Comment, r.Date, ar.RequestID, t.UserName 
                FROM REVIEW r 
                JOIN ASSISTANCE_REQUEST ar ON r.RequestID = ar.RequestID 
                JOIN TRAVELER t ON ar.TravelerID = t.UserID 
                WHERE ar.AssistantID = $assistant_id";
$reviews_result = $conn->query($reviews_sql);

// جلب الطلبات المسندة
$requests_sql = "SELECT ar.RequestID, a.AirportName, ar.Date, ar.Status, aty.AssistanceName 
                 FROM ASSISTANCE_REQUEST ar 
                 JOIN GATE g ON ar.GateID = g.GateID 
                 JOIN AIRPORT a ON g.AirportID = a.AirportID 
                 JOIN REQUEST_TYPE rt ON ar.RequestID = rt.RequestID 
                 JOIN ASSISTANCE_TYPE aty ON rt.AssistanceTypeID = aty.AssistanceTypeID
                 WHERE ar.AssistantID = $assistant_id 
                 ORDER BY ar.Date DESC";
$requests_result = $conn->query($requests_sql);
?>
<!DOCTYPE html>
<html lang="ar" dir="ltr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Yumnak | <?php echo htmlspecialchars($assistant['Name']); ?></title>
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
        <a href="Admin-Dashboard.html" class="sidebar-item"><i class="fa-solid fa-chart-line"></i> Dashboard</a>
        <a href="request-management.html" class="sidebar-item"><i class="fa-solid fa-clipboard-list"></i> Request Management</a>
        <a href="Assistant_Management.php" class="sidebar-item"><i class="fa-solid fa-users"></i> Assistant Management</a>
        <a href="view-requests.html" class="sidebar-item"><i class="fa-solid fa-clock-rotate-left"></i> View Requests</a>
        <a href="Weekly_Performance.php" class="sidebar-item active"><i class="fa-solid fa-file-lines"></i> Weekly Performance Report</a>
    </aside>
    <main class="main-content">
        <div class="headline">
            <h1>Assistant Details</h1>
            <p>View assistant information, performance, and user feedback.</p>
        </div>

        <div class="profile-header-card">
            <img src="https://ui-avatars.com/api/?name=<?php echo urlencode($assistant['Name']); ?>&background=8B6B4A&color=fff" alt="Profile" class="main-avatar">
            <div class="profile-info-grid">
                <div class="name-section">
                    <h2><?php echo htmlspecialchars($assistant['Name']); ?></h2>
                    <span class="id-tag">Assistant ID: AS00<?php echo $assistant['AssistantID']; ?></span>
                </div>
                <div class="contact-details-row">
                    <div class="detail-item">
                        <i class="fa-solid fa-phone"></i>
                        <div><label>Phone Number</label><p><?php echo htmlspecialchars($assistant['Phone']); ?></p></div>
                    </div>
                    <div class="detail-item">
                        <i class="fa-solid fa-envelope"></i>
                        <div><label>Email Address</label><p><?php echo htmlspecialchars($assistant['Email']); ?></p></div>
                    </div>
                    <div class="detail-item">
                        <i class="fa-solid fa-briefcase"></i>
                        <div><label>Specialization</label><p><?php echo htmlspecialchars($assistant['Specialization']); ?></p></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="stats-container">
            <div class="stat-box"><div class="stat-icon rating-bg"><i class="fa-solid fa-star"></i></div><h3><?php echo $rating; ?></h3><p>Average Rating</p></div>
            <div class="stat-box"><div class="stat-icon completed-bg"><i class="fa-solid fa-check-double"></i></div><h3><?php echo $stats['CompletedCount']; ?></h3><p>Completed Requests</p></div>
            <div class="stat-box"><div class="stat-icon assigned-bg"><i class="fa-solid fa-clock"></i></div><h3><?php echo $stats['AssignedCount']; ?></h3><p>Active Requests</p></div>
        </div>

       <div class="reviews-section">
        <h3>Ratings & Reviews</h3>
        <?php if ($reviews_result->num_rows > 0) { ?>
        <div class="slider-wrapper">
            <button class="nav-btn prev" onclick="moveSlider(-1)"><i class="fa-solid fa-chevron-left"></i></button>
            <div class="reviews-slider-container">
                <div class="reviews-track" id="reviewsTrack">
                    <?php while($rev = $reviews_result->fetch_assoc()) { ?>
                    <div class="review-card">
                        <img src="https://ui-avatars.com/api/?name=<?php echo urlencode($rev['UserName']); ?>" class="reviewer-img">
                        <div class="review-content">
                            <div class="review-header">
                                <h4><?php echo htmlspecialchars($rev['UserName']); ?> <span>(REQ-<?php echo $rev['RequestID']; ?>)</span></h4>
                                <span class="time-ago"><?php echo date("d M Y", strtotime($rev['Date'])); ?></span>
                            </div>
                            <div class="stars">
                                <?php for($i=0; $i<$rev['Stars']; $i++) { echo '<i class="fa-solid fa-star"></i>'; } ?> 
                                <?php echo $rev['Stars']; ?>.0
                            </div>
                            <p>"<?php echo htmlspecialchars($rev['Comment']); ?>"</p>
                        </div>
                    </div>
                    <?php } ?>
                </div>
            </div>
            <button class="nav-btn next" onclick="moveSlider(1)"><i class="fa-solid fa-chevron-right"></i></button>
        </div>
        <?php } else { echo "<p>No reviews available for this assistant yet.</p>"; } ?>
    </div>

        <div class="table-container-details">
            <h3>Assigned Requests</h3>
            <table class="req-tab">
                <thead>
                    <tr><th>Request ID</th><th>Airport</th><th>Assistance Type</th><th>Date</th><th>Status</th></tr>
                </thead>
                <tbody>
                    <?php 
                    if ($requests_result->num_rows > 0) {
                        while($req = $requests_result->fetch_assoc()) { 
                            $statusClass = strtolower($req['Status']); // لربط الألوان بالـ CSS
                    ?>
                    <tr>
                        <td>REQ-<?php echo $req['RequestID']; ?></td>
                        <td><?php echo htmlspecialchars($req['AirportName']); ?></td>
                        <td><?php echo htmlspecialchars($req['AssistanceName']); ?></td>
                        <td><?php echo date("M d, Y", strtotime($req['Date'])); ?></td>
                        <td><span class="status-badge <?php echo $statusClass; ?>"><?php echo htmlspecialchars($req['Status']); ?></span></td>
                    </tr>
                    <?php } } else { echo "<tr><td colspan='5'>No assigned requests found.</td></tr>"; } ?>
                </tbody>
            </table>
        </div>

        <a href="Weekly_Performance.php" class="btn-back-link"><i class="fa-solid fa-arrow-left"></i> Back to Weekly Performance</a>
    </main>
</div>
<script>
    let currentIndex = 0;
    const track = document.getElementById('reviewsTrack');
    const cards = document.querySelectorAll('.review-card');

    if(track && cards.length > 0) {
        function moveSlider(direction) {
            currentIndex += direction;
            if (currentIndex < 0) { currentIndex = cards.length - 1; } 
            else if (currentIndex >= cards.length) { currentIndex = 0; }
            const offset = currentIndex * -100;
            track.style.transform = `translateX(${offset}%)`;
        }
    }
</script>
</body>
</html>

