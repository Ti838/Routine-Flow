<?php
/**
 * ADMIN DASHBOARD PAGE
 * Displays system statistics and overview for administrators
 * Shows user count, departments, routines, and recent activities
 */

// Import authentication, layout, and database
require_once '../includes/auth_check.php';
require_once '../includes/layout.php';
require_once '../includes/db.php';

// Verify user is logged in as admin
checkAuth('admin');

// Get current admin's name and role from session
$name = $_SESSION['user_name'];
$role = $_SESSION['user_role'];

// Fetch dashboard statistics from database
try {
    // Gather key system statistics
    $stats = [
        'users' => $conn->query("SELECT (SELECT COUNT(*) FROM students) + (SELECT COUNT(*) FROM teachers) + (SELECT COUNT(*) FROM admins)")->fetchColumn(),  // Total users across all roles
        'departments' => $conn->query("SELECT COUNT(*) FROM departments")->fetchColumn(),  // Total departments
        'routines' => $conn->query("SELECT COUNT(*) FROM routines")->fetchColumn(),        // Total routine entries
    ];

    // Get system maintenance mode status
    require_once '../includes/core.php';
    $maintenance_mode = getSystemSetting('maintenance_mode', '0');

    // Fetch 5 most recent activity log entries
    $recent_activities = $conn->query("SELECT * FROM activity_log ORDER BY created_at DESC LIMIT 5")->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    // If query fails, use default values
    $stats = ['users' => 0, 'departments' => 0, 'routines' => 0];
    $maintenance_mode = '0';
    $recent_activities = [];
}

// Load the dashboard HTML template for display
include __DIR__ . '/dashboard.html';
?>

