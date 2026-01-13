<?php
require_once '../includes/auth_check.php';
require_once '../includes/layout.php';
require_once '../includes/db.php';

checkAuth('student');

$name = $_SESSION['user_name'];
$role = $_SESSION['user_role'];
$dept_id = $_SESSION['department_id'];
$semester = $_SESSION['semester'];

// Logic: Fetch today's classes from database
$today = date('l');
$classes = [];
try {
    $stmt = $conn->prepare("
        SELECT r.*, src.color_code, src.is_starred, src.priority 
        FROM routines r 
        LEFT JOIN student_routine_customizations src ON r.id = src.routine_id AND src.student_id = ?
        WHERE r.department_id = ? AND r.semester = ? AND r.day_of_week = ? AND r.status = 'active'
        ORDER BY r.start_time ASC
    ");
    $stmt->execute([$_SESSION['user_id'], $dept_id, $semester, $today]);
    $classes = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    //
}

// Presentation: Include the view
include 'views/today.html';
?>