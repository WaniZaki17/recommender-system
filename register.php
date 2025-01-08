<?php
// Include the database connection
include('db_connect.php');
session_start();

$username_error = '';
$password_error = '';
$fullname_error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $fullname = $_POST['fullname'] ?? '';
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    // Validate input (basic check)
    if (empty($fullname)) {
        $fullname_error = 'Full Name is required!';
    }
    if (empty($username)) {
        $username_error = 'Username is required!';
    }
    if (empty($password)) {
        $password_error = 'Password is required!';
    }

    if (empty($username_error) && empty($password_error) && empty($fullname_error)) {
        // Check if the username already exists
        $sql = "SELECT id FROM users WHERE username = ?";
        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("s", $username); // Bind the username parameter

            // Execute query and check if the username already exists
            $stmt->execute();
            $stmt->store_result();

            if ($stmt->num_rows > 0) {
                $username_error = 'This username is already taken!';
            } else {
                // Username is available, insert the new user into the database
                $hashed_password = password_hash($password, PASSWORD_DEFAULT); // Hash the password
                $insert_sql = "INSERT INTO users (fullname, username, password) VALUES (?, ?, ?)";

                if ($insert_stmt = $conn->prepare($insert_sql)) {
                    $insert_stmt->bind_param("sss", $fullname, $username, $hashed_password); // Bind parameters

                    // Execute insert query
                    if ($insert_stmt->execute()) {
                        // Registration successful, redirect to welcome page
                        $_SESSION['username'] = $username;
                        $_SESSION['fullname'] = $fullname;
                        header("Location: welcome.php"); // Redirect to a welcome page
                        exit();
                    } else {
                        // Error inserting user into database
                        $password_error = 'Something went wrong. Please try again later.';
                    }
                    $insert_stmt->close();
                }
            }
            $stmt->close();
        }
    }
}
?>

<!-- HTML form for registration -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register Account</title>
    <link rel="stylesheet" href="assets/css/fontawesome.css">
    <link rel="stylesheet" href="assets/css/templatemo-edu-meeting.css">
    <link rel="stylesheet" href="assets/css/owl.css">
    <link rel="stylesheet" href="assets/css/lightbox.css">

    <style>
        body {
            background-color: #f4f4f4;
            font-family: Arial, sans-serif;
            background-image: url('path_to_your_image.jpg'); /* Add your image path here */
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
        }

        .register-container {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background-color: rgba(244, 244, 244, 0.8); /* Slight overlay for readability */
            width: 100%;
        }

        .form-box {
            width: 40%;
            background-color: white;
            padding: 30px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            z-index: 1;
        }

        h2 {
            text-align: center;
            margin-bottom: 30px;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .form-group input {
            width: 100%;
            padding: 10px;
            font-size: 14px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .register-btn {
            width: 100%;
            padding: 10px;
            font-size: 16px;
            font-weight: bold;
            color: white;
            background-color: green;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .register-btn:hover {
            background-color: darkgreen;
        }

        .login-link {
            display: block;
            text-align: center;
            margin-top: 15px;
            font-size: 14px;
            color: green;
            text-decoration: underline;
            cursor: pointer;
        }

        #error-message {
            color: red;
            text-align: center;
            margin-top: 10px;
            display: <?php echo (!empty($username_error)) ? 'block' : 'none'; ?>;
        }
    </style>
</head>

<body>
    <div class="register-container">
        <div class="form-box">
            <h2>Register Account</h2>
            <form id="registerForm" action="register.php" method="POST">
                <div class="form-group">
                    <label for="fullname">Full Name:</label>
                    <input type="text" id="fullname" name="fullname" value="<?php echo htmlspecialchars($fullname); ?>" required>
                    <span style="color: red;"><?php echo $fullname_error; ?></span>
                </div>
                <div class="form-group">
                    <label for="username">Username:</label>
                    <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($username); ?>" required>
                    <span style="color: red;"><?php echo $username_error; ?></span>
                </div>
                <div class="form-group">
                    <label for="password">Password:</label>
                    <input type="password" id="password" name="password" required>
                    <span style="color: red;"><?php echo $password_error; ?></span>
                </div>
                <div id="error-message">This username already exists. Please choose a different one.</div>
                <button type="submit" class="register-btn">Register</button>
            </form>
            <a href="login.html" class="login-link">Already Have an Account?</a>
        </div>
    </div>
</body>
</html>



