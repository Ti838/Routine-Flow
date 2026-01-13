<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
session_start();

// NOTE: In a real production app, we would strictly check $_SESSION['user_id'] and role.
// For this local demo, if session is missing, we might return a mock ID or empty.
// However, the user wants "system logic", so let's try to use session if available.

$host = 'localhost';
$user = 'root';
$password = '';
$dbname = 'routine_flow_db';

$conn = new mysqli($host, $user, $password, $dbname);

if ($conn->connect_error) {
    echo json_encode(['success' => false, 'message' => 'Connection failed']);
    exit;
}

$teacher_id = 0;

if (isset($_SESSION['user_id']) && $_SESSION['user_role'] === 'Teacher') {
    $teacher_id = $_SESSION['user_id'];
} else {
    // Fallback for "Demo" mode if testing without login, OR return error.
    // Let's check if a parameter is passed for testing? ?id=1
    if (isset($_GET['id'])) {
        $teacher_id = intval($_GET['id']);
    }
}

if ($teacher_id === 0) {
    // Return empty but success to not break frontend
    echo json_encode(['success' => false, 'message' => 'Not authenticated as Teacher', 'payload' => []]);
    exit;
}

$sql = "
    SELECT 
        id, 
        subject_name as subject, 
        day_of_week as day, 
        start_time, 
        end_time, 
        room_number as room, 
        department, 
        semester 
    FROM routines 
    WHERE teacher_id = ?
    ORDER BY FIELD(day_of_week, 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'), start_time
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $teacher_id);
$stmt->execute();
$result = $stmt->get_result();

$routines = [];
while ($row = $result->fetch_assoc()) {
    $routines[] = $row;
}

echo json_encode(['success' => true, 'payload' => $routines]);

$stmt->close();
$conn->close();
?>