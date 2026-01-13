<?php
require_once '../includes/auth_check.php';
require_once '../includes/layout.php';
require_once '../includes/db.php';

checkAuth('teacher');

$name = $_SESSION['user_name'];
$role = $_SESSION['user_role'];
$user_id = $_SESSION['user_id'];

try {
    $today = date('l');
    $stmt = $conn->prepare("SELECT * FROM routines WHERE (teacher_id = ? OR teacher_name = ?) AND day_of_week = ? AND status = 'active' ORDER BY start_time ASC");
    $stmt->execute([$user_id, $name, $today]);
    $classes = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $classes = [];
}

include 'views/today.html';
