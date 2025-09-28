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

$title = "Tasks";

ob_start();
?>
<h1 class="text-2xl font-bold mb-4">Your Tasks</h1>
<p class="mb-4">
  <a href="task_create.php" class="text-blue-500 hover:underline">+ Add New Task</a>
</p>

<?php if (count($tasks) === 0): ?>
  <p class="text-gray-600">No tasks yet. Add one!</p>
<?php else: ?>
  <ul class="space-y-4">
    <?php foreach ($tasks as $task): ?>
      <li class="p-4 bg-white rounded shadow">
        <h2 class="font-semibold text-lg"><?= htmlspecialchars($task['title']) ?></h2>
        <p class="text-gray-700"><?= nl2br(htmlspecialchars($task['details'])) ?></p>
        <p class="text-sm text-gray-500">Course: <?= htmlspecialchars($task['course_title']) ?></p>
        <p class="text-sm text-gray-500">Status: <?= htmlspecialchars($task['status']) ?></p>
        <p class="text-sm text-gray-500">Due: <?= $task['due_date'] ? $task['due_date'] : 'None' ?></p>
        <div class="mt-2 space-x-3">
          <a href="task_edit.php?id=<?= $task['id'] ?>" class="text-green-500 hover:underline">Edit</a>
          <a href="task_process.php?action=delete&id=<?= $task['id'] ?>"
             onclick="return confirm('Delete this task?')"
             class="text-red-500 hover:underline">Delete</a>
        </div>
      </li>
    <?php endforeach; ?>
  </ul>
<?php endif; ?>
<?php
$content = ob_get_clean();

include __DIR__ . '/partials/layout.php';
