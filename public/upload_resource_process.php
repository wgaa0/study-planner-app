<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

require_once __DIR__ . '/../src/lib/DB.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $course_id = $_POST['course_id'] ?? null;
    if (!$course_id || !isset($_FILES['file'])) {
        die("Invalid request.");
    }

    $file = $_FILES['file'];

    // Validate upload success
    if ($file['error'] !== UPLOAD_ERR_OK) {
        die("File upload error.");
    }

    // Validate size (max ~2MB for example)
    if ($file['size'] > 2 * 1024 * 1024) {
        die("File too large. Max 2MB allowed.");
    }

    // Validate type (basic: pdf, images, docs)
    $allowed = ['application/pdf', 'image/jpeg', 'image/png', 'text/plain'];
    if (!in_array($file['type'], $allowed)) {
        die("Invalid file type.");
    }

    // Generate safe filename
    $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
    $safeName = uniqid() . "." . $ext;

    // Move to uploads folder
    $destPath = __DIR__ . '/../uploads/' . $safeName;
    if (!move_uploaded_file($file['tmp_name'], $destPath)) {
        die("Failed to save file.");
    }

    // Save metadata in DB
    $pdo = DB::get();
    $stmt = $pdo->prepare("INSERT INTO resources (course_id, user_id, file_name, file_path, file_type, file_size) 
                           VALUES (:cid, :uid, :fname, :fpath, :ftype, :fsize)");
    $stmt->execute([
        ':cid' => $course_id,
        ':uid' => $_SESSION['user_id'],
        ':fname' => $file['name'], // original name
        ':fpath' => $safeName, // stored name
        ':ftype' => $file['type'],
        ':fsize' => $file['size']
    ]);

    header("Location: resources.php?course_id=" . $course_id);
    exit();
}
