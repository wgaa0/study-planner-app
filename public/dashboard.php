<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$title = "Dashboard";

ob_start();
?>
<h1 class="text-2xl font-bold mb-4">Welcome, <?= htmlspecialchars($_SESSION['user_name']) ?>!</h1>
<p class="mb-4">This is your protected dashboard area.</p>
<p>Select a page from the sidebar to get started.</p>
<?php
$content = ob_get_clean();

include __DIR__ . '/partials/layout.php';
