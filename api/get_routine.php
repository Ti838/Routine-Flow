<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

require_once '../includes/db.php';

$response = ['success' => false, 'payload' => [], 'message' => ''];

try {
    // For now, we fetch all routines or filter by dept/semester if provided
    $department = isset($_GET['department']) ? $_GET['department'] : null;
    $semester = isset($_GET['semester']) ? $_GET['semester'] : null;

    $sql = "
        SELECT 
            r.id, 
            r.subject_name as subject, 
            r.day_of_week as day, 
            r.start_time, 
            r.end_time, 
            r.room_number as room,
            t.name as teacher,
            'Lecture' as type,
            r.department,
            r.semester
        FROM routines r
        LEFT JOIN teachers t ON r.teacher_id = t.id
    ";

    $params = [];
    if ($department && $semester) {
        $sql .= " WHERE r.department = :dept AND r.semester = :sem";
        $params[':dept'] = $department;
        $params[':sem'] = $semester;
    }

    $sql .= " ORDER BY FIELD(day_of_week, 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'), start_time";

    $stmt = $conn->prepare($sql);
    $stmt->execute($params);
    $routines = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Add status logic or extra formatting if needed
    foreach ($routines as &$row) {
        $row['status'] = 'upcoming';
    }

    $response['success'] = true;
    $response['payload'] = $routines;

} catch (PDOException $e) {
    $response['message'] = "Database error: " . $e->getMessage();
}

echo json_encode($response);
?>
