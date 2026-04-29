<?php
session_start();
require 'db_connection.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: Index.php");
    exit();
}

if (!isset($_GET['id'])) {
    header("Location: my-requests.php");
    exit();
}

$requestID = $_GET['id'];
$travelerID = $_SESSION['user_id'];

$sql = "
  UPDATE ASSISTANCE_REQUEST
SET Status = 'Cancelled',
    AssistantID = NULL
WHERE RequestID = ?
AND TravelerID = ?
AND Status IN ('Pending', 'Accepted')
";

$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "ii", $requestID, $travelerID);
if (mysqli_stmt_execute($stmt)) {
    if (mysqli_stmt_affected_rows($stmt) > 0) {
        header("Location: my-requests.php?msg=cancelled");
    } else {
        header("Location: my-requests.php?msg=failed");
    }
} else {
    die("Error: " . mysqli_error($conn));
}

exit();
?>