<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

require_once __DIR__ . '/../src/lib/DB.php';

$pdo = DB::get();
$action = $_POST['action'] ?? $_GET['action'] ?? null;

try {
    if ($action === 'create') {
        $title = $_POST['title'] ?? '';
        $desc = $_POST['description'] ?? '';
        if (empty($title)) {
            die("Title required.");
        }

        $stmt = $pdo->prepare("INSERT INTO courses (user_id, title, description) VALUES (:uid, :title, :desc)");
        $stmt->execute([
            ':uid' => $_SESSION['user_id'],
            ':title' => $title,
            ':desc' => $desc
        ]);

        header("Location: courses.php");
        exit();

    } elseif ($action === 'edit') {
        $id = $_POST['id'] ?? null;
        $title = $_POST['title'] ?? '';
        $desc = $_POST['description'] ?? '';
        if (!$id || empty($title)) {
            die("Invalid input.");
        }

        $stmt = $pdo->prepare("UPDATE courses SET title = :title, description = :desc WHERE id = :id AND user_id = :uid");
        $stmt->execute([
            ':title' => $title,
            ':desc' => $desc,
            ':id' => $id,
            ':uid' => $_SESSION['user_id']
        ]);

        header("Location: courses.php");
        exit();

    } elseif ($action === 'delete') {
        $id = $_GET['id'] ?? null;
        if (!$id) {
            die("Invalid course ID.");
        }

        $stmt = $pdo->prepare("DELETE FROM courses WHERE id = :id AND user_id = :uid");
        $stmt->execute([
            ':id' => $id,
            ':uid' => $_SESSION['user_id']
        ]);

        header("Location: courses.php");
        exit();
    } else {
        die("Invalid action.");
    }
} catch (PDOException $e) {
    die("Database error: " . $e->getMessage());
}
