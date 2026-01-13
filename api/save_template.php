<?php
/**
 * Save Template API
 * Save custom routine templates for students
 */

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');

require_once '../includes/db.php';

// Start session
session_start();

// Check authentication
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'student') {
    echo json_encode(['success' => false, 'message' => 'Unauthorized. Students only.']);
    exit;
}

$studentId = $_SESSION['user_id'];

// Get JSON data
$data = json_decode(file_get_contents('php://input'), true);

$templateName = isset($data['template_name']) ? trim($data['template_name']) : '';
$templateType = isset($data['template_type']) ? trim($data['template_type']) : 'custom';
$templateData = isset($data['template_data']) ? $data['template_data'] : [];

if (empty($templateName)) {
    echo json_encode(['success' => false, 'message' => 'Template name is required']);
    exit;
}

// Validate template type
$validTypes = ['daily', 'weekly', 'monthly', 'custom'];
if (!in_array($templateType, $validTypes)) {
    echo json_encode(['success' => false, 'message' => 'Invalid template type']);
    exit;
}

try {
    // Save template
    $stmt = $conn->prepare("
        INSERT INTO routine_templates 
        (student_id, template_name, template_type, template_data) 
        VALUES (:student_id, :template_name, :template_type, :template_data)
    ");

    $stmt->execute([
        ':student_id' => $studentId,
        ':template_name' => $templateName,
        ':template_type' => $templateType,
        ':template_data' => json_encode($templateData)
    ]);

    $templateId = $conn->lastInsertId();

    echo json_encode([
        'success' => true,
        'message' => 'Template saved successfully',
        'data' => [
            'template_id' => $templateId,
            'template_name' => $templateName,
            'template_type' => $templateType
        ]
    ]);

} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}
?>