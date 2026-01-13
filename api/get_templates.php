<?php
/**
 * Get Templates API
 * Retrieve student's saved templates
 */

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET');

require_once '../includes/db.php';

// Start session
session_start();

// Check authentication
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'student') {
    echo json_encode(['success' => false, 'message' => 'Unauthorized. Students only.']);
    exit;
}

$studentId = $_SESSION['user_id'];
$templateType = isset($_GET['type']) ? trim($_GET['type']) : null;

try {
    // Build query
    $sql = "SELECT * FROM routine_templates WHERE student_id = :student_id";
    $params = [':student_id' => $studentId];

    if ($templateType) {
        $sql .= " AND template_type = :template_type";
        $params[':template_type'] = $templateType;
    }

    $sql .= " ORDER BY updated_at DESC";

    $stmt = $conn->prepare($sql);
    $stmt->execute($params);
    $templates = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Decode JSON data for each template
    foreach ($templates as &$template) {
        $template['template_data'] = json_decode($template['template_data'], true);
    }

    echo json_encode([
        'success' => true,
        'data' => $templates,
        'count' => count($templates)
    ]);

} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}
?>