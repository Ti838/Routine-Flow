<?php
header('Content-Type: application/json');
require_once '../includes/db.php';
require_once '../includes/auth_check.php';

// Ensure student is logged in
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'student') {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

$student_id = $_SESSION['user_id'];
$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'GET') {
    try {
        $stmt = $conn->prepare("SELECT * FROM student_tasks WHERE student_id = ? ORDER BY status ASC, created_at DESC");
        $stmt->execute([$student_id]);
        echo json_encode(['success' => true, 'data' => $stmt->fetchAll(PDO::FETCH_ASSOC)]);
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }
} elseif ($method === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    $action = $data['action'] ?? '';

    try {
        if ($action === 'add') {
            $stmt = $conn->prepare("INSERT INTO student_tasks (student_id, title, description, estimated_time) VALUES (?, ?, ?, ?)");
            $stmt->execute([$student_id, $data['title'], $data['description'] ?? '', $data['estimated_time'] ?? 25]);
            echo json_encode(['success' => true, 'message' => 'Task added']);
        } elseif ($action === 'delete') {
            $stmt = $conn->prepare("DELETE FROM student_tasks WHERE id = ? AND student_id = ?");
            $stmt->execute([$data['id'], $student_id]);
            echo json_encode(['success' => true, 'message' => 'Task deleted']);
        } elseif ($action === 'complete') {
            $stmt = $conn->prepare("UPDATE student_tasks SET status = 'completed' WHERE id = ? AND student_id = ?");
            $stmt->execute([$data['id'], $student_id]);
            echo json_encode(['success' => true, 'message' => 'Task completed']);
        } elseif ($action === 'update_time') {
            $stmt = $conn->prepare("UPDATE student_tasks SET spent_time = spent_time + ? WHERE id = ? AND student_id = ?");
            $stmt->execute([$data['seconds'], $data['id'], $student_id]);
            echo json_encode(['success' => true, 'message' => 'Time updated']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Invalid action']);
        }
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }
}
