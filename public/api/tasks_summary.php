<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized']);
    exit();
}

require_once __DIR__ . '/../../src/lib/DB.php';
$pdo = DB::get();

$userId = $_SESSION['user_id'];
header('Content-Type: application/json');

try {
    $stmt = $pdo->prepare("
        SELECT YEARWEEK(updated_at, 1) AS week, COUNT(*) AS completed_count
        FROM tasks
        WHERE status = 'done'
        AND course_id IN (SELECT id FROM courses WHERE user_id = :uid)
        GROUP BY YEARWEEK(updated_at, 1)
        ORDER BY week ASC
    ");
    $stmt->execute([':uid' => $userId]);

    echo json_encode($stmt->fetchAll());
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}
