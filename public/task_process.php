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
        $course_id = $_POST['course_id'] ?? null;
        $title = $_POST['title'] ?? '';
        $details = $_POST['details'] ?? '';
        $due_date = $_POST['due_date'] ?? null;

        if (empty($course_id) || empty($title)) {
            die("Course and title required.");
        }

        $stmt = $pdo->prepare("INSERT INTO tasks (course_id, title, details, due_date) VALUES (:cid, :title, :details, :due_date)");
        $stmt->execute([
            ':cid' => $course_id,
            ':title' => $title,
            ':details' => $details,
            ':due_date' => $due_date ?: null
        ]);

        header("Location: tasks.php");
        exit();

    } elseif ($action === 'edit') {
        $id = $_POST['id'] ?? null;
        $course_id = $_POST['course_id'] ?? null;
        $title = $_POST['title'] ?? '';
        $details = $_POST['details'] ?? '';
        $status = $_POST['status'] ?? 'todo';
        $due_date = $_POST['due_date'] ?? null;

        if (!$id || empty($course_id) || empty($title)) {
            die("Invalid input.");
        }

        $stmt = $pdo->prepare("UPDATE tasks SET course_id = :cid, title = :title, details = :details, status = :status, due_date = :due_date WHERE id = :id");
        $stmt->execute([
            ':cid' => $course_id,
            ':title' => $title,
            ':details' => $details,
            ':status' => $status,
            ':due_date' => $due_date ?: null,
            ':id' => $id
        ]);

        header("Location: tasks.php");
        exit();

    } elseif ($action === 'delete') {
        $id = $_GET['id'] ?? null;
        if (!$id) {
            die("Invalid task ID.");
        }

        $stmt = $pdo->prepare("DELETE FROM tasks WHERE id = :id");
        $stmt->execute([':id' => $id]);

        header("Location: tasks.php");
        exit();
    } else {
        die("Invalid action.");
    }
} catch (PDOException $e) {
    die("Database error: " . $e->getMessage());
}
