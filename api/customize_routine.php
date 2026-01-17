<?php
/**
 * Customize Routine API
 * Save student routine customizations (color, star, priority, notes)
 */

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, PUT');

require_once '../includes/db.php';

// Start session
session_start();

// Check authentication
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'student') {
    echo json_encode(['success' => false, 'message' => 'Unauthorized. Students only.']);
    exit;
}

$studentId = $_SESSION['user_id'];

// Get JSON data
$data = json_decode(file_get_contents('php://input'), true);

$routineId = isset($data['routine_id']) ? intval($data['routine_id']) : 0;
$colorCode = isset($data['color_code']) ? trim($data['color_code']) : null;
$isStarred = isset($data['is_starred']) ? (bool) $data['is_starred'] : false;
$priority = isset($data['priority']) ? intval($data['priority']) : 0;
$notes = isset($data['notes']) ? trim($data['notes']) : null;

if (!$routineId) {
    echo json_encode(['success' => false, 'message' => 'Missing routine ID']);
    exit;
}

// Validate color code format (allow hex or color names)
if ($colorCode && !preg_match('/^([a-zA-Z]+|#[0-9A-Fa-f]{6})$/', $colorCode)) {
    echo json_encode(['success' => false, 'message' => 'Invalid color format']);
    exit;
}

// Validate priority range
if ($priority < 0 || $priority > 3) {
    echo json_encode(['success' => false, 'message' => 'Priority must be between 0 and 3']);
    exit;
}

try {
    // Upsert customization (insert or update if exists)
    $stmt = $conn->prepare("
        INSERT INTO student_routine_customizations 
        (student_id, routine_id, color_code, is_starred, priority, notes) 
        VALUES (:student_id, :routine_id, :color_code, :is_starred, :priority, :notes)
        ON DUPLICATE KEY UPDATE 
        color_code = VALUES(color_code),
        is_starred = VALUES(is_starred),
        priority = VALUES(priority),
        notes = VALUES(notes),
        updated_at = CURRENT_TIMESTAMP
    ");

    $stmt->execute([
        ':student_id' => $studentId,
        ':routine_id' => $routineId,
        ':color_code' => $colorCode,
        ':is_starred' => $isStarred ? 1 : 0,
        ':priority' => $priority,
        ':notes' => $notes
    ]);

    echo json_encode([
        'success' => true,
        'message' => 'Customization saved successfully',
        'data' => [
            'routine_id' => $routineId,
            'color_code' => $colorCode,
            'is_starred' => $isStarred,
            'priority' => $priority,
            'notes' => $notes
        ]
    ]);

} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}
?>