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

    // Fetch today's classes for this teacher
    $today = date('l');
    $stmt = $conn->prepare("SELECT * FROM routines WHERE (teacher_id = ? OR teacher_name = ?) AND day_of_week = ? AND status = 'active' ORDER BY start_time ASC");
    $stmt->execute([$_SESSION['user_id'], $name, $today]);
    $today_classes = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $classes_today_count = count($today_classes);

    // Fetch recent notices
    $stmt = $conn->prepare("SELECT * FROM notices ORDER BY created_at DESC LIMIT 3");
    $stmt->execute();
    $notices = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    $dept_name = 'Error';
    $today_classes = [];
    $classes_today_count = 0;
    $notices = [];
}

// Presentation: Include the view
include 'views/dashboard.html';
?>