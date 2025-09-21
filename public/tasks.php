<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

require_once __DIR__ . '/../src/lib/DB.php';

$pdo = DB::get();
$sql = "SELECT t.id, t.title, t.details, t.status, t.due_date, c.title AS course_title
        FROM tasks t
        JOIN courses c ON t.course_id = c.id
        WHERE c.user_id = :uid
        ORDER BY t.created_at DESC";
$stmt = $pdo->prepare($sql);
$stmt->execute([':uid' => $_SESSION['user_id']]);
$tasks = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Your Tasks</title>
</head>
<body>
  <h1>Tasks</h1>
  <p><a href="dashboard.php">Back to Dashboard</a></p>
  <p><a href="task_create.php">+ Add New Task</a></p>

  <?php if (count($tasks) === 0): ?>
    <p>No tasks yet. Add one!</p>
  <?php else: ?>
    <ul>
      <?php foreach ($tasks as $task): ?>
        <li>
          <strong><?= htmlspecialchars($task['title']) ?></strong> (<?= htmlspecialchars($task['status']) ?>)<br>
          <em>Course: <?= htmlspecialchars($task['course_title']) ?></em><br>
          <?= nl2br(htmlspecialchars($task['details'])) ?><br>
          Due: <?= $task['due_date'] ? $task['due_date'] : 'None' ?><br>
          <a href="task_edit.php?id=<?= $task['id'] ?>">Edit</a> | 
          <a href="task_process.php?action=delete&id=<?= $task['id'] ?>" onclick="return confirm('Delete this task?')">Delete</a>
        </li>
      <?php endforeach; ?>
    </ul>
  <?php endif; ?>
</body>
</html>
