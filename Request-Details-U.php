<?php
session_start();
require 'db_connection.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: LogIn.html");
    exit();
}

if (!isset($_GET['id'])) {
    header("Location: my-requests.php");
    exit();
}

$travelerID = $_SESSION['user_id'];
$requestID = $_GET['id'];

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $stars = $_POST['stars'] ?? 0;
    $comment = $_POST['comment'] ?? '';

    // check if review already exists
    $check = "SELECT ReviewID FROM REVIEW WHERE RequestID = ?";
    $stmtCheck = mysqli_prepare($conn, $check);
    mysqli_stmt_bind_param($stmtCheck, "i", $requestID);
    mysqli_stmt_execute($stmtCheck);
    $resCheck = mysqli_stmt_get_result($stmtCheck);

    if (mysqli_num_rows($resCheck) == 0) {
        $insertReview = "
            INSERT INTO REVIEW (Stars, Comment, Date, RequestID)
            VALUES (?, ?, CURRENT_TIMESTAMP, ?)
        ";

        $stmtReview = mysqli_prepare($conn, $insertReview);
        mysqli_stmt_bind_param($stmtReview, "isi", $stars, $comment, $requestID);
        mysqli_stmt_execute($stmtReview);
    }

    header("Location: Request-Details-U.php?id=$requestID&msg=reviewed");
    exit();
}

$sql = "
SELECT 
    ar.RequestID,
    ar.PreferredTime,
    ar.Date,
    ar.ExtraNote,
    ar.Status,
    ar.IsPaid,
    ar.GateID,
    a.AirportName,
    ass.Name AS AssistantName,
    ass.Specialization,
    GROUP_CONCAT(at.AssistanceName SEPARATOR ', ') AS AssistanceTypes,
    SUM(at.Price) AS TotalAmount,
    r.ReviewID
FROM ASSISTANCE_REQUEST ar
LEFT JOIN GATE g ON ar.GateID = g.GateID
LEFT JOIN AIRPORT a ON g.AirportID = a.AirportID
LEFT JOIN ASSISTANT ass ON ar.AssistantID = ass.AssistantID
LEFT JOIN REQUEST_TYPE rt ON ar.RequestID = rt.RequestID
LEFT JOIN ASSISTANCE_TYPE at ON rt.AssistanceTypeID = at.AssistanceTypeID
LEFT JOIN REVIEW r ON ar.RequestID = r.RequestID
WHERE ar.RequestID = ? AND ar.TravelerID = ?
GROUP BY ar.RequestID
";

$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "ii", $requestID, $travelerID);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$request = mysqli_fetch_assoc($result);

if (!$request) {
    header("Location: my-requests.php");
    exit();
}

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
  <title>Yumnak | Request Details</title>

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
    <a href="my-requests.php">My Requests</a>
  </nav>

  <div class="logout">
    <button onclick="window.location.href='logout.php'">
      <i class="fas fa-sign-out-alt"></i>
      Logout
    </button>
  </div>
</header>

<main class="details-container">

<?php
if (isset($_GET['msg']) && $_GET['msg'] == 'reviewed') {
    echo "<script>alert('Thank you for your feedback!');</script>";
}
?>

<h2 class="page-title">
  <i class="fa-solid fa-arrow-left back-icon"></i>
  Request Details
</h2>

<p class="page-subtitle">
  View the full details and current status of your assistance request.
</p>

<div class="status-badge <?= statusClass($request['Status']) ?>">
  <i class="fa-solid fa-circle-check"></i>
  <?= htmlspecialchars($request['Status']) ?>
</div>

<div class="details-box">
  <h2>Request Information</h2>

  <div class="details-grid">
    <div>
      <span>Request ID</span>
      <p>#YMK-<?= htmlspecialchars($request['RequestID']) ?></p>
    </div>

    <div>
      <span>Preferred Date</span>
      <p><?= date("F d, Y", strtotime($request['PreferredTime'])) ?></p>
    </div>

    <div>
      <span>Airport</span>
      <p><?= htmlspecialchars($request['AirportName']) ?></p>
    </div>

    <div>
      <span>Preferred Time</span>
      <p><?= date("H:i", strtotime($request['PreferredTime'])) ?></p>
    </div>

    <div>
      <span>Entrance Gate</span>
      <p>Gate <?= htmlspecialchars($request['GateID']) ?></p>
    </div>

    <div>
      <span>Payment Status</span>
      <p id="paid">
        <i class="fa-solid fa-check"></i>
        <?= $request['IsPaid'] ? 'Paid' : 'Not Paid' ?>
      </p>
    </div>

    <div>
      <span>Assistance Type</span>
      <p>
        <i class="fa-solid fa-wheelchair chairicon"></i>
        <?= htmlspecialchars($request['AssistanceTypes'] ?? 'Not specified') ?>
      </p>
    </div>

    <div>
      <span>Total Amount</span>
      <p>$<?= number_format($request['TotalAmount'] ?? 0, 2) ?></p>
    </div>
  </div>

  <div class="notes">
    <span class="notes-title">Extra Notes</span>
    <p><?= htmlspecialchars($request['ExtraNote'] ?: 'No extra notes provided.') ?></p>
  </div>
</div>

<div class="details-box">
  <h2>Assigned Assistant</h2>

  <?php if ($request['AssistantName']): ?>
    <div class="assistant-info">
      <img src="https://ui-avatars.com/api/?name=<?= urlencode($request['AssistantName']) ?>&background=random">

      <div>
        <p class="assistant-name"><?= htmlspecialchars($request['AssistantName']) ?></p>
        <span><?= htmlspecialchars($request['Specialization']) ?></span>
      </div>
    </div>
  <?php else: ?>
    <p>No assistant assigned yet.</p>
  <?php endif; ?>
</div>

<div class="details-actions">
  <?php if ($request['Status'] === 'Completed' && !$request['ReviewID']): ?>
    <button type="button" class="btn rate">
      <i class="fa-solid fa-star"></i>
      Rate & Review
    </button>
  <?php endif; ?>

  <button type="button" class="btn back">
    <i class="fa-solid fa-arrow-left"></i>
    Back to My Requests
  </button>
</div>

</main>

<footer class="footer">
  <div class="footer-bottom">
    &copy; 2026 Yumnak Platform. All rights reserved.
  </div>
</footer>

<div class="rating-modal" id="ratingModal">
  <div class="rating-box">
    <h3>Rate Your Experience</h3>

    <div class="stars">
      <i class="fa-regular fa-star"></i>
      <i class="fa-regular fa-star"></i>
      <i class="fa-regular fa-star"></i>
      <i class="fa-regular fa-star"></i>
      <i class="fa-regular fa-star"></i>
    </div>

    <form method="POST">
      <input type="hidden" name="stars" id="starsInput">
      <textarea name="comment" placeholder="Write your review..."></textarea>

      <div class="rating-actions">
        <button type="submit" class="btn submit-rating">Submit</button>
        <button type="button" class="btn close-rating">Cancel</button>
      </div>
    </form>
  </div>
</div>

<script>
const modal = document.getElementById("ratingModal");
const stars = document.querySelectorAll(".stars i");
const starsInput = document.getElementById("starsInput");

let selectedRating = 0;

document.querySelector(".btn.back").onclick = () => {
  window.location.href = "my-requests.php";
};

if (document.querySelector(".btn.rate")) {
  document.querySelector(".btn.rate").onclick = () => {
    modal.style.display = "flex";
  };
}

document.querySelector(".close-rating").onclick = () => {
  modal.style.display = "none";
};

stars.forEach((star, index) => {
  star.onclick = () => {
    selectedRating = index + 1;
    starsInput.value = selectedRating;
  };
});
</script>

</body>
</html>