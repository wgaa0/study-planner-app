<?php
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized']);
    exit();
}

require_once __DIR__ . '/../../src/lib/DB.php';

$pdo = DB::get();
$userId = $_SESSION['user_id'];
$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'GET':
        // Fetch all events for this user
        $stmt = $pdo->prepare("SELECT * FROM events WHERE user_id = :uid ORDER BY start ASC");
        $stmt->execute([':uid' => $userId]);
        echo json_encode($stmt->fetchAll());
        break;

    case 'POST':
        $input = json_decode(file_get_contents('php://input'), true);
        if (!$input || !isset($input['title'], $input['start'])) {
            http_response_code(400);
            echo json_encode(['error' => 'Missing required fields']);
            exit();
        }

        $stmt = $pdo->prepare("INSERT INTO events (user_id, course_id, task_id, title, start, end, notes) 
                               VALUES (:uid, :cid, :tid, :title, :start, :end, :notes)");
        $stmt->execute([
            ':uid' => $userId,
            ':cid' => $input['course_id'] ?? null,
            ':tid' => $input['task_id'] ?? null,
            ':title' => $input['title'],
            ':start' => $input['start'],
            ':end' => $input['end'] ?? null,
            ':notes' => $input['notes'] ?? null,
        ]);
        echo json_encode(['success' => true, 'id' => $pdo->lastInsertId()]);
        break;

    case 'DELETE':
        parse_str($_SERVER['QUERY_STRING'], $query);
        if (!isset($query['id'])) {
            http_response_code(400);
            echo json_encode(['error' => 'Missing id']);
            exit();
        }

        $stmt = $pdo->prepare("DELETE FROM events WHERE id = :id AND user_id = :uid");
        $stmt->execute([
            ':id' => $query['id'],
            ':uid' => $userId
        ]);
        echo json_encode(['success' => true]);
        break;

    default:
        http_response_code(405);
        echo json_encode(['error' => 'Method not allowed']);
}
