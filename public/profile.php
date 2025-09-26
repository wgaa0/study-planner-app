<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

require_once __DIR__ . '/../src/lib/DB.php';
$pdo = DB::get();

// Fetch current user data
$stmt = $pdo->prepare("SELECT name, email FROM users WHERE id = :id");
$stmt->execute([':id' => $_SESSION['user_id']]);
$user = $stmt->fetch();

if (!$user) {
    die("User not found.");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Profile</title>
</head>
<body>
    <h1>Your Profile</h1>
    <form action="profile_update.php" method="POST">
        <div>
            <label for="name">Name:</label>
            <input type="text" name="name" id="name" value="<?= htmlspecialchars($user['name']) ?>" required>
        </div>
        <div>
            <label for="email">Email (read-only):</label>
            <input type="email" value="<?= htmlspecialchars($user['email']) ?>" disabled>
        </div>
        <div>
            <label for="password">New Password (leave blank to keep current):</label>
            <input type="password" name="password" id="password">
        </div>
        <button type="submit">Update Profile</button>
    </form>
    <p><a href="dashboard.php">Back to Dashboard</a></p>
</body>
</html>
