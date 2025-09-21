<?php
// Start the session
session_start();

// Check if the user is logged in. If not, redirect to the login page.
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Use htmlspecialchars to prevent XSS attacks when displaying user data
$userName = htmlspecialchars($_SESSION['user_name']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
</head>
<body>
    <h1>Welcome to your Dashboard, <?= $userName ?>!</h1>
    <p>This is a protected area.</p>
    <p><a href="courses.php">View Courses</a></p>
    <a href="logout.php">Logout</a>
</body>
</html>