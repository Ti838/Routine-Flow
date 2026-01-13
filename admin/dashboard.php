<?php
require_once '../includes/auth_check.php';
require_once '../includes/layout.php';
require_once '../includes/db.php';

checkAuth('admin');

$name = $_SESSION['user_name'];
$role = $_SESSION['user_role'];

// Logic: Fetch stats
try {
    $stats = [
        'users' => $conn->query("SELECT (SELECT COUNT(*) FROM students) + (SELECT COUNT(*) FROM teachers) + (SELECT COUNT(*) FROM admins)")->fetchColumn(),
        'departments' => $conn->query("SELECT COUNT(*) FROM departments")->fetchColumn(),
        'routines' => $conn->query("SELECT COUNT(*) FROM routines")->fetchColumn(),
    ];

    require_once '../includes/core.php';
    $maintenance_mode = getSystemSetting('maintenance_mode', '0');

    $recent_activities = $conn->query("SELECT * FROM activity_log ORDER BY created_at DESC LIMIT 5")->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $stats = ['users' => 0, 'departments' => 0, 'routines' => 0];
    $maintenance_mode = '0';
    $recent_activities = [];
}

// Presentation: Include the view
include 'views/dashboard.html';
?>