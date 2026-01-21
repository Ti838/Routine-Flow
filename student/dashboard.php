<?php
/**
 * STUDENT DASHBOARD PAGE
 * Displays today's schedule, next session, notices, and routine files
 * Merges official classes with personal tasks and sorts by time
 */

// Import authentication, layout, and database
require_once '../includes/auth_check.php';
require_once '../includes/layout.php';
require_once '../includes/db.php';

// Verify user is logged in as student
checkAuth('student');

// Get current student's information from session
$name = $_SESSION['user_name'];
$role = $_SESSION['user_role'];
$student_id = !empty($_SESSION['student_id']) ? $_SESSION['student_id'] : 'Not Set';    // Student ID
$semester = !empty($_SESSION['semester']) ? $_SESSION['semester'] : 'Semester Not Set';   // Semester level
$dept_id = $_SESSION['department_id'];                                                    // Department ID

try {
    // Get department name from ID
    $stmt = $conn->prepare("SELECT name FROM departments WHERE id = ?");
    $stmt->execute([$dept_id]);
    $dept_name = $stmt->fetchColumn() ?: 'General Engineering';  // Default if not found

    // Get current day of week (e.g., 'Monday', 'Tuesday')
    $today = date('l');
    
    /**
     * FETCH TODAY'S OFFICIAL CLASSES
     * Joins with customizations to include student's color codes and stars
     */
    $stmt = $conn->prepare("
        SELECT r.*, c.color_code, c.is_starred, 'class' as type 
        FROM routines r
        LEFT JOIN student_routine_customizations c ON r.id = c.routine_id AND c.student_id = ?
        WHERE r.department_id = ? AND r.semester = ? AND r.day_of_week = ? AND r.status = 'active'
    ");
    $stmt->execute([$student_id, $dept_id, $semester, $today]);
    $official_classes = $stmt->fetchAll(PDO::FETCH_ASSOC);

    /**
     * FETCH TODAY'S PERSONAL TASKS/EVENTS
     * Separately fetched and formatted to match routine structure
     */
    $stmt = $conn->prepare("
        SELECT id, title as subject_name, day_of_week, start_time, end_time, 
               '' as room_number, 'Personal Task' as teacher_name, 
               color as color_code, 'personal' as type, 0 as is_starred
        FROM personal_events 
        WHERE student_id = ? AND day_of_week = ?
    ");
    $stmt->execute([$student_id, $today]);
    $personal_tasks = $stmt->fetchAll(PDO::FETCH_ASSOC);

    /**
     * MERGE AND SORT TODAY'S ACTIVITIES
     * Combines official classes and personal tasks, sorted by start time
     */
    $today_classes = array_merge($official_classes, $personal_tasks);
    usort($today_classes, function ($a, $b) {
        return strcmp($a['start_time'], $b['start_time']);  // Sort by time string
    });

    /**
     * FIND NEXT SESSION
     * Searches for first upcoming class after current time
     */
    $next_session_time = "Done Today";
    $next_session_subject = "Relax for now";
    if (!empty($today_classes)) {
        $now_time = date('H:i:s');  // Current time in 24-hour format
        $found = false;
        foreach ($today_classes as $class) {
            if ($class['start_time'] > $now_time) {
                // Found next upcoming class
                $next_session_time = date('h:i A', strtotime($class['start_time']));  // Convert to 12-hour format
                $next_session_subject = $class['subject_name'];
                $found = true;
                break;
            }
        }
    }

    // Fetch recent notices (latest 3)
    $stmt = $conn->prepare("SELECT * FROM notices ORDER BY created_at DESC LIMIT 3");
    $stmt->execute();
    $notices = $stmt->fetchAll(PDO::FETCH_ASSOC);

    /**
     * FETCH ROUTINE FILES
     * Gets shared routine documents for student's department and semester
     * Allows 'Central' or 'Any' department to be shared across all departments
     */
    $stmt = $conn->prepare("SELECT * FROM routine_files WHERE (department = ? OR department = 'Central' OR department = 'central' OR department IS NULL) AND (semester = ? OR semester IS NULL OR semester = 'Any' OR semester = 'All Semesters' OR semester = 'all') ORDER BY created_at DESC LIMIT 5");
    $stmt->execute([$dept_name, $semester]);
    $routine_files = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    // Handle any database errors with default values
    $dept_name = 'Error';
    $today_classes = [];
    $next_session_time = "--:--";
    $next_session_subject = "Error";
    $notices = [];
    $routine_files = [];
}

// Load the dashboard HTML template
include __DIR__ . '/dashboard.html';
?>
