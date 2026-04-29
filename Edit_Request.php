<?php
session_start();
require 'db_connection.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: LogIn.html");
    exit();
}

$travelerID = $_SESSION['user_id'];




if (!isset($_GET['id'])) {
    header("Location: my-requests.php");
    exit();
}

$requestID = $_GET['id'];

function getRequestPrice($conn, $requestID) {
    $total = 0;

    $sql = "
        SELECT at.Price
        FROM request_type rt
        JOIN assistance_type at ON rt.AssistanceTypeID = at.AssistanceTypeID
        WHERE rt.RequestID = $requestID
    ";

    $result = mysqli_query($conn, $sql);

    while ($row = mysqli_fetch_assoc($result)) {
        $total += $row['Price'];
    }

    return $total;
}

function getNewPrice($conn, $types) {
    $total = 0;

    foreach ($types as $typeID) {
        $sql = "SELECT Price FROM assistance_type WHERE AssistanceTypeID = $typeID";
        $result = mysqli_query($conn, $sql);
        $row = mysqli_fetch_assoc($result);
        $total += $row['Price'];
    }

    return $total;
}

function updateRequest($conn, $requestID, $travelerID, $preferredDateTime, $note, $gateID, $types) {
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

        return true;
    }

    return false;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $airportID = $_POST['airport'];
    $gateID = $_POST['gate'];
    $types = $_POST['type'];
    $date = $_POST['date'];
    $time = $_POST['time'];
    $note = $_POST['note'];

    $preferredDateTime = $date . " " . $time . ":00";

    $oldPrice = getRequestPrice($conn, $requestID);
    $newPrice = getNewPrice($conn, $types);

    if ($newPrice > $oldPrice) {

        $difference = $newPrice - $oldPrice;

        $_SESSION['pending_edit'] = [
            'request_id' => $requestID,
            'airport_id' => $airportID,
            'gate_id' => $gateID,
            'assistance_type' => $types,
            'preferred_date' => $date,
            'preferred_time' => $time,
            'extra_note' => $note,
            'difference' => $difference
        ];

        unset($_SESSION['pending_request']);

        echo "<script>
            alert('The selected assistance types cost more. Please pay the additional amount to complete the update.');
            window.location.href = 'Payment.php';
        </script>";
        exit();

    } else if ($newPrice < $oldPrice) {

        if (updateRequest($conn, $requestID, $travelerID, $preferredDateTime, $note, $gateID, $types)) {
            echo "<script>
                alert('Your request has been updated successfully. The price is lower, and the refund will be processed as soon as possible.');
                window.location.href = 'my-requests.php';
            </script>";
            exit();
        } else {
            echo "Error: " . mysqli_error($conn);
        }

    } else {

        if (updateRequest($conn, $requestID, $travelerID, $preferredDateTime, $note, $gateID, $types)) {
            echo "<script>
                alert('Your request has been updated successfully.');
                window.location.href = 'my-requests.php';
            </script>";
            exit();
        } else {
            echo "Error: " . mysqli_error($conn);
        }
    }
}

$requestSql = "
    SELECT 
        ar.RequestID,
        ar.PreferredTime,
        ar.ExtraNote,
        ar.Status,
        ar.GateID,
        g.AirportID
    FROM assistance_request ar
    JOIN gate g ON ar.GateID = g.GateID
    WHERE ar.RequestID = ? AND ar.TravelerID = ?
";

$stmt = mysqli_prepare($conn, $requestSql);
mysqli_stmt_bind_param($stmt, "ii", $requestID, $travelerID);
mysqli_stmt_execute($stmt);
$requestResult = mysqli_stmt_get_result($stmt);

if (mysqli_num_rows($requestResult) == 0) {
    header("Location: my-requests.php");
    exit();
}

$request = mysqli_fetch_assoc($requestResult);

$currentAirportID = $request['AirportID'];
$currentGateID = $request['GateID'];
$currentDate = date("Y-m-d", strtotime($request['PreferredTime']));
$currentTime = date("H:i", strtotime($request['PreferredTime']));
$currentNote = $request['ExtraNote'];

$airportsResult = mysqli_query($conn, "SELECT AirportID, AirportName FROM airport");
$gatesResult = mysqli_query($conn, "SELECT GateID, AirportID FROM gate");
$typesResult = mysqli_query($conn, "SELECT AssistanceTypeID, AssistanceName FROM assistance_type");

$selectedTypes = [];
$typeSql = "SELECT AssistanceTypeID FROM request_type WHERE RequestID = $requestID";
$typeResult = mysqli_query($conn, $typeSql);

while ($typeRow = mysqli_fetch_assoc($typeResult)) {
    $selectedTypes[] = $typeRow['AssistanceTypeID'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Yumnak | Edit Request</title>

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
        <button onclick="window.location.href='LogIn.html'">
            <i class="fas fa-sign-out-alt"></i>
            Logout
        </button>
    </div>
</header>

<main class="add-request-page">
  <section class="add-request-form-section">
    <div class="add-request-form-card">

      <div class="add-request-form-header">
        <h1 class="add-request-page-title">Edit Assistance Request</h1>
        <p class="add-request-form-subtitle">
          Update the request details before saving your changes.
        </p>
      </div>

      <form class="add-request-form" id="addRequestForm" method="POST">

        <div class="add-request-field">
          <label for="airport">Airport <span>*</span></label>
          <div class="add-request-input-with-icon">
            <i class="fas fa-plane-departure"></i>

            <select id="airport" name="airport">
              <?php while ($airport = mysqli_fetch_assoc($airportsResult)) { ?>
                <option value="<?php echo $airport['AirportID']; ?>"
                  <?php if ($airport['AirportID'] == $currentAirportID) echo "selected"; ?>>
                  <?php echo htmlspecialchars($airport['AirportName']); ?>
                </option>
              <?php } ?>
            </select>

          </div>
        </div>

        <div class="add-request-field">
          <label for="gate">Entrance Gate <span>*</span></label>
          <div class="add-request-input-with-icon">
            <i class="fas fa-door-open"></i>

            <select id="gate" name="gate">
              <option value="" disabled>Select entrance gate</option>

              <?php while ($gate = mysqli_fetch_assoc($gatesResult)) { ?>
                <option 
                  value="<?php echo $gate['GateID']; ?>"
                  data-airport="<?php echo $gate['AirportID']; ?>"
                  <?php if ($gate['GateID'] == $currentGateID) echo "selected"; ?>>
                  <?php echo htmlspecialchars($gate['GateID']); ?>
                </option>
              <?php } ?>

            </select>

          </div>
        </div>

        <div class="add-request-field">
          <label for="type">Assistance Type <span>*</span></label>
          <div class="add-request-input-with-icon">
            <i class="fas fa-hands-helping"></i>

            <select id="type" name="type[]" multiple size="1">
              <?php while ($type = mysqli_fetch_assoc($typesResult)) { ?>
                <option value="<?php echo $type['AssistanceTypeID']; ?>"
                  <?php if (in_array($type['AssistanceTypeID'], $selectedTypes)) echo "selected"; ?>>
                  <?php echo htmlspecialchars($type['AssistanceName']); ?>
                </option>
              <?php } ?>
            </select>

          </div>
        </div>

        <div class="add-request-row">
          <div class="add-request-field">
            <label for="date">Preferred Date <span>*</span></label>
            <div class="add-request-input-with-icon">
              <i class="fas fa-calendar-alt"></i>
              <input type="date" id="date" name="date" value="<?php echo $currentDate; ?>">
            </div>
          </div>

          <div class="add-request-field">
            <label for="time">Preferred Time <span>*</span></label>
            <div class="add-request-input-with-icon">
              <i class="fas fa-clock"></i>
              <input type="time" id="time" name="time" value="<?php echo $currentTime; ?>">
            </div>
          </div>
        </div>

        <div class="add-request-field">
          <label for="note">Extra Note(Optional)</label>
          <div class="add-request-textarea-with-icon">
            <i class="fas fa-pen"></i>
            <textarea id="note" name="note" rows="5" placeholder="Add any additional details to help us assist you..."><?php echo htmlspecialchars($currentNote); ?></textarea>
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
            <i class="fas fa-floppy-disk"></i>
            Save Changes
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
  const airportInput = document.getElementById("airport");
  const gateInput = document.getElementById("gate");
  const typeInput = document.getElementById("type");
  const dateInput = document.getElementById("date");
  const timeInput = document.getElementById("time");
  const noteInput = document.getElementById("note");
  const errorMessage = document.getElementById("errorMessage");
  const cancelBtn = document.getElementById("cancelBtn");

  function filterGates() {
    const selectedAirport = airportInput.value;
    const gateOptions = gateInput.querySelectorAll("option");

    gateOptions.forEach(function(option) {
      if (option.value === "") {
        option.style.display = "block";
        return;
      }

      if (option.getAttribute("data-airport") === selectedAirport) {
        option.style.display = "block";
      } else {
        option.style.display = "none";
      }
    });

    const selectedGateOption = gateInput.options[gateInput.selectedIndex];

    if (selectedGateOption && selectedGateOption.getAttribute("data-airport") !== selectedAirport) {
      gateInput.value = "";
    }
  }

  airportInput.addEventListener("change", filterGates);
  filterGates();

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

    if (dateInput.value < today) {
      event.preventDefault();
      errorMessage.textContent = "Choose today or a future date";
      errorMessage.style.display = "block";
      return;
    }

    const confirmUpdate = confirm("Are you sure you want to save these changes?");

    if (!confirmUpdate) {
      event.preventDefault();
      return;
    }

    errorMessage.style.display = "none";
  });

  cancelBtn.addEventListener("click", function () {
    window.location.href = "my-requests.php";
  });
</script>

</body>
</html>
