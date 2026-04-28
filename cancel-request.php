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

$requestID = $_GET['id'];
$travelerID = $_SESSION['user_id'];

$sql = "
    UPDATE ASSISTANCE_REQUEST
    SET Status = 'Cancelled'
    WHERE RequestID = ?
    AND TravelerID = ?
    AND Status IN ('Pending', 'Accepted')
";

$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "ii", $requestID, $travelerID);
mysqli_stmt_execute($stmt);

header("Location: my-requests.php");
exit();
?>