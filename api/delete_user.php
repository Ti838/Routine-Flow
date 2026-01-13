<?php
header('Content-Type: application/json');
require_once '../includes/auth_check.php';
require_once '../includes/db.php';

checkAuth('admin');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $data = json_decode(file_get_contents("php://input"), true);
    $user_id = $data['id'];

    if (!$user_id) {
        echo json_encode(['success' => false, 'message' => 'User ID is required']);
        exit;
    }

    try {
        // Fetch name for logging
        $stmt = $conn->prepare("SELECT full_name, role FROM users WHERE id = ?");
        $stmt->execute([$user_id]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$user) {
            echo json_encode(['success' => false, 'message' => 'User not found']);
            exit;
        }

        // Delete (Cascading might need to be handled if not in DB schema, but following project patterns)
        // Usually, foreign keys handle this, but we'll be safe.
        $conn->beginTransaction();

        // Remove from role tables first
        $conn->prepare("DELETE FROM students WHERE user_id = ?")->execute([$user_id]);
        $conn->prepare("DELETE FROM teachers WHERE user_id = ?")->execute([$user_id]);
        $conn->prepare("DELETE FROM users WHERE id = ?")->execute([$user_id]);

        logActivity($conn, $_SESSION['user_id'], 'admin', $_SESSION['user_name'], 'delete', 'User Removed', "Deleted {$user['role']}: {$user['full_name']}", 'ri-user-unfollow-line', 'danger');

        $conn->commit();
        echo json_encode(['success' => true, 'message' => 'User deleted successfully']);
    } catch (Exception $e) {
        $conn->rollBack();
        echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
    }
}
