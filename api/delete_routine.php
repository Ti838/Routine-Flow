<?php
header('Content-Type: application/json');
require_once '../includes/auth_check.php';
require_once '../includes/db.php';

// Check if user is admin
checkAuth('admin');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    $routine_id = $data['id'] ?? null;

    if (!$routine_id) {
        echo json_encode(['success' => false, 'message' => 'Routine ID is required']);
        exit;
    }

    try {
        // Fetch routine info for logging before deletion
        $stmt = $conn->prepare("SELECT subject_name, department, semester FROM routines WHERE id = ?");
        $stmt->execute([$routine_id]);
        $routine = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$routine) {
            echo json_encode(['success' => false, 'message' => 'Routine not found']);
            exit;
        }

        // Delete the routine
        $stmt = $conn->prepare("DELETE FROM routines WHERE id = ?");
        $stmt->execute([$routine_id]);

        // Log the activity
        session_start();
        $name = $_SESSION['user_name'];
        $role = $_SESSION['user_role'];
        $user_id = $_SESSION['user_id'];

        $log_stmt = $conn->prepare("INSERT INTO activity_log (user_id, user_role, user_name, action_type, action_title, action_description, icon_class, badge_type) VALUES (?, ?, ?, 'delete', 'Routine Removed', ?, 'ri-delete-bin-line', 'danger')");
        $log_stmt->execute([
            $user_id,
            $role,
            $name,
            "Deleted routine for '{$routine['subject_name']}' ({$routine['department']} - {$routine['semester']})"
        ]);

        echo json_encode(['success' => true, 'message' => 'Routine deleted successfully']);
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
}
