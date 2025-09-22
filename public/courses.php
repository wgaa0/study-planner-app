<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

require_once __DIR__ . '/../src/lib/DB.php';

$pdo = DB::get();
$stmt = $pdo->prepare("SELECT id, title, description, created_at FROM courses WHERE user_id = :uid ORDER BY created_at DESC");
$stmt->execute([':uid' => $_SESSION['user_id']]);
$courses = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Your Courses</title>
</head>
<body>
  <h1>Courses</h1>
  <p><a href="dashboard.php">Back to Dashboard</a></p>
  <p><a href="course_create.php">+ Add New Course</a></p>

  <?php if (count($courses) === 0): ?>
    <p>No courses yet. Add one!</p>
  <?php else: ?>
    <ul>
      <?php foreach ($courses as $course): ?>
        <li>
          <strong><?= htmlspecialchars($course['title']) ?></strong><br>
          <?= nl2br(htmlspecialchars($course['description'])) ?><br>
          <a href="resources.php?course_id=<?= $course['id'] ?>">Resources</a><br>
          <small>Created: <?= $course['created_at'] ?></small><br>
          <a href="course_edit.php?id=<?= $course['id'] ?>">Edit</a> | 
          <a href="course_process.php?action=delete&id=<?= $course['id'] ?>" onclick="return confirm('Delete this course?')">Delete</a>
        </li>
      <?php endforeach; ?>
    </ul>
  <?php endif; ?>
</body>
</html>
