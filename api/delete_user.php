<?php
header('Content-Type: application/json');
require_once '../includes/auth_check.php';
require_once '../includes/db.php';

checkAuth('admin');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $data = json_decode(file_get_contents("php://input"), true);
    $user_id = $data['id'] ?? null;
    $role = $data['role'] ?? null;

    if (!$user_id || !$role) {
        echo json_encode(['success' => false, 'message' => 'User ID and Role are required']);
        exit;
    }

    $table = '';
    if ($role === 'student')
        $table = 'students';
    else if ($role === 'teacher')
        $table = 'teachers';
    else {
        echo json_encode(['success' => false, 'message' => 'Invalid role']);
        exit;
    }

    try {
        // Fetch name for logging
        $stmt = $conn->prepare("SELECT full_name FROM $table WHERE id = ?");
        $stmt->execute([$user_id]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$user) {
            echo json_encode(['success' => false, 'message' => 'User not found']);
            exit;
        }

        $stmt = $conn->prepare("DELETE FROM $table WHERE id = ?");
        $stmt->execute([$user_id]);

        logActivity(
            $conn,
            $_SESSION['user_id'],
            'admin',
            $_SESSION['user_name'],
            'delete',
            'User Removed',
            "Deleted $role: {$user['full_name']}",
            'ri-user-unfollow-line',
            'danger'
        );

        echo json_encode(['success' => true, 'message' => 'User deleted successfully']);
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
    }
}

