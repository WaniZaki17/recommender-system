<?php
// db_connect.php

$servername = "localhost"; // localhost is fine if MySQL is on the same server
$username = "root";        // "root" is the default username for MySQL in XAMPP
$password = "";            // Default password is empty for XAMPP
$dbname = "learningstyle"; // Make sure the database exists in MySQL

// Create a connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
// Connection is successful, no need to echo a success message in production
?>
