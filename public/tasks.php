<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

require_once __DIR__ . '/../src/lib/DB.php';

$pdo = DB::get();

// Fetch tasks
$sql = "SELECT t.id, t.title, t.details, t.status, t.due_date, c.title AS course_title, c.id AS course_id
        FROM tasks t
        JOIN courses c ON t.course_id = c.id
        WHERE c.user_id = :uid
        ORDER BY t.created_at DESC";
$stmt = $pdo->prepare($sql);
$stmt->execute([':uid' => $_SESSION['user_id']]);
$tasks = $stmt->fetchAll();

// Fetch courses for dropdowns
$stmt = $pdo->prepare("SELECT id, title FROM courses WHERE user_id = :uid");
$stmt->execute([':uid' => $_SESSION['user_id']]);
$courses = $stmt->fetchAll();

$title = "Tasks";

ob_start();
?>
<h1 class="text-2xl font-bold mb-4">Your Tasks</h1>
<p class="mb-4">
  <button onclick="openCreateTaskModal()" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
    + Add New Task
  </button>
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
          <button 
            onclick="openEditTaskModal(<?= $task['id'] ?>, <?= $task['course_id'] ?>, '<?= htmlspecialchars($task['title'], ENT_QUOTES) ?>', '<?= htmlspecialchars($task['details'], ENT_QUOTES) ?>', '<?= $task['status'] ?>', '<?= $task['due_date'] ?>')" 
            class="text-green-500 hover:underline">
            Edit
          </button>
          <a href="task_process.php?action=delete&id=<?= $task['id'] ?>"
             onclick="return confirm('Delete this task?')"
             class="text-red-500 hover:underline">Delete</a>
        </div>
      </li>
    <?php endforeach; ?>
  </ul>
<?php endif; ?>

<!-- CREATE TASK MODAL -->
<div id="createTaskModal" class="fixed inset-0 bg-black bg-opacity-50 hidden flex items-center justify-center">
  <div class="bg-white p-6 rounded shadow-lg w-full max-w-md">
    <h2 class="text-xl font-bold mb-4">Create New Task</h2>
    <form action="task_process.php" method="POST">
      <input type="hidden" name="action" value="create">

      <div class="mb-3">
        <label class="block mb-1">Course:</label>
        <select name="course_id" class="w-full border p-2 rounded" required>
          <?php foreach ($courses as $course): ?>
            <option value="<?= $course['id'] ?>"><?= htmlspecialchars($course['title']) ?></option>
          <?php endforeach; ?>
        </select>
      </div>

      <div class="mb-3">
        <label class="block mb-1">Title:</label>
        <input type="text" name="title" class="w-full border p-2 rounded" required>
      </div>

      <div class="mb-3">
        <label class="block mb-1">Details:</label>
        <textarea name="details" class="w-full border p-2 rounded"></textarea>
      </div>

      <div class="mb-3">
        <label class="block mb-1">Due Date:</label>
        <input type="datetime-local" name="due_date" class="w-full border p-2 rounded">
      </div>

      <div class="mt-4 flex justify-end space-x-3">
        <button type="button" onclick="closeCreateTaskModal()" class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400">Cancel</button>
        <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">Save</button>
      </div>
    </form>
  </div>
</div>

<!-- EDIT TASK MODAL -->
<div id="editTaskModal" class="fixed inset-0 bg-black bg-opacity-50 hidden flex items-center justify-center">
  <div class="bg-white p-6 rounded shadow-lg w-full max-w-md">
    <h2 class="text-xl font-bold mb-4">Edit Task</h2>
    <form action="task_process.php" method="POST">
      <input type="hidden" name="action" value="edit">
      <input type="hidden" name="id" id="editTaskId">

      <div class="mb-3">
        <label class="block mb-1">Course:</label>
        <select name="course_id" id="editTaskCourse" class="w-full border p-2 rounded" required>
          <?php foreach ($courses as $course): ?>
            <option value="<?= $course['id'] ?>"><?= htmlspecialchars($course['title']) ?></option>
          <?php endforeach; ?>
        </select>
      </div>

      <div class="mb-3">
        <label class="block mb-1">Title:</label>
        <input type="text" name="title" id="editTaskTitle" class="w-full border p-2 rounded" required>
      </div>

      <div class="mb-3">
        <label class="block mb-1">Details:</label>
        <textarea name="details" id="editTaskDetails" class="w-full border p-2 rounded"></textarea>
      </div>

      <div class="mb-3">
        <label class="block mb-1">Status:</label>
        <select name="status" id="editTaskStatus" class="w-full border p-2 rounded">
          <option value="todo">To Do</option>
          <option value="in_progress">In Progress</option>
          <option value="done">Done</option>
        </select>
      </div>

      <div class="mb-3">
        <label class="block mb-1">Due Date:</label>
        <input type="datetime-local" name="due_date" id="editTaskDueDate" class="w-full border p-2 rounded">
      </div>

      <div class="mt-4 flex justify-end space-x-3">
        <button type="button" onclick="closeEditTaskModal()" class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400">Cancel</button>
        <button type="submit" class="px-4 py-2 bg-green-500 text-white rounded hover:bg-green-600">Update</button>
      </div>
    </form>
  </div>
</div>

<script>
function openCreateTaskModal() {
  document.getElementById('createTaskModal').classList.remove('hidden');
}
function closeCreateTaskModal() {
  document.getElementById('createTaskModal').classList.add('hidden');
}

function openEditTaskModal(id, courseId, title, details, status, dueDate) {
  document.getElementById('editTaskId').value = id;
  document.getElementById('editTaskCourse').value = courseId;
  document.getElementById('editTaskTitle').value = title;
  document.getElementById('editTaskDetails').value = details;
  document.getElementById('editTaskStatus').value = status;
  document.getElementById('editTaskDueDate').value = dueDate ? new Date(dueDate).toISOString().slice(0,16) : '';
  document.getElementById('editTaskModal').classList.remove('hidden');
}
function closeEditTaskModal() {
  document.getElementById('editTaskModal').classList.add('hidden');
}
</script>

<?php
$content = ob_get_clean();

include __DIR__ . '/partials/layout.php';
