<?php
// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Redirect if not logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Escape user name
$userName = htmlspecialchars($_SESSION['user_name']);

function renderNavLink($text, $href, $currentTitle) {
    $isActive = ($text === $currentTitle);

    if ($isActive) {
        echo "<span class=\"block px-2 py-1 rounded bg-gray-700 text-white cursor-default\">{$text}</span>";
    } else {
        echo "<a href=\"{$href}\" class=\"block px-2 py-1 rounded hover:bg-gray-700\">{$text}</a>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?= $title ?? 'Study Manager' ?></title>
    <link href="./assets/css/output.css" rel="stylesheet">
</head>
<body class="bg-gray-100 flex">

    <!-- Sidebar -->
    <aside class="w-64 h-screen bg-gray-800 text-white flex flex-col">
        <div class="p-4 font-bold text-xl border-b border-gray-700">
            Study Manager
        </div>
        <nav class="flex-1 p-4 space-y-2">
            <?php renderNavLink('Dashboard', 'dashboard.php', $title ?? ''); ?>
            <?php renderNavLink('Courses', 'courses.php', $title ?? ''); ?>
            <?php renderNavLink('Tasks', 'tasks.php', $title ?? ''); ?>
            <a href="events.php" class="block px-2 py-1 rounded hover:bg-gray-700">Events</a>
            <a href="analytics.php" class="block px-2 py-1 rounded hover:bg-gray-700">Analytics</a>
            <a href="profile.php" class="block px-2 py-1 rounded hover:bg-gray-700">Profile</a>
        </nav>
        <div class="p-4 border-t border-gray-700">
            <span class="block mb-2">Hello, <?= $userName ?></span>
            <a href="logout.php" class="block px-2 py-1 text-red-400 hover:bg-gray-700 rounded">Logout</a>
        </div>
    </aside>

    <main class="flex-1 p-6">
        <?= $content ?? '' ?>
    </main>

</body>
</html>
