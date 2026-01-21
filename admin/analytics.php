<?php
require_once '../includes/auth_check.php';
require_once '../includes/layout.php';
require_once '../includes/db.php';

checkAuth('admin');

$name = $_SESSION['user_name'];
$role = $_SESSION['user_role'];

// Fetch Summary Statistics
try {
    // Total counts
    $totalRoutines = $conn->query("SELECT COUNT(*) FROM routines WHERE status = 'active'")->fetchColumn();
    $totalTeachers = $conn->query("SELECT COUNT(DISTINCT teacher_name) FROM routines WHERE status = 'active'")->fetchColumn();
    $totalRooms = $conn->query("SELECT COUNT(DISTINCT room_number) FROM routines WHERE status = 'active' AND room_number != ''")->fetchColumn();
    $totalDepartments = $conn->query("SELECT COUNT(*) FROM departments")->fetchColumn();
} catch (PDOException $e) {
    $totalRoutines = 0;
    $totalTeachers = 0;
    $totalRooms = 0;
    $totalDepartments = 0;
}

// Presentation: Include the view
include __DIR__ . '/analytics.html';
?>
