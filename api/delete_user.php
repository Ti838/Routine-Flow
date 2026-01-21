<?php
/**
 * DELETE USER API ENDPOINT
 * Admin-only endpoint to remove users (students and teachers)
 * Logs the deletion action and validates user existence
 */

// Set response headers for JSON API
header('Content-Type: application/json');

// Import authentication and database
require_once '../includes/auth_check.php';
require_once '../includes/db.php';

// Verify requesting user is admin
checkAuth('admin');

// Only allow POST requests
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get JSON data from request body
    $data = json_decode(file_get_contents("php://input"), true);
    $user_id = $data['id'] ?? null;      // User ID to delete
    $role = $data['role'] ?? null;       // User role (student or teacher)

    // Validate required parameters
    if (!$user_id || !$role) {
        echo json_encode(['success' => false, 'message' => 'User ID and Role are required']);
        exit;
    }

    // Map role to database table
    $table = '';
    if ($role === 'student')
        $table = 'students';
    else if ($role === 'teacher')
        $table = 'teachers';
    else {
        // Invalid role
        echo json_encode(['success' => false, 'message' => 'Invalid role']);
        exit;
    }

    try {
        // Fetch user's full name before deletion for logging purposes
        $stmt = $conn->prepare("SELECT full_name FROM $table WHERE id = ?");
        $stmt->execute([$user_id]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // Check if user exists
        if (!$user) {
            echo json_encode(['success' => false, 'message' => 'User not found']);
            exit;
        }

        // Delete the user from appropriate table
        $stmt = $conn->prepare("DELETE FROM $table WHERE id = ?");
        $stmt->execute([$user_id]);

        // Log the deletion action for audit trail
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

        // Send success response
        echo json_encode(['success' => true, 'message' => 'User deleted successfully']);
    } catch (Exception $e) {
        // Handle any errors
        echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
    }
}
?>