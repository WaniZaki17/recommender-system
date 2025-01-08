<?php
// Database connection
$dsn = 'sqlite:learningstyle.db'; // Change this if your DB is located elsewhere
$username = 'root'; // Add your database username
$password = ''; // Add your database password
$options = [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION];

try {
    $db = new PDO($dsn, $username, $password, $options);
} catch (PDOException $e) {
    echo 'Connection failed: ' . $e->getMessage();
    exit;
}

// Retrieve form data
$user = $_POST['username'];
$pass = $_POST['password'];

// Query to check if the username exists
$query = "SELECT * FROM users WHERE username = :username";
$stmt = $db->prepare($query);
$stmt->bindParam(':username', $user);
$stmt->execute();

$userData = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$userData) {
    // If the username does not exist
    echo 'user_not_found';
    exit;
} else {
    // If the username exists, check the password
    if (password_verify($pass, $userData['password'])) {
        // If password is correct
        echo 'success';
    } else {
        // If password is incorrect
        echo 'wrong_password';
    }
}
?>
