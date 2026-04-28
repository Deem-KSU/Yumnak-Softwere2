<?php
// 1. Import the connection file
require 'db_connection.php';

// 2. If the connection failed, the 'die()' function in db_connection.php 
// would have stopped the script. So if the code reaches this line, it's a success!
echo "<h1>🎉 Success!</h1>";
echo "<p>You successfully imported the connection file, and your database is connected.</p>";
?>