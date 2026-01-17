<?php
/**
 * Get Customizations API
 * Retrieve student's routine customizations
 */

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET');

require_once '../includes/db.php';

// Start session
session_start();

// Check authentication
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'student') {
    echo json_encode(['success' => false, 'message' => 'Unauthorized. Students only.']);
    exit;
}

$studentId = $_SESSION['user_id'];

try {
    // Get all customizations for this student
    $stmt = $conn->prepare("
        SELECT 
            c.*,
            r.subject_name,
            r.day_of_week,
            r.start_time,
            r.end_time
        FROM student_routine_customizations c
        JOIN routines r ON c.routine_id = r.id
        WHERE c.student_id = :student_id
        ORDER BY c.updated_at DESC
    ");

    $stmt->execute([':student_id' => $studentId]);
    $customizations = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        'success' => true,
        'data' => $customizations,
        'count' => count($customizations)
    ]);

} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}
?>