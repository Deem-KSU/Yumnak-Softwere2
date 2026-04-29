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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $reqID = intval($_POST['request_id']);
    $action = $_POST['action'];
    $adminID = 1; // Assuming Admin 1 is logged in for our prototype

    if ($action === 'assign') {
        $assistantID = intval($_POST['selected_assistant']);
        
        if ($assistantID > 0) {
            // Update the request with the chosen Assistant and change status to Accepted
            $updateQuery = "UPDATE ASSISTANCE_REQUEST 
                            SET AssistantID = $assistantID, Status = 'Accepted', AdminID = $adminID 
                            WHERE RequestID = $reqID";
            mysqli_query($conn, $updateQuery);
            header("Location: request-management.php"); // Send them back to the queue
            exit();
        }
    } elseif ($action === 'reject') {
        // Change status to Rejected
        $updateQuery = "UPDATE ASSISTANCE_REQUEST 
                        SET Status = 'Rejected', AdminID = $adminID 
                        WHERE RequestID = $reqID";
        mysqli_query($conn, $updateQuery);
        header("Location: request-management.php");
        exit();
    }
}

// 1. Get the Request ID from the URL (and secure it!)
if (!isset($_GET['id']) || empty($_GET['id'])) {
    // If someone tries to visit this page without clicking a link, send them back
    header("Location: request-management.php");
    exit();
}
$requestID = intval($_GET['id']);

// 2. Fetch all details for THIS specific request
// We join TRAVELER, GATE, AIRPORT, and the ASSISTANCE_TYPE bridge
$reqQuery = "SELECT ar.RequestID, ar.Status, ar.IsPaid, ar.Date AS SubmitDate, ar.PreferredTime, ar.ExtraNote, 
                    t.UserName, t.DOB, t.Phone, t.Email, 
                    g.GateID, a.AirportName,
                    GROUP_CONCAT(at.AssistanceName SEPARATOR ',') AS AssistanceTypes
             FROM ASSISTANCE_REQUEST ar
             JOIN TRAVELER t ON ar.TravelerID = t.UserID
             JOIN GATE g ON ar.GateID = g.GateID
             JOIN AIRPORT a ON g.AirportID = a.AirportID
             JOIN REQUEST_TYPE rt ON ar.RequestID = rt.RequestID
             JOIN ASSISTANCE_TYPE at ON rt.AssistanceTypeID = at.AssistanceTypeID
             WHERE ar.RequestID = $requestID
             GROUP BY ar.RequestID";

$reqResult = mysqli_query($conn, $reqQuery);
$request = mysqli_fetch_assoc($reqResult);

// If the request doesn't exist in the database, redirect them
if (!$request) {
    header("Location: request-management.php");
    exit();
}

// Calculate the Traveler's Age using their DOB
$dob = new DateTime($request['DOB']);
$now = new DateTime();
$age = $now->diff($dob)->y;

// Format the Dates
$submitDate = date('M j, Y - g:i A', strtotime($request['SubmitDate']));
$prefDate = date('F j, Y', strtotime($request['PreferredTime']));
$prefTime = date('g:i A', strtotime($request['PreferredTime']));

// 3. Fetch ONLY Suitable Available Assistants + Their Stats
// First, we break down the traveler's requested assistance types
$requestedTypes = explode(',', $request['AssistanceTypes']);
$searchConditions = [];

// Loop through the requested types and extract the first word (e.g., "Wheelchair" from "Wheelchair Assistance")
foreach ($requestedTypes as $type) {
    $cleanType = trim($type);
    $firstWord = explode(' ', $cleanType)[0];

    // Create a safe SQL LIKE condition to find matching specializations
    $safeWord = mysqli_real_escape_string($conn, $firstWord);
    $searchConditions[] = "a.Specialization LIKE '%$safeWord%'";
}

// Join the conditions together with 'OR' (in case the traveler asked for multiple types of help)
$specializationFilter = implode(' OR ', $searchConditions);

// Now we run our awesome Assistant query, but we add the WHERE clause we just built!
$astQuery = "SELECT a.AssistantID, a.Name, a.Specialization,
                (SELECT COUNT(*) FROM ASSISTANCE_REQUEST ar2 
                 WHERE ar2.AssistantID = a.AssistantID AND ar2.Status = 'Completed') AS CompletedCount,
                (SELECT ROUND(AVG(r.Stars), 1) FROM REVIEW r 
                 JOIN ASSISTANCE_REQUEST ar3 ON r.RequestID = ar3.RequestID 
                 WHERE ar3.AssistantID = a.AssistantID) AS AvgRating
             FROM ASSISTANT a
             WHERE ($specializationFilter)
             AND a.AssistantID NOT IN (
                SELECT ar4.AssistantID
                FROM ASSISTANCE_REQUEST ar4
                WHERE DATE(ar4.PreferredTime) = DATE('" . mysqli_real_escape_string($conn, $request['PreferredTime']) . "')
                AND ar4.Status = 'Accepted'
                AND ar4.AssistantID IS NOT NULL
             )
             ORDER BY AvgRating DESC";

$astResult = mysqli_query($conn, $astQuery);
?>
<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Yumnak | Handle Request</title>
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
            <a href="request-management.php" class="sidebar-item active">
                <i class="fa-solid fa-clipboard-list"></i> Request Management
            </a>
            <a href="Assistant_Management.php" class="sidebar-item">
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
                <h1>Handle Request</h1>
                <p>Review full traveler request information and assign a suitable assistant</p>
            </div>

            <div class="cards-row">
                <div class="small-table-container">
                    <div class="small-table-header-title">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-6">
                            <path fill-rule="evenodd"
                                d="M5.625 1.5c-1.036 0-1.875.84-1.875 1.875v17.25c0 1.035.84 1.875 1.875 1.875h12.75c1.035 0 1.875-.84 1.875-1.875V12.75A3.75 3.75 0 0 0 16.5 9h-1.875a1.875 1.875 0 0 1-1.875-1.875V5.25A3.75 3.75 0 0 0 9 1.5H5.625ZM7.5 15a.75.75 0 0 1 .75-.75h7.5a.75.75 0 0 1 0 1.5h-7.5A.75.75 0 0 1 7.5 15Zm.75 2.25a.75.75 0 0 0 0 1.5H12a.75.75 0 0 0 0-1.5H8.25Z"
                                clip-rule="evenodd" />
                            <path
                                d="M12.971 1.816A5.23 5.23 0 0 1 14.25 5.25v1.875c0 .207.168.375.375.375H16.5a5.23 5.23 0 0 1 3.434 1.279 9.768 9.768 0 0 0-6.963-6.963Z" />
                        </svg>
                        Request Summary
                    </div>
                    <table class="detail-table">
                        <tbody>
                            <tr>
                                <th>Request ID:</th>
                                <td><?php echo htmlspecialchars($request['RequestID']); ?></td>
                            </tr>
                            <tr>
                                <th>Request Status:</th>
                                <td><span
                                        class="status <?php echo strtolower($request['Status']); ?>"><?php echo htmlspecialchars($request['Status']); ?></span>
                                </td>
                            </tr>
                            <tr>
                                <th>Payment Status:</th>
                                <?php if ($request['IsPaid']): ?>
                                    <td><span class="status paid">Paid</span></td>
                                <?php else: ?>
                                    <td><span class="status pending">Unpaid</span></td>
                                <?php endif; ?>
                            </tr>
                            <tr>
                                <th>Date Submitted:</th>
                                <td><?php echo $submitDate; ?></td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="small-table-container">
                    <div class="small-table-header-title">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-6">
                            <path fill-rule="evenodd"
                                d="M7.5 6a4.5 4.5 0 1 1 9 0 4.5 4.5 0 0 1-9 0ZM3.751 20.105a8.25 8.25 0 0 1 16.498 0 .75.75 0 0 1-.437.695A18.683 18.683 0 0 1 12 22.5c-2.786 0-5.433-.608-7.812-1.7a.75.75 0 0 1-.437-.695Z"
                                clip-rule="evenodd" />
                        </svg>
                        Traveler Information
                    </div>
                    <table class="detail-table">
                        <tbody>
                            <tr>
                                <th>UserName:</th>
                                <td><?php echo htmlspecialchars($request['UserName']); ?></td>
                            </tr>
                            <tr>
                                <th>Age:</th>
                                <td><?php echo $age; ?> years</td>
                            </tr>
                            <tr>
                                <th>Traveler Type:</th>
                                <td><span
                                        class="status <?php echo ($age >= 18) ? 'adult' : 'pending'; ?>"><?php echo ($age >= 18) ? 'Adult' : 'Minor'; ?></span>
                                </td>
                            </tr>
                            <tr>
                                <th>Phone Number:</th>
                                <td><?php echo htmlspecialchars($request['Phone']); ?></td>
                            </tr>
                            <tr>
                                <th>Email Address:</th>
                                <td><?php echo htmlspecialchars($request['Email']); ?></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="table-container full-width-card">
                <div class="small-table-header-title">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                        <path fill-rule="evenodd"
                            d="M2.25 12c0-5.385 4.365-9.75 9.75-9.75s9.75 4.365 9.75 9.75-4.365 9.75-9.75 9.75S2.25 17.385 2.25 12Zm8.706-1.442c1.146-.573 2.437.463 2.126 1.706l-.709 2.836.042-.02a.75.75 0 0 1 .67 1.34l-.04.022c-1.147.573-2.438-.463-2.127-1.706l.71-2.836-.042.02a.75.75 0 1 1-.671-1.34l.041-.022ZM12 9a.75.75 0 1 0 0-1.5.75.75 0 0 0 0 1.5Z"
                            clip-rule="evenodd" />
                    </svg>
                    Assistance Request Details
                </div>
                <dl class="details-grid">
                    <div class="detail-block">
                        <dt>Airport:</dt>
                        <dd><?php echo htmlspecialchars($request['AirportName']); ?></dd>
                    </div>
                    <div class="detail-block">
                        <dt>Preferred Time:</dt>
                        <dd><?php echo $prefTime; ?></dd>
                    </div>
                    <div class="detail-block">
                        <dt>Entrance Gate:</dt>
                        <dd><?php echo htmlspecialchars($request['GateID']); ?></dd>
                    </div>
                    <div class="detail-block">
                        <dt>Extra Note:</dt>
                        <dd><?php echo htmlspecialchars($request['ExtraNote']); ?></dd>
                    </div>
                    <div class="detail-block border-none">
                        <dt>Assistance Type:</dt>
                        <dd>
                            <?php
                            // Split multiple assistance types (if any) and loop through them to create badges
                            $types = explode(',', $request['AssistanceTypes']);
                            foreach ($types as $type) {
                                echo '<span class="badge purple" style="margin-right: 5px;">' . htmlspecialchars(trim($type)) . '</span>';
                            }
                            ?>
                        </dd>
                    </div>
                    <div class="detail-block border-none">
                        <dt>Preferred date:</dt>
                        <dd><?php echo $prefDate; ?></dd>
                    </div>
                </dl>
            </div>

            <form method="POST" action="handeling.php?id=<?php echo $requestID; ?>">
                <input type="hidden" name="request_id" value="<?php echo $requestID; ?>">
                <input type="hidden" name="selected_assistant" id="selected_assistant_input" value="">

                <div class="table-container full-width-card">
                    <div class="small-table-header-title">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                            <path
                                d="M4.5 6.375a4.125 4.125 0 1 1 8.25 0 4.125 4.125 0 0 1-8.25 0ZM14.25 8.625a3.375 3.375 0 1 1 6.75 0 3.375 3.375 0 0 1-6.75 0ZM1.5 19.125a7.125 7.125 0 0 1 14.25 0v.003l-.001.119a.75.75 0 0 1-.363.63 13.067 13.067 0 0 1-6.761 1.873c-2.472 0-4.786-.684-6.76-1.873a.75.75 0 0 1-.364-.63l-.001-.122ZM17.25 19.128l-.001.144a2.25 2.25 0 0 1-.233.96 10.088 10.088 0 0 0 5.06-1.01.75.75 0 0 0 .42-.643 4.875 4.875 0 0 0-6.957-4.611 8.586 8.586 0 0 1 1.71 5.157v.003Z" />
                        </svg>
                        Available Assistants
                    </div>
                    <p class="assistants-subtitle">Select a suitable assistant who matches the assistance type and is available.</p>

                    <div class="assistant-cards-wrapper">
                        <?php while ($ast = mysqli_fetch_assoc($astResult)):
                            $rating = $ast['AvgRating'] ? $ast['AvgRating'] : '0.0';
                        ?>
                            <div class="assistant-card" id="card-<?php echo $ast['AssistantID']; ?>">
                                <div class="assistant-header">
                                    <img src="https://ui-avatars.com/api/?name=<?php echo urlencode($ast['Name']); ?>&background=random" alt="<?php echo htmlspecialchars($ast['Name']); ?>'s image">
                                    <div class="assistant-info">
                                        <h4><?php echo htmlspecialchars($ast['Name']); ?></h4>
                                        <span class="ast-id">ID: <?php echo htmlspecialchars($ast['AssistantID']); ?></span>
                                        <span
                                            class="badge purple"><?php echo htmlspecialchars($ast['Specialization']); ?></span>
                                    </div>
                                </div>
                                <div class="card-stats">
                                    <div class="stat-row"><span>Status:</span> <span class="status paid">Available</span>
                                    </div>
                                    <div class="stat-row"><span>Rating:</span> <span class="val">
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                                                class="size-6">
                                                <path fill-rule="evenodd"
                                                    d="M10.788 3.21c.448-1.077 1.976-1.077 2.424 0l2.082 5.006 5.404.434c1.164.093 1.636 1.545.749 2.305l-4.117 3.527 1.257 5.273c.271 1.136-.964 2.033-1.96 1.425L12 18.354 7.373 21.18c-.996.608-2.231-.29-1.96-1.425l1.257-5.273-4.117-3.527c-.887-.76-.415-2.212.749-2.305l5.404-.434 2.082-5.005Z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                            <?php echo $rating; ?>
                                        </span>
                                    </div>
                                    <div class="stat-row"><span>Completed:</span> <span
                                            class="val"><?php echo $ast['CompletedCount']; ?> requests</span></div>
                                </div>
                                <button type="button" class="btn-select" onclick="selectAssistant(<?php echo $ast['AssistantID']; ?>, this)">Select</button>
                            </div>
                        <?php endwhile; ?>
                    </div>
                </div>

                <div class="action-row">
                    <button type="submit" name="action" value="reject" class="btn-reject" onclick="return confirm('Are you sure you want to reject this request?');">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"
                            style="height: 16px;">
                            <path
                                d="M6.28 5.22a.75.75 0 0 0-1.06 1.06L8.94 10l-3.72 3.72a.75.75 0 1 0 1.06 1.06L10 11.06l3.72 3.72a.75.75 0 1 0 1.06-1.06L11.06 10l3.72-3.72a.75.75 0 0 0-1.06-1.06L10 8.94 6.28 5.22Z" />
                        </svg>
                        Reject Request
                    </button>
                    <button type="submit" name="action" value="assign" class="btn-assign" onclick="return validateAssignment();">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"
                            style="height: 16px;">
                            <path fill-rule="evenodd"
                                d="M10 18a8 8 0 1 0 0-16 8 8 0 0 0 0 16Zm3.857-9.809a.75.75 0 0 0-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 1 0-1.06 1.061l2.5 2.5a.75.75 0 0 0 1.137-.089l4-5.5Z"
                                clip-rule="evenodd" />
                        </svg>
                        Assign Assistant
                    </button>
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
                <p>Your companion for a world without barriers, making every journey at the airport easier and more
                    inclusive.</p>
            </div>
        </div>

        <div class="footer-bottom">
            &copy; 2026 Yumnak Platform. All rights reserved.
        </div>
    </footer>
    <script>
    function selectAssistant(assistantID, btnElement) {
        // 1. Save the clicked Assistant's ID into the hidden form input
        document.getElementById('selected_assistant_input').value = assistantID;

        // 2. Reset the styling of all cards and buttons back to normal
        let allCards = document.querySelectorAll('.assistant-card');
        allCards.forEach(card => card.style.border = '1px solid #e5e7eb');
        
        let allBtns = document.querySelectorAll('.btn-select');
        allBtns.forEach(btn => btn.innerText = 'Select');

        // 3. Highlight the specific card the Admin just clicked
        let selectedCard = document.getElementById('card-' + assistantID);
        selectedCard.style.border = '2px solid #6b21a8'; // Highlights it in Yumnak purple
        btnElement.innerText = 'Selected ✓';
    }

    function validateAssignment() {
        // Check if the hidden input is empty before letting the form submit
        let selected = document.getElementById('selected_assistant_input').value;
        if (selected === "") {
            alert("Please Select an Assistant from the list before assigning!");
            return false; // Stops the form from submitting
        }
        return true; // Allows the form to submit to the database
    }
</script>
</body>

</html>