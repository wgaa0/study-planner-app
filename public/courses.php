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

$title = "Courses";

ob_start();
?>
<h1 class="text-2xl font-bold mb-4">Your Courses</h1>
<p class="mb-4">
  <button onclick="openCreateModal()" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
    + Add New Course
  </button>
</p>

<?php if (count($courses) === 0): ?>
  <p class="text-gray-600">No courses yet. Add one!</p>
<?php else: ?>
  <ul class="space-y-4">
    <?php foreach ($courses as $course): ?>
      <li class="p-4 bg-white rounded shadow">
        <h2 class="font-semibold text-lg"><?= htmlspecialchars($course['title']) ?></h2>
        <p class="text-gray-700"><?= nl2br(htmlspecialchars($course['description'])) ?></p>
        <p class="text-sm text-gray-500">Created: <?= $course['created_at'] ?></p>
        <div class="mt-2 space-x-3">
          <a href="resources.php?course_id=<?= $course['id'] ?>" class="text-blue-500 hover:underline">Resources</a>
          <button 
            onclick="openEditModal(<?= $course['id'] ?>, '<?= htmlspecialchars($course['title'], ENT_QUOTES) ?>', '<?= htmlspecialchars($course['description'], ENT_QUOTES) ?>')" 
            class="text-green-500 hover:underline">
            Edit
          </button>
          <a href="course_process.php?action=delete&id=<?= $course['id'] ?>" 
             onclick="return confirm('Delete this course?')" 
             class="text-red-500 hover:underline">Delete</a>
        </div>
      </li>
    <?php endforeach; ?>
  </ul>
<?php endif; ?>

<!-- CREATE MODAL -->
<div id="createModal" class="fixed inset-0 bg-black bg-opacity-50 hidden flex items-center justify-center">
  <div class="bg-white p-6 rounded shadow-lg w-full max-w-md">
    <h2 class="text-xl font-bold mb-4">Create New Course</h2>
    <form action="course_process.php" method="POST">
      <input type="hidden" name="action" value="create">
      <div class="mb-3">
        <label class="block mb-1">Title:</label>
        <input type="text" name="title" class="w-full border p-2 rounded" required>
      </div>
      <div class="mb-3">
        <label class="block mb-1">Description:</label>
        <textarea name="description" class="w-full border p-2 rounded"></textarea>
      </div>
      <div class="flex justify-end space-x-3">
        <button type="button" onclick="closeCreateModal()" class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400">Cancel</button>
        <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">Save</button>
      </div>
    </form>
  </div>
</div>

<!-- EDIT MODAL -->
<div id="editModal" class="fixed inset-0 bg-black bg-opacity-50 hidden flex items-center justify-center">
  <div class="bg-white p-6 rounded shadow-lg w-full max-w-md">
    <h2 class="text-xl font-bold mb-4">Edit Course</h2>
    <form action="course_process.php" method="POST">
      <input type="hidden" name="action" value="edit">
      <input type="hidden" name="id" id="editCourseId">
      <div class="mb-3">
        <label class="block mb-1">Title:</label>
        <input type="text" name="title" id="editCourseTitle" class="w-full border p-2 rounded" required>
      </div>
      <div class="mb-3">
        <label class="block mb-1">Description:</label>
        <textarea name="description" id="editCourseDescription" class="w-full border p-2 rounded"></textarea>
      </div>
      <div class="flex justify-end space-x-3">
        <button type="button" onclick="closeEditModal()" class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400">Cancel</button>
        <button type="submit" class="px-4 py-2 bg-green-500 text-white rounded hover:bg-green-600">Update</button>
      </div>
    </form>
  </div>
</div>

<script>
function openCreateModal() {
  document.getElementById('createModal').classList.remove('hidden');
}
function closeCreateModal() {
  document.getElementById('createModal').classList.add('hidden');
}

function openEditModal(id, title, description) {
  document.getElementById('editCourseId').value = id;
  document.getElementById('editCourseTitle').value = title;
  document.getElementById('editCourseDescription').value = description;
  document.getElementById('editModal').classList.remove('hidden');
}
function closeEditModal() {
  document.getElementById('editModal').classList.add('hidden');
}
</script>

<?php
$content = ob_get_clean();

include __DIR__ . '/partials/layout.php';
