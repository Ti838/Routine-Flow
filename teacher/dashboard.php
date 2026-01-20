<?php
require_once '../includes/auth_check.php';
require_once '../includes/layout.php';
require_once '../includes/db.php';

checkAuth('teacher');

$name = $_SESSION['user_name'];
$role = $_SESSION['user_role'];
$dept_id = $_SESSION['department_id'];

// Logic: Fetch dynamic data
try {
    // Fetch department name
    $stmt = $conn->prepare("SELECT name FROM departments WHERE id = ?");
    $stmt->execute([$dept_id]);
    $dept_name = $stmt->fetchColumn() ?: 'General Engineering';

    // Fetch today's classes for this teacher (Personal Focus)
    $today = date('l');
    $stmt = $conn->prepare("
        SELECT * FROM routines 
        WHERE (teacher_id = ? OR teacher_name = ?) 
        AND day_of_week = ? 
        AND status = 'active' 
        ORDER BY start_time ASC
    ");
    $stmt->execute([$_SESSION['user_id'], $name, $today]);
    $today_classes = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $classes_today_count = count($today_classes);

    // Fetch recent notices
    $stmt = $conn->prepare("SELECT * FROM notices ORDER BY created_at DESC LIMIT 3");
    $stmt->execute();
    $notices = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Fetch uploaded routine files for this department or Central
    $stmt = $conn->prepare("SELECT * FROM routine_files WHERE (department = ? OR department = 'Central' OR department = 'central' OR department IS NULL) ORDER BY created_at DESC LIMIT 5");
    $stmt->execute([$dept_name]);
    $routine_files = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Calculate next session for teacher
    $next_session_time = "Done Today";
    $next_session_subject = "No more classes";
    if (!empty($today_classes)) {
        $now_time = date('H:i:s');
        foreach ($today_classes as $class) {
            if ($class['start_time'] > $now_time) {
                $next_session_time = date('h:i A', strtotime($class['start_time']));
                $next_session_subject = $class['subject_name'];
                break;
            }
        }
    }

} catch (PDOException $e) {
    $dept_name = 'Error';
    $today_classes = [];
    $classes_today_count = 0;
    $notices = [];
}

// Presentation: Include the view
include __DIR__ . '/dashboard.html';
?>