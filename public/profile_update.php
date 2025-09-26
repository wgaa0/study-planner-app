<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

require_once __DIR__ . '/../src/lib/DB.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($name)) {
        die("Name cannot be empty.");
    }

    $pdo = DB::get();
    $params = [':name' => $name, ':id' => $_SESSION['user_id']];

    if (!empty($password)) {
        $password_hash = password_hash($password, PASSWORD_DEFAULT);
        $sql = "UPDATE users SET name = :name, password_hash = :password_hash WHERE id = :id";
        $params[':password_hash'] = $password_hash;
    } else {
        $sql = "UPDATE users SET name = :name WHERE id = :id";
    }

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);

    // Update session name immediately
    $_SESSION['user_name'] = $name;

    header("Location: profile.php?status=success");
    exit();
}
