<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
require_once __DIR__ . '/../src/lib/DB.php';

$pdo = DB::get();
$course_id = $_GET['course_id'] ?? null;
if (!$course_id) {
    die("No course selected.");
}

$stmt = $pdo->prepare("SELECT title FROM courses WHERE id = :cid AND user_id = :uid");
$stmt->execute([':cid' => $course_id, ':uid' => $_SESSION['user_id']]);
$course = $stmt->fetch();
if (!$course) {
    die("Course not found.");
}

$stmt = $pdo->prepare("SELECT * FROM resources WHERE course_id = :cid AND user_id = :uid ORDER BY uploaded_at DESC");
$stmt->execute([':cid' => $course_id, ':uid' => $_SESSION['user_id']]);
$resources = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Resources - <?= htmlspecialchars($course['title']) ?></title>
</head>
<body>
  <h1>Resources for <?= htmlspecialchars($course['title']) ?></h1>
  <p><a href="courses.php">Back to Courses</a></p>
  <p><a href="upload_resource.php?course_id=<?= $course_id ?>">+ Upload File</a></p>

  <?php if (count($resources) === 0): ?>
    <p>No resources uploaded yet.</p>
  <?php else: ?>
    <ul>
      <?php foreach ($resources as $res): ?>
        <li>
          <a href="../uploads/<?= htmlspecialchars($res['file_path']) ?>" target="_blank">
            <?= htmlspecialchars($res['file_name']) ?>
          </a>
          (<?= round($res['file_size'] / 1024, 1) ?> KB, <?= htmlspecialchars($res['file_type']) ?>)
          <small>Uploaded: <?= $res['uploaded_at'] ?></small>
          <br><a href="resource_delete.php?id=<?= $res['id'] ?>&course_id=<?= $course_id ?>" 
            onclick="return confirm('Delete this file?')">Delete
          </a>

        </li>
      <?php endforeach; ?>
    </ul>
  <?php endif; ?>
</body>
</html>
