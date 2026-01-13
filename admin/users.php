<?php
require_once '../includes/auth_check.php';
require_once '../includes/layout.php';
require_once '../includes/db.php';

checkAuth('admin');

$name = $_SESSION['user_name'];
$role = $_SESSION['user_role'];

// Logic: Fetch users
try {
    $students = $conn->query("SELECT s.*, d.name as dept_name FROM students s LEFT JOIN departments d ON s.department_id = d.id ORDER BY s.created_at DESC")->fetchAll(PDO::FETCH_ASSOC);
    $teachers = $conn->query("SELECT t.*, d.name as dept_name FROM teachers t LEFT JOIN departments d ON t.department_id = d.id ORDER BY t.created_at DESC")->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $students = [];
    $teachers = [];
}

// Presentation: Include the view
include 'views/users.html';
?>