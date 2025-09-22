<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

require_once __DIR__ . '/../src/lib/DB.php';

$id = $_GET['id'] ?? null;
$course_id = $_GET['course_id'] ?? null;

if (!$id || !$course_id) {
    die("Invalid request.");
}

$pdo = DB::get();

// Fetch file info to delete from disk
$stmt = $pdo->prepare("SELECT file_path FROM resources WHERE id = :id AND user_id = :uid AND course_id = :cid");
$stmt->execute([
    ':id' => $id,
    ':uid' => $_SESSION['user_id'],
    ':cid' => $course_id
]);
$res = $stmt->fetch();

if (!$res) {
    die("Resource not found.");
}

// Delete file from disk
$filePath = __DIR__ . '/../uploads/' . $res['file_path'];
if (file_exists($filePath)) {
    unlink($filePath);
}

// Delete row from DB
$stmt = $pdo->prepare("DELETE FROM resources WHERE id = :id AND user_id = :uid AND course_id = :cid");
$stmt->execute([
    ':id' => $id,
    ':uid' => $_SESSION['user_id'],
    ':cid' => $course_id
]);

// Redirect back
header("Location: resources.php?course_id=" . $course_id);
exit();
