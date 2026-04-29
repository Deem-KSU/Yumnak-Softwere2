<?php

session_start();
$timeout = 900;

if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity']) > $timeout) {
    session_unset();
    session_destroy();
    header("Location: LogIn.php?msg=timeout");
    exit();
}

$_SESSION['last_activity'] = time();
require 'db_connection.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: LogIn.php");
    exit();
}

$travelerID = $_SESSION['user_id'];


if ($_SERVER["REQUEST_METHOD"] == "POST" && !isset($_POST['confirm_payment']) && isset($_POST['airport_id'])) {
    $_SESSION['pending_request'] = [
        'airport_id' => $_POST['airport_id'],
        'gate_id' => $_POST['gate_id'],
        'assistance_type' => $_POST['assistance_type'],
        'preferred_date' => $_POST['preferred_date'],
        'preferred_time' => $_POST['preferred_time'],
        'extra_note' => $_POST['extra_note']
    ];

    unset($_SESSION['pending_edit']);
}

if (isset($_SESSION['pending_edit'])) {
    $paymentMode = "edit";
    $request = $_SESSION['pending_edit'];

    $requestID = $request['request_id'];
    $airportID = $request['airport_id'];
    $gateID = $request['gate_id'];
    $types = $request['assistance_type'];
    $date = $request['preferred_date'];
    $time = $request['preferred_time'];
    $note = $request['extra_note'];
    $totalPrice = $request['difference'];
} else if (isset($_SESSION['pending_request'])) {
    $paymentMode = "add";
    $request = $_SESSION['pending_request'];

    $airportID = $request['airport_id'];
    $gateID = $request['gate_id'];
    $types = $request['assistance_type'];
    $date = $request['preferred_date'];
    $time = $request['preferred_time'];
    $note = $request['extra_note'];
} else {
    header("Location: Airport_Selection.php");
    exit();
}

$preferredDateTime = $date . " " . $time . ":00";

$airportResult = mysqli_query($conn, "SELECT AirportName FROM airport WHERE AirportID = $airportID");
$airport = mysqli_fetch_assoc($airportResult);

$typeNames = [];

if ($paymentMode == "add") {
    $totalPrice = 0;
}

foreach ($types as $typeID) {
    $typeResult = mysqli_query($conn, "SELECT AssistanceName, Price FROM assistance_type WHERE AssistanceTypeID = $typeID");
    $typeRow = mysqli_fetch_assoc($typeResult);

    $typeNames[] = $typeRow['AssistanceName'];

    if ($paymentMode == "add") {
        $totalPrice += $typeRow['Price'];
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['confirm_payment'])) {

    if ($paymentMode == "edit") {

        $updateSql = "
            UPDATE assistance_request
            SET PreferredTime = ?, ExtraNote = ?, GateID = ?
            WHERE RequestID = ? AND TravelerID = ?
        ";

        $stmt = mysqli_prepare($conn, $updateSql);
        mysqli_stmt_bind_param($stmt, "sssii", $preferredDateTime, $note, $gateID, $requestID, $travelerID);

        if (mysqli_stmt_execute($stmt)) {

            mysqli_query($conn, "DELETE FROM request_type WHERE RequestID = $requestID");

            foreach ($types as $typeID) {
                $insertType = "INSERT INTO request_type (RequestID, AssistanceTypeID) VALUES (?, ?)";
                $typeStmt = mysqli_prepare($conn, $insertType);
                mysqli_stmt_bind_param($typeStmt, "ii", $requestID, $typeID);
                mysqli_stmt_execute($typeStmt);
            }

            unset($_SESSION['pending_edit']);

            echo "<script>
                alert('Additional payment completed successfully. Your request has been updated.');
                window.location.href = 'my-requests.php';
            </script>";
            exit();

        } else {
            echo "Error: " . mysqli_error($conn);
        }

    } else {

        $insertRequest = "
            INSERT INTO assistance_request 
            (PreferredTime, Date, ExtraNote, Status, IsPaid, TravelerID, AdminID, AssistantID, GateID)
            VALUES
            ('$preferredDateTime', NOW(), '$note', 'Pending', 1, $travelerID, NULL, NULL, '$gateID')
        ";

        if (mysqli_query($conn, $insertRequest)) {

            $requestID = mysqli_insert_id($conn);

            foreach ($types as $typeID) {
                $insertType = "
                    INSERT INTO request_type (RequestID, AssistanceTypeID)
                    VALUES ($requestID, $typeID)
                ";
                mysqli_query($conn, $insertType);
            }

            unset($_SESSION['pending_request']);

            echo "<script>
                alert('Payment completed successfully. Your request has been confirmed.');
                window.location.href = 'my-requests.php';
            </script>";
            exit();

        } else {
            echo "Error: " . mysqli_error($conn);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Yumnak | Payment</title>

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
        <button onclick="window.location.href='LogIn.php'">
            <i class="fas fa-sign-out-alt"></i>
            Logout
        </button>
    </div>
  </header>

<main class="payment-page">
  <section class="payment-section">

    <div class="payment-header">
      <h1 class="payment-page-title">Confirm and Pay for Assistance</h1>
      <p class="payment-page-subtitle">
        Complete your payment to confirm the airport assistance request.
      </p>
    </div>

    <div class="payment-content">

      <div class="payment-card">
        <h2 class="payment-card-title">Request Summary</h2>

        <div class="payment-summary-row">
          <span>Airport</span>
          <strong><?php echo $airport['AirportName']; ?></strong>
        </div>

        <div class="payment-summary-row">
          <span>Entrance Gate</span>
          <strong><?php echo $gateID; ?></strong>
        </div>

        <div class="payment-summary-row">
          <span>Assistance Type</span>
          <strong><?php echo implode(", ", $typeNames); ?></strong>
        </div>

        <div class="payment-summary-row">
          <span>Preferred Date</span>
          <strong><?php echo $date; ?></strong>
        </div>

        <div class="payment-summary-row">
          <span>Preferred Time</span>
          <strong><?php echo $time; ?></strong>
        </div>

        <div class="payment-summary-note">
          <span>Extra Note</span>
          <p><?php echo empty($note) ? "No extra note" : $note; ?></p>
        </div>

        <div class="payment-total-box">
          <span><?php echo ($paymentMode == "edit") ? "Additional Payment" : "Total Price"; ?></span>
          <strong>$<?php echo number_format($totalPrice, 2); ?></strong>
        </div>
      </div>

      <div class="payment-card">
        <h2 class="payment-card-title">Payment Details</h2>

        <form id="paymentForm" class="payment-form" method="POST" action="Payment.php">

          <input type="hidden" name="confirm_payment" value="1">

          <div class="payment-field">
            <label>Payment Method</label>

            <div class="payment-method-options">
              <label class="payment-method-box">
                <input type="radio" name="paymentMethod" value="card" id="cardMethod">
                <i class="fa-brands fa-cc-visa"></i>
                <i class="fa-brands fa-cc-mastercard"></i>
                <span>Card</span>
              </label>

              <label class="payment-method-box">
                <input type="radio" name="paymentMethod" value="applepay" id="applePayMethod">
                <i class="fa-brands fa-apple-pay"></i>
                <span>Apple Pay</span>
              </label>
            </div>
          </div>

          <div id="cardFieldsWrapper">
            <div class="payment-field">
              <label for="cardNumber">Card Number</label>
              <input type="text" id="cardNumber" placeholder="1234-5678-9012-3456">
            </div>

            <div class="payment-row">
              <div class="payment-field">
                <label for="expiryDate">Expiry Date</label>
                <input type="text" id="expiryDate" placeholder="MM/YY">
              </div>

              <div class="payment-field">
                <label for="cvv">CVV</label>
                <input type="text" id="cvv" placeholder="123">
              </div>
            </div>
          </div>

          <p class="payment-error-message" id="paymentErrorMessage">
            Payment failed. Please try again.
          </p>

          <div class="payment-actions">
            <button type="button" class="payment-cancel-btn" id="paymentCancelBtn">
              <i class="fas fa-xmark"></i>
              Cancel
            </button>

            <button type="submit" class="payment-confirm-btn">
              <i class="fas fa-credit-card"></i>
              Confirm Payment ($<?php echo number_format($totalPrice, 2); ?>)
            </button>
          </div>

        </form>
      </div>

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
  const paymentForm = document.getElementById("paymentForm");
  const cardNumber = document.getElementById("cardNumber");
  const expiryDate = document.getElementById("expiryDate");
  const cvv = document.getElementById("cvv");
  const paymentErrorMessage = document.getElementById("paymentErrorMessage");
  const paymentCancelBtn = document.getElementById("paymentCancelBtn");

  const cardMethod = document.getElementById("cardMethod");
  const applePayMethod = document.getElementById("applePayMethod");
  const cardFieldsWrapper = document.getElementById("cardFieldsWrapper");

  function updatePaymentFields() {
    if (applePayMethod.checked) {
      cardFieldsWrapper.style.display = "none";
      cardNumber.value = "";
      expiryDate.value = "";
      cvv.value = "";
      paymentErrorMessage.style.display = "none";
    } else {
      cardFieldsWrapper.style.display = "block";
    }
  }

  cardMethod.addEventListener("change", updatePaymentFields);
  applePayMethod.addEventListener("change", updatePaymentFields);

  cardNumber.addEventListener("input", function () {
    let value = cardNumber.value.replace(/[^0-9]/g, "");
    value = value.substring(0, 16);

    let formatted = value.match(/.{1,4}/g);

    if (formatted) {
      cardNumber.value = formatted.join("-");
    } else {
      cardNumber.value = value;
    }
  });

  expiryDate.addEventListener("input", function () {
    let value = expiryDate.value.replace(/[^0-9]/g, "");
    value = value.substring(0, 4);

    if (value.length >= 3) {
      expiryDate.value = value.substring(0, 2) + "/" + value.substring(2, 4);
    } else {
      expiryDate.value = value;
    }
  });

  cvv.addEventListener("input", function () {
    let value = cvv.value.replace(/[^0-9]/g, "");
    cvv.value = value.substring(0, 3);
  });

  paymentForm.addEventListener("submit", function(event) {
    const selectedMethod = document.querySelector('input[name="paymentMethod"]:checked');

    if (!selectedMethod) {
      event.preventDefault();
      paymentErrorMessage.textContent = "Please select a payment method.";
      paymentErrorMessage.style.display = "block";
      return;
    }

    if (selectedMethod.value === "card") {
      const cardValue = cardNumber.value.replace(/[^0-9]/g, "");
      const expiryValue = expiryDate.value.trim();
      const cvvValue = cvv.value.trim();

      if (cardValue.length !== 16) {
        event.preventDefault();
        paymentErrorMessage.textContent = "Card number must be 16 digits.";
        paymentErrorMessage.style.display = "block";
        return;
      }

      if (!/^\d{3}$/.test(cvvValue)) {
        event.preventDefault();
        paymentErrorMessage.textContent = "CVV must be 3 digits.";
        paymentErrorMessage.style.display = "block";
        return;
      }

      if (!/^\d{2}\/\d{2}$/.test(expiryValue)) {
        event.preventDefault();
        paymentErrorMessage.textContent = "Expiry date must be in MM/YY format.";
        paymentErrorMessage.style.display = "block";
        return;
      }

      const parts = expiryValue.split("/");
      const month = parseInt(parts[0], 10);
      const year = parseInt("20" + parts[1], 10);

      const now = new Date();
      const currentYear = now.getFullYear();
      const currentMonth = now.getMonth() + 1;

      if (month < 1 || month > 12) {
        event.preventDefault();
        paymentErrorMessage.textContent = "Invalid month.";
        paymentErrorMessage.style.display = "block";
        return;
      }

      if (year < currentYear || (year === currentYear && month < currentMonth)) {
        event.preventDefault();
        paymentErrorMessage.textContent = "Card has expired.";
        paymentErrorMessage.style.display = "block";
        return;
      }
    }

    paymentErrorMessage.style.display = "none";
  });

  paymentCancelBtn.addEventListener("click", function() {
    window.location.href = "my-requests.php";
  });

  updatePaymentFields();
</script>

</body>
</html>
