<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

require_once __DIR__ . '/../src/lib/DB.php';

$id = $_GET['id'] ?? null;
if (!$id) {
    header("Location: courses.php");
    exit();
}

$pdo = DB::get();
$stmt = $pdo->prepare("SELECT id, title, description FROM courses WHERE id = :id AND user_id = :uid");
$stmt->execute([':id' => $id, ':uid' => $_SESSION['user_id']]);
$course = $stmt->fetch();

if (!$course) {
    die("Course not found.");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Edit Course</title>
</head>
<body>
  <h1>Edit Course</h1>
  <form action="course_process.php" method="POST">
    <input type="hidden" name="action" value="edit">
    <input type="hidden" name="id" value="<?= $course['id'] ?>">
    <div>
      <label for="title">Title:</label>
      <input type="text" name="title" id="title" value="<?= htmlspecialchars($course['title']) ?>" required>
    </div>
    <div>
      <label for="description">Description:</label><br>
      <textarea name="description" id="description"><?= htmlspecialchars($course['description']) ?></textarea>
    </div>
    <button type="submit">Update</button>
  </form>
  <p><a href="courses.php">Back to Courses</a></p>
</body>
</html>
