<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

require_once __DIR__ . '/../src/lib/DB.php';

$pdo = DB::get();
$stmt = $pdo->prepare("SELECT id, title FROM courses WHERE user_id = :uid");
$stmt->execute([':uid' => $_SESSION['user_id']]);
$courses = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Create Task</title>
</head>
<body>
  <h1>Create New Task</h1>
  <form action="task_process.php" method="POST">
    <input type="hidden" name="action" value="create">

    <div>
      <label for="course">Course:</label>
      <select name="course_id" id="course" required>
        <?php foreach ($courses as $course): ?>
          <option value="<?= $course['id'] ?>"><?= htmlspecialchars($course['title']) ?></option>
        <?php endforeach; ?>
      </select>
    </div>

    <div>
      <label for="title">Title:</label>
      <input type="text" name="title" id="title" required>
    </div>

    <div>
      <label for="details">Details:</label><br>
      <textarea name="details" id="details"></textarea>
    </div>

    <div>
      <label for="due_date">Due Date:</label>
      <input type="datetime-local" name="due_date" id="due_date">
    </div>

    <button type="submit">Save</button>
  </form>
  <p><a href="tasks.php">Back to Tasks</a></p>
</body>
</html>
