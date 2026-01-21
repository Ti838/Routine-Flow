<?php
/**
 * INDEX / HOMEPAGE FILE
 * Displays landing page with system statistics
 * Shows login/dashboard button based on user authentication status
 */

session_start();

// Import database and core utilities
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/core.php';

// Check if system is in maintenance mode
handleMaintenance();

// Try to fetch statistics from database
try {
    // Count total departments
    $dept_count = $conn->query("SELECT COUNT(*) FROM departments")->fetchColumn();
    
    // Count total users across all roles
    $user_count = $conn->query("SELECT (SELECT COUNT(*) FROM students) + (SELECT COUNT(*) FROM teachers) + (SELECT COUNT(*) FROM admins)")->fetchColumn();
} catch (PDOException $e) {
    // If database query fails, use fallback numbers for display
    $dept_count = 12;
    $user_count = 1200;
}

// Load the HTML template
$template = file_get_contents('index.html');

// Create placeholder replacements for statistics
$placeholders = [
    '{{DEPT_COUNT}}' => $dept_count,              // Department count
    '{{USER_COUNT}}' => number_format($user_count)  // Formatted user count
];

// Default login button HTML
$login_btn_html = '
                    <a href="login.php" id="nav-action-btn" class="bg-primary-blue text-white px-6 py-2.5 rounded-xl font-bold hover:bg-primary-dark transition-all shadow-lg shadow-indigo-500/20">Login</a>';

// If user is logged in, replace login button with dashboard link
if (isset($_SESSION['user_id'])) {
    $role = $_SESSION['user_role'];
    
    // Create dashboard button with role-specific URL
    $dashboard_btn = '<a href="' . $role . '/dashboard.php" id="nav-action-btn" class="bg-primary-blue text-white px-6 py-2.5 rounded-xl font-bold hover:bg-primary-dark transition-all shadow-lg shadow-indigo-500/20">Dashboard</a>';
    
    // Replace login button with dashboard button in template
    $template = str_replace($login_btn_html, $dashboard_btn, $template);
}

// Replace all placeholders and display final HTML
echo strtr($template, $placeholders);
?>
