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


$sql = "SELECT AirportID, AirportName, City, ImagePath FROM airport";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Yumnak | Airport Selection Page</title>

<link rel="stylesheet" href="styleS.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>

<body>

<header>
    <div class="logo">
      <img src="Image/Yumnak-Logo.png" alt="Yumnak Logo">
    </div>

    <nav>
      <a href="User-Dashboard.php">Home</a>
      <a href="my-requests.php">My Requests</a>
    </nav>

    <div class="logout">
        <button onclick="window.location.href='LogIn.php'">
            <i class="fas fa-sign-out-alt"></i>
            Logout
        </button>
    </div>
</header>

<main class="airport-selection-page">

    <section class="airport-selection-hero">
        <i class="fas fa-plane airport-icon"></i>

        <h1 class="airport-selection-title">Select Airport</h1>

        <div class="airport-selection-search-box">
            <input 
                type="text" 
                placeholder="Search airports..." 
                class="airport-selection-search-input"
                id="airportSearchInput"
            >
            <button class="search-btn" id="airportSearchButton">
                <i class="fas fa-search"></i>
            </button>
        </div>
    </section>

    <section class="airport-selection-list-section">
        <h2 class="airport-selection-section-title">Airports</h2>
        <p class="airport-selection-section-subtitle">Select requested airport assistance locations</p>

        <div class="airport-selection-cards">

            <?php
            if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {

                    $airportID = $row['AirportID'];
                    $airportName = $row['AirportName'];
                    $city = $row['City'];
                    $imagePath = $row['ImagePath'];
            ?>

            <div class="airport-selection-card" data-name="<?php echo $airportName; ?>">
                <img src="<?php echo $imagePath; ?>" alt="<?php echo $airportName; ?>" class="airport-selection-card-image">
                <h3 class="airport-selection-card-title"><?php echo $airportName; ?></h3>
                <p class="airport-selection-card-location"><?php echo $city; ?>, Saudi Arabia</p>

                <button 
                    class="airport-selection-card-button"
                    onclick="window.location.href='Add_request.php?airport_id=<?php echo $airportID; ?>'">
                    Select
                </button>
            </div>

            <?php
                }
            } else {
                echo "<p>No airports found</p>";
            }
            ?>

        </div>

        <p class="airport-selection-no-results" id="noResultsMessage">No results found</p>
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
    const searchInput = document.getElementById("airportSearchInput");
    const searchButton = document.getElementById("airportSearchButton");
    const airportCards = document.querySelectorAll(".airport-selection-card");
    const noResultsMessage = document.getElementById("noResultsMessage");

    function searchAirports() {
        const searchValue = searchInput.value.trim().toLowerCase();
        let matchFound = false;

        airportCards.forEach(function(card) {
            const airportName = card.getAttribute("data-name").toLowerCase();

            if (airportName.includes(searchValue)) {
                card.style.display = "block";
                matchFound = true;
            } else {
                card.style.display = "none";
            }
        });

        noResultsMessage.style.display = matchFound ? "none" : "block";
    }

    searchButton.addEventListener("click", searchAirports);

    searchInput.addEventListener("keyup", function(event) {
        if (event.key === "Enter") {
            searchAirports();
        }
    });
</script>

</body>
</html>
