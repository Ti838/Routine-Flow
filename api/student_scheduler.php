<?php
/**
 * Student Scheduler API
 * Handles fetching merged events and managing personal tasks
 */

header('Content-Type: application/json');
session_start();
require_once '../includes/db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'student') {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

$student_id = $_SESSION['user_id'];
$dept_id = $_SESSION['department_id'];
$semester = $_SESSION['semester'] ?? '1st Year, 1st Sem';
$action = $_GET['action'] ?? '';

try {
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        // FETCH MERGED SCHEDULE (Classes + Tasks)

        // 1. Fetch Official Classes
        $stmt = $conn->prepare("SELECT id, subject_name as title, day_of_week, start_time, end_time, room_number, teacher_name, 'class' as type, color_tag as color 
                               FROM routines 
                               WHERE department_id = ? AND semester = ? AND status = 'active'");
        $stmt->execute([$dept_id, $semester]);
        $classes = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // 2. Fetch Personal Tasks
        $stmt = $conn->prepare("SELECT id, title, day_of_week, start_time, end_time, '' as room_number, '' as teacher_name, 'personal' as type, color 
                               FROM personal_events 
                               WHERE student_id = ?");
        $stmt->execute([$student_id]);
        $tasks = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $merged = array_merge($classes, $tasks);
        echo json_encode(['success' => true, 'data' => $merged]);
    } elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // ADD NEW TASK
        $data = json_decode(file_get_contents('php://input'), true);

        $title = $data['title'];
        $day = $data['day'];
        $start = $data['start'];
        $end = $data['end'];
        $color = $data['color'] ?? 'orange';

        $stmt = $conn->prepare("INSERT INTO personal_events (student_id, title, day_of_week, start_time, end_time, color) VALUES (?, ?, ?, ?, ?, ?)");
        if ($stmt->execute([$student_id, $title, $day, $start, $end, $color])) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to save']);
        }
    } elseif ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
        // DELETE TASK
        $id = $_GET['id'];
        $stmt = $conn->prepare("DELETE FROM personal_events WHERE id = ? AND student_id = ?");
        if ($stmt->execute([$id, $student_id])) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to delete']);
        }
    }

} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
?>