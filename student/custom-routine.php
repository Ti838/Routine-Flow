<?php
require_once '../includes/auth_check.php';
require_once '../includes/layout.php';
require_once '../includes/db.php';

checkAuth('student');

$name = $_SESSION['user_name'];
$role = $_SESSION['user_role'];
$dept_id = $_SESSION['department_id'];
$user_id = $_SESSION['user_id'];

// Get Filters
$semester = $_GET['semester'] ?? $_SESSION['semester'] ?? '1st Year, 1st Sem';

$routines = [];
try {
    // Fetch Routines with Customizations (Stars, Colors)
    $stmt = $conn->prepare("
        SELECT r.*, 
               c.color_code, 
               c.is_starred as is_favorite, 
               c.notes as custom_notes,
               'class' as type
        FROM routines r
        LEFT JOIN student_routine_customizations c 
        ON r.id = c.routine_id AND c.student_id = ?
        WHERE r.department_id = ? 
        AND r.semester = ? 
        AND r.status = 'active' 
    ");
    $stmt->execute([$user_id, $dept_id, $semester]);
    $official_routines = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Fetch Personal Tasks
    $stmt = $conn->prepare("
        SELECT id, 
               title as subject_name, 
               day_of_week, 
               start_time, 
               end_time, 
               'Personal Task' as teacher_name, 
               '' as room_number, 
               color as color_code, 
               0 as is_favorite, 
               '' as custom_notes,
               'personal' as type
        FROM personal_events 
        WHERE student_id = ?
    ");
    $stmt->execute([$user_id]);
    $personal_tasks = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Merge and Sort
    $routines = array_merge($official_routines, $personal_tasks);

    // Sort logic
    $dayOrder = ['Monday' => 1, 'Tuesday' => 2, 'Wednesday' => 3, 'Thursday' => 4, 'Friday' => 5, 'Saturday' => 6, 'Sunday' => 7];
    usort($routines, function ($a, $b) use ($dayOrder) {
        $da = $dayOrder[$a['day_of_week']] ?? 8;
        $db = $dayOrder[$b['day_of_week']] ?? 8;
        if ($da !== $db)
            return $da - $db;
        return strcmp($a['start_time'], $b['start_time']);
    });

    $dept_name = $conn->query("SELECT name FROM departments WHERE id = $dept_id")->fetchColumn();
} catch (PDOException $e) {
    $routines = [];
    $dept_name = "Department";
}

// Presentation: Include the view
include 'views/custom-routine.html';
?>