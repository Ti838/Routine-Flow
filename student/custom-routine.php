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
    $stmt = $conn->prepare("SELECT * FROM routines WHERE department_id = ? AND semester = ? AND status = 'active' ORDER BY FIELD(day_of_week, 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'), start_time");
    $stmt->execute([$dept_id, $semester]);
    $routines = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $dept_name = $conn->query("SELECT name FROM departments WHERE id = $dept_id")->fetchColumn();
} catch (PDOException $e) {
    $routines = [];
    $dept_name = "Department";
}

// Presentation: Include the view
include 'views/custom-routine.html';
?>