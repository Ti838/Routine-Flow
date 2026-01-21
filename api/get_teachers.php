<?php
/**
 * GET TEACHERS API ENDPOINT
 * Retrieves list of all teachers from database
 * Used for dropdown selections and teacher assignments
 * Returns JSON-formatted teacher data
 */

// Set response headers for JSON API
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

require_once '../includes/db.php';

try {
    // Query all teachers ordered by full name alphabetically
    $stmt = $conn->prepare("SELECT id, full_name, department FROM teachers ORDER BY full_name ASC");
    $stmt->execute();
    $teachers = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Return successful response with teacher list
    echo json_encode([
        'success' => true,
        'payload' => $teachers
    ]);
} catch (PDOException $e) {
    // Handle database errors
    echo json_encode([
        'success' => false,
        'message' => 'Database error: ' . $e->getMessage()
    ]);
}
?>
