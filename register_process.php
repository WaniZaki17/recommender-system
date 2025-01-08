<?php
// register_process.php

// Connection to the MySQL database
$servername = "localhost";  // MySQL host (use 'localhost' or IP address)
$username = "root";         // MySQL username
$password = "";             // MySQL password
$dbname = "learningstyle.db";  // Name of your MySQL database

$conn = new mysqli($servername, $username, $password, $dbname);

// Check if the connection was successful
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get form data
$fullname = $_POST['fullname'];
$username = $_POST['username'];
$password = $_POST['password']; // Keep the password as plain text

// Check if the username already exists
$sql = "SELECT * FROM users WHERE username = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // Username already exists
    echo "exists";
} else {
    // Insert data into the users table (no hashing of password)
    $sql = "INSERT INTO users (username, fullname, password) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $username, $fullname, $password); // Store password as plain text

    if ($stmt->execute()) {
        // Registration successful
        echo "success";
    } else {
        // Error saving data
        echo "error";
    }
}

// Close the connection
$conn->close();
?>


