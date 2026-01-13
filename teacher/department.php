<?php
require_once '../includes/auth_check.php';
require_once '../includes/layout.php';
require_once '../includes/db.php';

checkAuth('teacher');

$name = $_SESSION['user_name'];
$role = $_SESSION['user_role'];
$dept_id = $_SESSION['department_id'];

try {
    $dept_stmt = $conn->prepare("SELECT name FROM departments WHERE id = ?");
    $dept_stmt->execute([$dept_id]);
    $dept_name = $dept_stmt->fetchColumn();

    $stmt = $conn->prepare("SELECT * FROM routines WHERE department_id = ? AND status = 'active' ORDER BY FIELD(day_of_week, 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'), start_time ASC");
    $stmt->execute([$dept_id]);
    $routines = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $routines = [];
    $dept_name = "Department";
}

include 'views/department.html';
