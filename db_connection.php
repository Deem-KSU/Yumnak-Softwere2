<?php
$servername = "localhost:8889";
$username = "root";
$password = "root"; 
$dbname = "YumnakDB";
//$port = 3306; 

$conn = mysqli_connect($servername, $username, $password, $dbname);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>
