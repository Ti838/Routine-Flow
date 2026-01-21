<?php
/**
 * TEACHER DASHBOARD PAGE
 * Displays today's teaching schedule, next class, notices, and routine files
 * Shows classes for the logged-in teacher
 */

// Import authentication, layout, and database
require_once '../includes/auth_check.php';
require_once '../includes/layout.php';
require_once '../includes/db.php';

// Verify user is logged in as teacher
checkAuth('teacher');

// Get current teacher's information from session
$name = $_SESSION['user_name'];
$role = $_SESSION['user_role'];
$dept_id = $_SESSION['department_id'];  // Teacher's department

try {
    // Get department name from ID
    $stmt = $conn->prepare("SELECT name FROM departments WHERE id = ?");
    $stmt->execute([$dept_id]);
    $dept_name = $stmt->fetchColumn() ?: 'General Engineering';  // Default if not found

    // Get current day of week
    $today = date('l');
    
    /**
     * FETCH TODAY'S CLASSES FOR THIS TEACHER
     * Searches by both teacher_id and teacher_name for flexibility
     * Sorted by start time
     */
    $stmt = $conn->prepare("
        SELECT * FROM routines 
        WHERE (teacher_id = ? OR teacher_name = ?) 
        AND day_of_week = ? 
        AND status = 'active' 
        ORDER BY start_time ASC
    ");
    $stmt->execute([$_SESSION['user_id'], $name, $today]);
    $today_classes = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $classes_today_count = count($today_classes);  // Count for display

    // Fetch recent notices (latest 3)
    $stmt = $conn->prepare("SELECT * FROM notices ORDER BY created_at DESC LIMIT 3");
    $stmt->execute();
    $notices = $stmt->fetchAll(PDO::FETCH_ASSOC);

    /**
     * FETCH ROUTINE FILES
     * Gets shared routine documents for teacher's department
     * Allows 'Central' or 'Any' department to be shared
     */
    $stmt = $conn->prepare("SELECT * FROM routine_files WHERE (department = ? OR department = 'Central' OR department = 'central' OR department IS NULL) ORDER BY created_at DESC LIMIT 5");
    $stmt->execute([$dept_name]);
    $routine_files = $stmt->fetchAll(PDO::FETCH_ASSOC);

    /**
     * FIND NEXT CLASS
     * Searches for first upcoming class after current time
     */
    $next_session_time = "Done Today";
    $next_session_subject = "No more classes";
    if (!empty($today_classes)) {
        $now_time = date('H:i:s');  // Current time in 24-hour format
        foreach ($today_classes as $class) {
            if ($class['start_time'] > $now_time) {
                // Found next upcoming class
                $next_session_time = date('h:i A', strtotime($class['start_time']));  // Convert to 12-hour format
                $next_session_subject = $class['subject_name'];
                break;
            }
        }
    }

} catch (PDOException $e) {
    // Handle any database errors with default values
    $dept_name = 'Error';
    $today_classes = [];
    $classes_today_count = 0;
    $notices = [];
    $routine_files = [];
}

// Load the dashboard HTML template
include __DIR__ . '/dashboard.html';
?>
