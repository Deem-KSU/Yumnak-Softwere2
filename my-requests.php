<?php
session_start();
require 'db_connection.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: LogIn.html");
    exit();
}

$travelerID = $_SESSION['user_id'];

$sql = "
    SELECT 
        ar.RequestID,
        ar.PreferredTime,
        ar.Date,
        ar.Status,
        ar.GateID,
        g.AirportID,
        a.AirportName,
        GROUP_CONCAT(at.AssistanceName SEPARATOR ', ') AS AssistanceTypes
    FROM ASSISTANCE_REQUEST ar
    JOIN GATE g ON ar.GateID = g.GateID
    JOIN AIRPORT a ON g.AirportID = a.AirportID
    LEFT JOIN REQUEST_TYPE rt ON ar.RequestID = rt.RequestID
    LEFT JOIN ASSISTANCE_TYPE at ON rt.AssistanceTypeID = at.AssistanceTypeID
    WHERE ar.TravelerID = ?
    GROUP BY 
        ar.RequestID, ar.PreferredTime, ar.Date, ar.Status, ar.GateID, g.AirportID, a.AirportName
    ORDER BY ar.Date DESC
";

$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $travelerID);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

function statusClass($status) {
    return match ($status) {
        'Pending' => 'pending',
        'Accepted' => 'accepted',
        'Completed' => 'completed',
        'Rejected' => 'rejected',
        'Cancelled' => 'cancelled',
        default => ''
    };
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Yumnak | My Assistance Requests</title>

  <link rel="stylesheet" href="styleR.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
</head>

<body>
    
  <header>
    <div class="logo">
      <img src="Image/Yumnak-Logo.png" alt="Yumnak Logo">
    </div>

    <nav>
      <a href="user-dashboard.php">Home</a>
      <a class="active" href="#">My Requests</a>
    </nav>

    <div class="logout">
        <button onclick="window.location.href='logout.php'">
            <i class="fas fa-sign-out-alt"></i>
            Logout
        </button>
    </div>
  </header>

  <main class="container">
    <?php
if (isset($_GET['msg']) && $_GET['msg'] == 'cancelled') {
    echo "<script>alert('Request cancelled successfully');</script>";
}

if (isset($_GET['msg']) && $_GET['msg'] == 'failed') {
    echo "<script>alert('Request could not be cancelled');</script>";
}
?>
    <h1>My Assistance Requests</h1>
    <p class="subtitle">
      Track the status and details of your submitted assistance requests.
    </p>

    <div class="requests">
      <?php if (mysqli_num_rows($result) > 0): ?>
        <?php while ($row = mysqli_fetch_assoc($result)): ?>
          <div class="card">
            <div class="card-info">
              <div class="card-header">
                <div class="airport-name">
                  <i class="fa-solid fa-building"></i>
                  <h3><?= htmlspecialchars($row['AirportName']) ?></h3>
                </div>

                <span class="status <?= statusClass($row['Status']) ?>">
                  <?= htmlspecialchars($row['Status']) ?>
                </span>
              </div>

              <div class="details">
                <div>
                  <span class="label">
                    <i class="fa-solid fa-door-open"></i>
                    Gate Number
                  </span>
                  <p><?= htmlspecialchars($row['GateID']) ?></p>
                </div>

                <div>
                  <span class="label">
                    <i class="fa-solid fa-wheelchair"></i>
                    Assistance Type
                  </span>
                  <?php
                    $types = explode(', ', $row['AssistanceTypes'] ?? '');
                    foreach ($types as $type) {
                        if (trim($type) !== '') {
                            echo '<p>' . htmlspecialchars($type) . '</p>';
                        }
                    }
                  ?>
                </div>

                <div>
                  <span class="label">
                    <i class="fa-regular fa-calendar"></i>
                    Date & Time
                  </span>
                  <p>
                    <?= date("M d, Y - H:i", strtotime($row['PreferredTime'])) ?>
                  </p>
                </div>
              </div>
            </div>

            <div class="card-actions">
              <button 
                type="button" 
                class="btn details view-btn" 
                data-id="<?= $row['RequestID'] ?>">
                <i class="fa-regular fa-eye"></i>
                View Details
              </button>

              <?php if ($row['Status'] === 'Pending'): ?>
                <button 
                  type="button" 
                  class="btn edit edit-btn" 
                  data-id="<?= $row['RequestID'] ?>">
                  <i class="fa-regular fa-pen-to-square"></i>
                  Edit
                </button>
              <?php endif; ?>

              <?php if ($row['Status'] === 'Pending' || $row['Status'] === 'Accepted'): ?>
                <button 
                  type="button" 
                  class="btn cancel cancel-btn" 
                  data-id="<?= $row['RequestID'] ?>">
                  <i class="fa-solid fa-xmark"></i>
                  Cancel
                </button>
              <?php endif; ?>
            </div>
          </div>
        <?php endwhile; ?>
      <?php else: ?>
        <p>No requests found.</p>
      <?php endif; ?>
    </div>

    <button type="button" class="add-btn">
      <i class="fa-solid fa-plus"></i>
      Add New Request
    </button>
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
          <li><a href="user-dashboard.php">Dashboard</a></li>
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
  const viewButtons = document.querySelectorAll(".view-btn");
  const editButtons = document.querySelectorAll(".edit-btn");
  const cancelButtons = document.querySelectorAll(".cancel-btn");

  viewButtons.forEach((btn) => {
    btn.addEventListener("click", () => {
      const requestId = btn.dataset.id;
      window.location.href = `Request-Details-U.php?id=${requestId}`;
    });
  });

  editButtons.forEach((btn) => {
    btn.addEventListener("click", () => {
      const requestId = btn.dataset.id;
      window.location.href = `Edit_Request.php?id=${requestId}`;
    });
  });

  cancelButtons.forEach((btn) => {
    btn.addEventListener("click", () => {
      const requestId = btn.dataset.id;
      const confirmCancel = confirm("Are you sure you want to cancel this request?");

      if (confirmCancel) {
        window.location.href = `cancel-request.php?id=${requestId}`;
      }
    });
  });

  document.querySelector(".add-btn").onclick = function() {
    window.location.href = "Airport_Selection.php";
  };
</script>

</body>
</html>