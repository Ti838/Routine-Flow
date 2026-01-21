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
        $stmt = $conn->prepare("
            SELECT r.id, r.subject_name as title, r.day_of_week, r.start_time, r.end_time, r.room_number, r.teacher_name, 'class' as type, 
                   COALESCE(c.color_code, r.color_tag, 'indigo') as color, c.is_starred
            FROM routines r
            LEFT JOIN student_routine_customizations c ON r.id = c.routine_id AND c.student_id = ?
            WHERE r.department_id = ? AND r.semester = ? AND r.status = 'active'
        ");
        $stmt->execute([$student_id, $dept_id, $semester]);
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
        $data = json_decode(file_get_contents('php://input'), true);
        $actionData = $data['action'] ?? '';

        if ($actionData === 'add') {
            // ADD NEW TASK
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
        } elseif ($actionData === 'delete') {
            // DELETE TASK
            $id = $data['id'];
            $stmt = $conn->prepare("DELETE FROM personal_events WHERE id = ? AND student_id = ?");
            if ($stmt->execute([$id, $student_id])) {
                echo json_encode(['success' => true]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Failed to delete']);
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'Invalid action']);
        }
    }

} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
?>
