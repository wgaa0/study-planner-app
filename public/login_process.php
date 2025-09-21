<?php
// Always start the session at the top of scripts that need it
session_start();

require_once __DIR__ . '/../src/lib/DB.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    if (empty($email) || empty($password)) {
        header("Location: login.php?error=Please fill all fields");
        exit();
    }

    try {
        $pdo = DB::get();

        // Find the user by email
        $stmt = $pdo->prepare("SELECT id, name, password_hash FROM users WHERE email = :email");
        $stmt->execute([':email' => $email]);
        $user = $stmt->fetch();

        // Verify user exists and the password is correct
        if ($user && password_verify($password, $user['password_hash'])) {
            // Login successful!
            
            // Regenerate session ID to prevent session fixation attacks
            session_regenerate_id(true);

            // Store user info in the session
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['name'];

            // Redirect to a protected dashboard page
            header("Location: dashboard.php");
            exit();
        } else {
            // Login failed
            header("Location: login.php?error=Invalid email or password");
            exit();
        }
    } catch (PDOException $e) {
        die("Database error: " . $e->getMessage());
    }
}