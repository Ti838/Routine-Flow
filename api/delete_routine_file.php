<?php
/**
 * Delete Routine File API
 * Allows admin/teacher to delete their uploaded files
 */

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: DELETE, POST');

require_once '../includes/db.php';
require_once '../includes/file_handler.php';

// Start session
session_start();

// Check authentication
if (!isset($_SESSION['user_id']) || !isset($_SESSION['role'])) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

$userId = $_SESSION['user_id'];
$userRole = $_SESSION['role'];

// Get attachment ID
$data = json_decode(file_get_contents('php://input'), true);
$attachmentId = isset($data['attachment_id']) ? intval($data['attachment_id']) : 0;

if (!$attachmentId) {
    echo json_encode(['success' => false, 'message' => 'Missing attachment ID']);
    exit;
}

try {
    // Get attachment info
    $stmt = $conn->prepare("SELECT * FROM routine_attachments WHERE id = :id");
    $stmt->execute([':id' => $attachmentId]);
    $attachment = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$attachment) {
        echo json_encode(['success' => false, 'message' => 'Attachment not found']);
        exit;
    }

    // Check permissions (admin can delete any, teacher can only delete their own)
    if ($userRole === 'teacher' && ($attachment['uploaded_by_role'] !== 'teacher' || $attachment['uploaded_by_id'] != $userId)) {
        echo json_encode(['success' => false, 'message' => 'You can only delete your own files']);
        exit;
    }

    // Delete file from filesystem
    FileHandler::deleteFile($attachment['file_path']);

    // Delete from database
    $deleteStmt = $conn->prepare("DELETE FROM routine_attachments WHERE id = :id");
    $deleteStmt->execute([':id' => $attachmentId]);

    echo json_encode([
        'success' => true,
        'message' => 'File deleted successfully'
    ]);

} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}
?>