<?php
require_once __DIR__ . '/../src/lib/DB.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'] ?? '';
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    if (empty($name) || empty($email) || empty($password)) {
        die("Please fill all required fields.");
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        die("Invalid email format.");
    }

    $password_hash = password_hash($password, PASSWORD_DEFAULT);

    try {
        $pdo = DB::get();

        $sql = "INSERT INTO users (name, email, password_hash) VALUES (:name, :email, :password_hash)";
        $stmt = $pdo->prepare($sql);
        
        $stmt->execute([
            ':name' => $name,
            ':email' => $email,
            ':password_hash' => $password_hash
        ]);

        header("Location: login.php?status=success");
        exit();

    } catch (PDOException $e) {
        if ($e->getCode() == 23000) {
            die("Error: This email address is already registered.");
        }
        die("Database error: " . $e->getMessage());
    }
}