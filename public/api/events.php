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
$method = $_SERVER['REQUEST_METHOD'];

header('Content-Type: application/json');

switch ($method) {
    case 'GET':
        $stmt = $pdo->prepare("SELECT id, title, start, end, notes FROM events WHERE user_id = :uid");
        $stmt->execute([':uid' => $userId]);
        echo json_encode($stmt->fetchAll());
        break;

    case 'POST':
        $data = json_decode(file_get_contents('php://input'), true);
        if (!$data || empty($data['title']) || empty($data['start'])) {
            http_response_code(400);
            echo json_encode(['error' => 'Missing required fields']);
            exit();
        }

        $stmt = $pdo->prepare("INSERT INTO events (user_id, title, start, end, notes) 
                               VALUES (:uid, :title, :start, :end, :notes)");
        $stmt->execute([
            ':uid' => $userId,
            ':title' => $data['title'],
            ':start' => $data['start'],
            ':end'   => $data['end'] ?? null,
            ':notes' => $data['notes'] ?? null
        ]);
        echo json_encode(['success' => true]);
        break;

    case 'PUT':
        $data = json_decode(file_get_contents('php://input'), true);
        if (!$data || empty($data['id'])) {
            http_response_code(400);
            echo json_encode(['error' => 'Missing event ID']);
            exit();
        }

        $stmt = $pdo->prepare("UPDATE events 
                               SET title = :title, start = :start, end = :end, notes = :notes 
                               WHERE id = :id AND user_id = :uid");
        $stmt->execute([
            ':title' => $data['title'] ?? '',
            ':start' => $data['start'] ?? null,
            ':end'   => $data['end'] ?? null,
            ':notes' => $data['notes'] ?? null,
            ':id'    => $data['id'],
            ':uid'   => $userId
        ]);
        echo json_encode(['success' => true]);
        break;

    case 'DELETE':
        if (!isset($_GET['id'])) {
            http_response_code(400);
            echo json_encode(['error' => 'Missing event ID']);
            exit();
        }

        $stmt = $pdo->prepare("DELETE FROM events WHERE id = :id AND user_id = :uid");
        $stmt->execute([':id' => $_GET['id'], ':uid' => $userId]);
        echo json_encode(['success' => true]);
        break;

    default:
        http_response_code(405);
        echo json_encode(['error' => 'Method not allowed']);
}
