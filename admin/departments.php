<?php
require_once '../includes/auth_check.php';
require_once '../includes/layout.php';
require_once '../includes/db.php';

checkAuth('admin');

$name = $_SESSION['user_name'];
$role = $_SESSION['user_role'];

// Logic: Fetch departments with stats
try {
    $depts = $conn->query("
        SELECT d.*, 
        (SELECT COUNT(*) FROM students WHERE department_id = d.id) as student_count,
        (SELECT COUNT(*) FROM routines WHERE department_id = d.id AND status = 'active') as routine_count
        FROM departments d 
        ORDER BY d.name ASC
    ")->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $depts = [];
}

// Presentation: Include the view
include 'views/departments.html';
?>