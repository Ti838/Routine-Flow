<?php
/**
 * GET ROUTINE API ENDPOINT
 * Retrieves routine entries filtered by department and semester
 * Returns JSON-formatted routine data for display on frontend
 */

// Set response headers for JSON API
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

require_once '../includes/db.php';

// Initialize response object
$response = ['success' => false, 'payload' => [], 'message' => ''];

try {
    // Get filter parameters from GET request
    $department = isset($_GET['department']) ? $_GET['department'] : null;
    $semester = isset($_GET['semester']) ? $_GET['semester'] : null;

    // Build base SQL query to fetch routine data
    $sql = "
        SELECT 
            r.id,                          -- Routine ID
            r.subject_name as subject,     -- Subject name (formatted for display)
            r.day_of_week as day,          -- Day of the week
            r.start_time,                  -- Class start time
            r.end_time,                    -- Class end time
            r.room_number as room,         -- Room number (formatted for display)
            t.name as teacher,             -- Teacher name (from join)
            'Lecture' as type,             -- Class type (currently fixed to Lecture)
            r.department,                  -- Department name
            r.semester                     -- Semester level
        FROM routines r
        LEFT JOIN teachers t ON r.teacher_id = t.id
    ";

    // Prepare filter conditions
    $params = [];
    if ($department && $semester) {
        // If both filters provided, filter by both
        $sql .= " WHERE r.department = :dept AND r.semester = :sem";
        $params[':dept'] = $department;
        $params[':sem'] = $semester;
    }

    // Order results by day of week and start time
    $sql .= " ORDER BY FIELD(day_of_week, 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'), start_time";

    // Execute prepared statement
    $stmt = $conn->prepare($sql);
    $stmt->execute($params);
    $routines = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Add status to each routine (for frontend logic)
    foreach ($routines as &$row) {
        $row['status'] = 'upcoming';  // All routines marked as upcoming
    }

    // Send successful response with routine data
    $response['success'] = true;
    $response['payload'] = $routines;

} catch (PDOException $e) {
    // Handle database errors
    $response['message'] = "Database error: " . $e->getMessage();
}

// Return JSON response
echo json_encode($response);
?>
