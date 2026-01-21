<?php
require_once '../includes/auth_check.php';
require_once '../includes/layout.php';
require_once '../includes/db.php';

checkAuth('admin');

$name = $_SESSION['user_name'];
$role = $_SESSION['user_role'];

// Fetch all departments for filtering
$departments = $conn->query("SELECT * FROM departments ORDER BY name ASC")->fetchAll(PDO::FETCH_ASSOC);

// Fetch all routines with department details
$query = "SELECT r.*, d.code as dept_code 
          FROM routines r 
          LEFT JOIN departments d ON r.department_id = d.id 
          ORDER BY r.day_of_week, r.start_time ASC";
$routines = $conn->query($query)->fetchAll(PDO::FETCH_ASSOC);

include __DIR__ . '/manage-routines.view.php';

