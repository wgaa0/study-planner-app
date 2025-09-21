<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
</head>
<body>
    <h2>Login to Your Account</h2>
    
    <?php if (isset($_GET['status']) && $_GET['status'] == 'success'): ?>
        <p style="color: green;">Registration successful! Please login.</p>
    <?php endif; ?>

    <?php if (isset($_GET['error'])): ?>
        <p style="color: red;"><?= htmlspecialchars($_GET['error']) ?></p>
    <?php endif; ?>

    <form action="login_process.php" method="POST">
        <div>
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>
        </div>
        <div>
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>
        </div>
        <button type="submit">Login</button>
    </form>
    <p>Don't have an account? <a href="register.php">Register here</a>.</p>
</body>
</html>