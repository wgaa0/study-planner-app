<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

require_once __DIR__ . '/../src/lib/DB.php';

$id = $_GET['id'] ?? null;
if (!$id) {
    header("Location: tasks.php");
    exit();
}

$pdo = DB::get();

$sql = "SELECT t.id, t.title, t.details, t.status, t.due_date, c.id AS course_id
        FROM tasks t
        JOIN courses c ON t.course_id = c.id
        WHERE t.id = :id AND c.user_id = :uid";
$stmt = $pdo->prepare($sql);
$stmt->execute([':id' => $id, ':uid' => $_SESSION['user_id']]);
$task = $stmt->fetch();

if (!$task) {
    die("Task not found.");
}

$stmt = $pdo->prepare("SELECT id, title FROM courses WHERE user_id = :uid");
$stmt->execute([':uid' => $_SESSION['user_id']]);
$courses = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Edit Task</title>
</head>
<body>
  <h1>Edit Task</h1>
  <form action="task_process.php" method="POST">
    <input type="hidden" name="action" value="edit">
    <input type="hidden" name="id" value="<?= $task['id'] ?>">

    <div>
      <label for="course">Course:</label>
      <select name="course_id" id="course" required>
        <?php foreach ($courses as $course): ?>
          <option value="<?= $course['id'] ?>" <?= $course['id'] == $task['course_id'] ? 'selected' : '' ?>>
            <?= htmlspecialchars($course['title']) ?>
          </option>
        <?php endforeach; ?>
      </select>
    </div>

    <div>
      <label for="title">Title:</label>
      <input type="text" name="title" id="title" value="<?= htmlspecialchars($task['title']) ?>" required>
    </div>

    <div>
      <label for="details">Details:</label><br>
      <textarea name="details" id="details"><?= htmlspecialchars($task['details']) ?></textarea>
    </div>

    <div>
      <label for="status">Status:</label>
      <select name="status" id="status">
        <option value="todo" <?= $task['status'] === 'todo' ? 'selected' : '' ?>>To Do</option>
        <option value="in_progress" <?= $task['status'] === 'in_progress' ? 'selected' : '' ?>>In Progress</option>
        <option value="done" <?= $task['status'] === 'done' ? 'selected' : '' ?>>Done</option>
      </select>
    </div>

    <div>
      <label for="due_date">Due Date:</label>
      <input type="datetime-local" name="due_date" id="due_date"
             value="<?= $task['due_date'] ? date('Y-m-d\TH:i', strtotime($task['due_date'])) : '' ?>">
    </div>

    <button type="submit">Update</button>
  </form>
  <p><a href="tasks.php">Back to Tasks</a></p>
</body>
</html>
