<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

require_once __DIR__ . '/../src/lib/DB.php';
$pdo = DB::get();

$stmt = $pdo->prepare("SELECT name, email FROM users WHERE id = :id");
$stmt->execute([':id' => $_SESSION['user_id']]);
$user = $stmt->fetch();

if (!$user) {
    die("User not found.");
}

$title = "Profile";

ob_start();
?>
<h1 class="text-2xl font-bold mb-4">Your Profile</h1>
<form action="profile_update.php" method="POST" class="space-y-4 bg-white p-6 rounded shadow w-full max-w-md">
    <div>
        <label for="name" class="block font-medium">Name:</label>
        <input type="text" name="name" id="name"
               value="<?= htmlspecialchars($user['name']) ?>"
               required
               class="mt-1 p-2 border rounded w-full">
    </div>
    <div>
        <label for="email" class="block font-medium">Email (read-only):</label>
        <input type="email" value="<?= htmlspecialchars($user['email']) ?>" disabled
               class="mt-1 p-2 border rounded w-full bg-gray-100">
    </div>
    <div>
        <label for="password" class="block font-medium">New Password (leave blank to keep current):</label>
        <input type="password" name="password" id="password"
               class="mt-1 p-2 border rounded w-full">
    </div>
    <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">
        Update Profile
    </button>
</form>
<?php
$content = ob_get_clean();

include __DIR__ . '/partials/layout.php';
