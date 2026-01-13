<?php
/**
 * Upload Routine File API
 * Handles file uploads for admin and teachers
 */

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');

require_once '../includes/db.php';
require_once '../includes/file_handler.php';

// Start session to get user info
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id']) || !isset($_SESSION['role'])) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized. Please login.']);
    exit;
}

$userId = $_SESSION['user_id'];
$userRole = $_SESSION['role'];

// Only admin and teacher can upload
if ($userRole !== 'admin' && $userRole !== 'teacher') {
    echo json_encode(['success' => false, 'message' => 'Only admins and teachers can upload files']);
    exit;
}

// Check if file was uploaded
if (!isset($_FILES['file'])) {
    echo json_encode(['success' => false, 'message' => 'No file uploaded']);
    exit;
}

// Get additional form data
$routineId = isset($_POST['routine_id']) ? intval($_POST['routine_id']) : null;
$department = isset($_POST['department']) ? trim($_POST['department']) : '';
$semester = isset($_POST['semester']) ? trim($_POST['semester']) : '';
$description = isset($_POST['description']) ? trim($_POST['description']) : '';
$isFileOnly = isset($_POST['is_file_only']) ? (bool) $_POST['is_file_only'] : true;

try {
    // Upload file
    $uploadResult = FileHandler::uploadFile($_FILES['file'], $userRole);

    if (!$uploadResult['success']) {
        echo json_encode(['success' => false, 'message' => $uploadResult['error']]);
        exit;
    }

    $filePath = $uploadResult['path'];
    $fileName = $_FILES['file']['name'];
    $fileSize = $_FILES['file']['size'];
    $fileType = FileHandler::getFileType($fileName);

    // If this is a file-only upload, create a routine entry
    if ($isFileOnly && !$routineId) {
        $stmt = $conn->prepare("INSERT INTO routines (department, semester, is_file_only, file_description) VALUES (:dept, :sem, 1, :desc)");
        $stmt->execute([
            ':dept' => $department,
            ':sem' => $semester,
            ':desc' => $description
        ]);
        $routineId = $conn->lastInsertId();
    }

    // Save file metadata to database
    $stmt = $conn->prepare("INSERT INTO routine_attachments (routine_id, file_name, file_path, file_type, file_size, uploaded_by_role, uploaded_by_id) VALUES (:routine_id, :file_name, :file_path, :file_type, :file_size, :role, :user_id)");

    $stmt->execute([
        ':routine_id' => $routineId,
        ':file_name' => $fileName,
        ':file_path' => $filePath,
        ':file_type' => $fileType,
        ':file_size' => $fileSize,
        ':role' => $userRole,
        ':user_id' => $userId
    ]);

    $attachmentId = $conn->lastInsertId();

    echo json_encode([
        'success' => true,
        'message' => 'File uploaded successfully',
        'data' => [
            'attachment_id' => $attachmentId,
            'routine_id' => $routineId,
            'file_name' => $fileName,
            'file_path' => $filePath,
            'file_type' => $fileType,
            'file_size' => FileHandler::formatFileSize($fileSize)
        ]
    ]);

} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}
?>