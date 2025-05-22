<?php
session_start();

$serverName = "tcp:mydemosvraz.database.windows.net,1433";
$connectionOptions = array(
    "Database" => "mydemodb",
    "Uid" => "wiproadmin@mydemosvraz",
    "PWD" => "Server@1",
    "Encrypt" => true,
    "TrustServerCertificate" => false,
    "LoginTimeout" => 30
);

// Connect using SQLSRV
$conn = sqlsrv_connect($serverName, $connectionOptions);
if ($conn === false) {
    die(print_r(sqlsrv_errors(), true));
}

$login_msg = "";
$signup_msg = "";

// LOGIN
if (isset($_POST['login'])) {
    $username = $_POST['login_username'];
    $password = md5($_POST['login_password']);

    $sql = "SELECT * FROM users WHERE username=? AND password=?";
    $params = array($username, $password);
    $stmt = sqlsrv_query($conn, $sql, $params);

    if ($stmt && sqlsrv_has_rows($stmt)) {
        $_SESSION['username'] = $username;
        header("Location: welcome.php");
        exit();
    } else {
        $login_msg = "Invalid username or password!";
    }
}

// SIGNUP
if (isset($_POST['signup'])) {
    $username = $_POST['signup_username'];
    $password = md5($_POST['signup_password']);

    $check = "SELECT * FROM users WHERE username=?";
    $stmt = sqlsrv_query($conn, $check, array($username));

    if ($stmt && sqlsrv_has_rows($stmt)) {
        $signup_msg = "Username already exists!";
    } else {
        $insert = "INSERT INTO users (username, password) VALUES (?, ?)";
        $params = array($username, $password);
        $stmt = sqlsrv_query($conn, $insert, $params);
        if ($stmt) {
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
