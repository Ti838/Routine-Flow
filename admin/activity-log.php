<?php
require_once '../includes/auth_check.php';
require_once '../includes/layout.php';
require_once '../includes/db.php';

checkAuth('admin');

$name = $_SESSION['user_name'];
$role = $_SESSION['user_role'];

// Logic: Fetch activity logs
try {
    $logs = $conn->query("SELECT * FROM activity_log ORDER BY created_at DESC")->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $logs = [];
}

// Presentation: Include the view
include __DIR__ . '/activity-log.html';
?>