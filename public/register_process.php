<?php
// Include your DB connection class
require_once __DIR__ . '/../src/lib/DB.php';

// Check if the form was submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 1. Get user input
    $name = $_POST['name'] ?? '';
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    // 2. Basic Validation (you can add more later)
    if (empty($name) || empty($email) || empty($password)) {
        die("Please fill all required fields.");
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        die("Invalid email format.");
    }

    // 3. Hash the password for security 🛡️
    $password_hash = password_hash($password, PASSWORD_DEFAULT);

    try {
        // 4. Get PDO instance
        $pdo = DB::get();

        // 5. Prepare and execute the SQL statement to insert the new user
        $sql = "INSERT INTO users (name, email, password_hash) VALUES (:name, :email, :password_hash)";
        $stmt = $pdo->prepare($sql);
        
        $stmt->execute([
            ':name' => $name,
            ':email' => $email,
            ':password_hash' => $password_hash
        ]);

        // 6. Redirect to the login page on success
        header("Location: login.php?status=success");
        exit();

    } catch (PDOException $e) {
        // Check if it's a duplicate email error
        if ($e->getCode() == 23000) {
            die("Error: This email address is already registered.");
        }
        die("Database error: " . $e->getMessage());
    }
}