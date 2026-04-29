<?php
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

if (!isset($_SESSION['user_id'])) {
    header("Location: Index.php");
    exit();
}

$travelerID = $_SESSION['user_id'];

if (!isset($_GET['airport_id'])) {
    header("Location: Airport_Selection.php");
    exit();
}

$airportID = $_GET['airport_id'];

$airportSql = "SELECT * FROM airport WHERE AirportID = $airportID";
$airportResult = mysqli_query($conn, $airportSql);
$airport = mysqli_fetch_assoc($airportResult);

$gateSql = "SELECT * FROM gate WHERE AirportID = $airportID";
$gateResult = mysqli_query($conn, $gateSql);

$typeSql = "SELECT * FROM assistance_type";
$typeResult = mysqli_query($conn, $typeSql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Yumnak | Add Request</title>

  <link rel="stylesheet" href="styleS.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>

<body>

<header>
    <div class="logo">
      <img src="Image/Yumnak-Logo.png" alt="Yumnak Logo">
    </div>

    <nav>
      <a class="active" href="User-Dashboard.php">Home</a>
      <a href="my-requests.php">My Requests</a>
    </nav>

    <div class="logout">
         <button onclick="window.location.href='logout.php'">
            <i class="fas fa-sign-out-alt"></i>
            Logout
        </button>
    </div>
</header>

<main class="add-request-page">
  <section class="add-request-form-section">
    <div class="add-request-form-card">

      <div class="add-request-form-header">
        <h1 class="add-request-page-title">Request Airport Assistance</h1>
        <p class="add-request-form-subtitle">
          Fill in the required information to request airport assistance.
        </p>
      </div>

      <form class="add-request-form" id="addRequestForm" action="Payment.php" method="POST">

        <input type="hidden" name="airport_id" value="<?php echo $airportID; ?>">

        <div class="add-request-field">
          <label for="airport">Airport <span>*</span></label>
          <div class="add-request-input-with-icon">
            <i class="fas fa-plane-departure"></i>
            <input type="text" id="airport" value="<?php echo $airport['AirportName']; ?>" readonly>
          </div>
        </div>

        <div class="add-request-field">
          <label for="gate">Entrance Gate <span>*</span></label>
          <div class="add-request-input-with-icon">
            <i class="fas fa-door-open"></i>
            <select id="gate" name="gate_id">
              <option value="" selected disabled>Select entrance gate</option>

              <?php
              while ($gate = mysqli_fetch_assoc($gateResult)) {
                  echo "<option value='" . $gate['GateID'] . "'>" . $gate['GateID'] . "</option>";
              }
              ?>

            </select>
          </div>
        </div>

        <div class="add-request-field">
          <label for="type">Assistance Type <span>*</span></label>
          <small>Hold Ctrl (or Command) to select multiple</small>
          <div class="add-request-input-with-icon">
            <i class="fas fa-hands-helping"></i>

            <select id="type" name="assistance_type[]" multiple size="1" >
              <?php
              while ($type = mysqli_fetch_assoc($typeResult)) {
                  echo "<option value='" . $type['AssistanceTypeID'] . "'>" . $type['AssistanceName'] . "</option>";
              }
              ?>
            </select>

          </div>
        </div>

        <div class="add-request-row">
          <div class="add-request-field">
            <label for="date">Preferred Date <span>*</span></label>
            <div class="add-request-input-with-icon">
              <i class="fas fa-calendar-alt"></i>
              <input type="date" id="date" name="preferred_date">
            </div>
          </div>

          <div class="add-request-field">
            <label for="time">Preferred Time <span>*</span></label>
            <div class="add-request-input-with-icon">
              <i class="fas fa-clock"></i>
              <input type="time" id="time" name="preferred_time">
            </div>
          </div>
        </div>

        <div class="add-request-field">
          <label for="note">Extra Note(Optional)</label>
          <div class="add-request-textarea-with-icon">
            <i class="fas fa-pen"></i>
            <textarea id="note" name="extra_note" rows="5" placeholder="Add any additional details to help us assist you..."></textarea>
          </div>
        </div>

        <p class="add-request-error-message" id="errorMessage">
          Please fill in all required fields.
        </p>

        <div class="add-request-actions">

          <button type="button" class="add-request-cancel-btn" id="cancelBtn">
              <i class="fas fa-xmark"></i>
              Cancel
          </button>

          <button type="submit" class="add-request-submit-btn">
              <i class="fas fa-paper-plane"></i>
              Submit Request
          </button>

        </div>

      </form>
    </div>
  </section>
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
        <li><a href="User-Dashboard.php">Dashboard</a></li>
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
  const form = document.getElementById("addRequestForm");
  const gateInput = document.getElementById("gate");
  const typeInput = document.getElementById("type");
  const dateInput = document.getElementById("date");
  const timeInput = document.getElementById("time");
  const errorMessage = document.getElementById("errorMessage");
  const cancelBtn = document.getElementById("cancelBtn");

  form.addEventListener("submit", function (event) {
    const gate = gateInput.value;
    const selectedTypes = Array.from(typeInput.selectedOptions);
    const date = dateInput.value;
    const time = timeInput.value;

    if (gate === "" || selectedTypes.length === 0 || date === "" || time === "") {
      event.preventDefault();
      errorMessage.textContent = "Please fill in all required fields.";
      errorMessage.style.display = "block";
      return;
    }

    const today = new Date().toISOString().split("T")[0];

    if (date < today) {
      event.preventDefault();
      errorMessage.textContent = "Choose today or a future date";
      errorMessage.style.display = "block";
      return;
    }

    errorMessage.style.display = "none";
  });

  cancelBtn.addEventListener("click", function () {
    window.location.href = "User-Dashboard.php";
  });
</script>

</body>
</html>
