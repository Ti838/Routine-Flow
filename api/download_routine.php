<?php
/**
 * Download Routine File API
 * Serves files for download with access control
 */

require_once '../includes/db.php';

// Start session to get user info
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id']) || !isset($_SESSION['role'])) {
    http_response_code(401);
    die('Unauthorized. Please login.');
}

$userId = $_SESSION['user_id'];
$userRole = $_SESSION['user_role'];

// Get attachment ID
if (!isset($_GET['id'])) {
    http_response_code(400);
    die('Missing attachment ID');
}

$attachmentId = intval($_GET['id']);
$table = isset($_GET['type']) && $_GET['type'] === 'file' ? 'routine_files' : 'routine_attachments';

try {
    // Get file information from appropriate table
    if ($table === 'routine_files') {
        $stmt = $conn->prepare("SELECT id, file_path, file_type as file_name, created_at FROM routine_files WHERE id = :id");
        $stmt->execute([':id' => $attachmentId]);
        $attachment = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($attachment) {
            // Extract filename from path for routine_files
            $attachment['file_name'] = basename($attachment['file_path']);
        }
    } else {
        $stmt = $conn->prepare("SELECT * FROM routine_attachments WHERE id = :id");
        $stmt->execute([':id' => $attachmentId]);
        $attachment = $stmt->fetch(PDO::FETCH_ASSOC);
    }

    if (!$attachment) {
        http_response_code(404);
        die('File not found');
    }

    $filePath = __DIR__ . '/../' . $attachment['file_path'];

    // Check if file exists
    if (!file_exists($filePath)) {
        http_response_code(404);
        die('File not found on server');
    }

    // Log download for students
    if ($userRole === 'student') {
        $logStmt = $conn->prepare("INSERT INTO download_logs (attachment_id, student_id) VALUES (:attachment_id, :student_id)");
        $logStmt->execute([
            ':attachment_id' => $attachmentId,
            ':student_id' => $userId
        ]);
    }

    // Set headers for file download
    header('Content-Type: ' . mime_content_type($filePath));
    header('Content-Disposition: attachment; filename="' . $attachment['file_name'] . '"');
    header('Content-Length: ' . filesize($filePath));
    header('Cache-Control: no-cache, must-revalidate');
    header('Expires: 0');

    // Output file
    readfile($filePath);
    exit;

} catch (PDOException $e) {
    http_response_code(500);
    die('Database error: ' . $e->getMessage());
}
?>