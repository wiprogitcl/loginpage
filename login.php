<?php
session_start();

$conn = new mysqli("mydemosvraz.database.windows.net", "wiproadmin", "Server@1", "mydemodb", 3306, NULL, MYSQLI_CLIENT_SSL);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Initialize variables to avoid undefined variable warnings
$login_msg = "";
$signup_msg = "";

// LOGIN FORM PROCESSING
if (isset($_POST['login'])) {
    $username = $_POST['login_username'];
    $password = md5($_POST['login_password']); // MD5 for demo

    $sql = "SELECT * FROM users WHERE username='$username' AND password='$password'";
    $result = $conn->query($sql);

    if ($result && $result->num_rows == 1) {
        $_SESSION['username'] = $username;
        header("Location: welcome.php");
        exit();
    } else {
        $login_msg = "Invalid username or password!";
    }
}

// SIGNUP FORM PROCESSING
if (isset($_POST['signup'])) {
    $username = $_POST['signup_username'];
    $password = md5($_POST['signup_password']);

    // Check if user already exists
    $check = "SELECT * FROM users WHERE username='$username'";
    $result = $conn->query($check);

    if ($result && $result->num_rows > 0) {
        $signup_msg = "Username already exists!";
    } else {
        $insert = "INSERT INTO users (username, password) VALUES ('$username', '$password')";
        if ($conn->query($insert)) {
            $signup_msg = "Signup successful! Please login.";
        } else {
            $signup_msg = "Error during signup.";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login & Signup</title>
    <link rel="stylesheet" type="text/css" href="styles.css">
</head>
<body>
<div class="container">
    <h2>Login</h2>
    <form method="POST">
        <input type="text" name="login_username" placeholder="Username" required>
        <input type="password" name="login_password" placeholder="Password" required>
        <input type="submit" name="login" value="Login">
    </form>
    <p class="message error"><?php echo $login_msg; ?></p>

    <hr>

    <h2>Sign Up</h2>
    <form method="POST">
        <input type="text" name="signup_username" placeholder="Username" required>
        <input type="password" name="signup_password" placeholder="Password" required>
        <input type="submit" name="signup" value="Sign Up">
    </form>
    <p class="message success"><?php echo $signup_msg; ?></p>
</div>
</body>
</html>
