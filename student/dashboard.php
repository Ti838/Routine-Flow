<?php
require_once '../includes/auth_check.php';
require_once '../includes/layout.php';
require_once '../includes/db.php';

checkAuth('student');

$name = $_SESSION['user_name'];
$role = $_SESSION['user_role'];
$student_id = $_SESSION['student_id'] ?? 'N/A';
$semester = $_SESSION['semester'] ?? '1st Year, 1st Sem';
$dept_id = $_SESSION['department_id'];

// Logic: Fetch dynamic data
try {
    // Fetch department name
    $stmt = $conn->prepare("SELECT name FROM departments WHERE id = ?");
    $stmt->execute([$dept_id]);
    $dept_name = $stmt->fetchColumn() ?: 'General Engineering';

    // Fetch today's classes
    $today = date('l');
    $stmt = $conn->prepare("SELECT * FROM routines WHERE department_id = ? AND semester = ? AND day_of_week = ? AND status = 'active' ORDER BY start_time ASC");
    $stmt->execute([$dept_id, $semester, $today]);
    $today_classes = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Calculate next session
    $next_session_time = "09:00 AM";
    $next_session_subject = "No session soon";
    if (!empty($today_classes)) {
        $now_time = date('H:i:s');
        foreach ($today_classes as $class) {
            if ($class['start_time'] > $now_time) {
                $next_session_time = date('H:i A', strtotime($class['start_time']));
                $next_session_subject = $class['subject_name'];
                break;
            }
        }
    }

    // Fetch recent notices
    $stmt = $conn->prepare("SELECT * FROM notices ORDER BY created_at DESC LIMIT 3");
    $stmt->execute();
    $notices = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    $dept_name = 'Error';
    $today_classes = [];
    $next_session_time = "--:--";
    $next_session_subject = "Error";
    $notices = [];
}

// Presentation: Include the view
include 'views/dashboard.html';
?>