<?php
session_start(); // Start the session

// Check if the user is logged in by verifying if $_SESSION['username'] is set
if (!isset($_SESSION['username'])) {
    header("Location: login.php"); // Redirect to login if not logged in
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Welcome</title>
    <link rel="stylesheet" type="text/css" href="styles.css">
</head>
<body>
<div class="container">
    <h2>Welcome, <?php echo $_SESSION['username']; ?>!</h2>
    <p>You are logged in.</p>
    <a href="logout.php">Logout</a>
</div>
</body>
</html>
