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
  <a href="course_create.php" class="text-blue-500 hover:underline">+ Add New Course</a>
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
          <a href="course_edit.php?id=<?= $course['id'] ?>" class="text-green-500 hover:underline">Edit</a>
          <a href="course_process.php?action=delete&id=<?= $course['id'] ?>" 
             onclick="return confirm('Delete this course?')" 
             class="text-red-500 hover:underline">Delete</a>
        </div>
      </li>
    <?php endforeach; ?>
  </ul>
<?php endif; ?>
<?php
$content = ob_get_clean();

include __DIR__ . '/partials/layout.php';
