<?php
session_start();
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/core.php';
handleMaintenance();

try {
    $dept_count = $conn->query("SELECT COUNT(*) FROM departments")->fetchColumn();
    $user_count = $conn->query("SELECT (SELECT COUNT(*) FROM students) + (SELECT COUNT(*) FROM teachers) + (SELECT COUNT(*) FROM admins)")->fetchColumn();
} catch (PDOException $e) {
    $dept_count = 12;
    $user_count = 1200;
}

$template = file_get_contents('index.html');

$placeholders = [
    '{{DEPT_COUNT}}' => $dept_count,
    '{{USER_COUNT}}' => number_format($user_count)
];

$login_btn_html = '
                    <a href="login.php" id="nav-action-btn" class="bg-primary-blue text-white px-6 py-2.5 rounded-xl font-bold hover:bg-primary-dark transition-all shadow-lg shadow-indigo-500/20">Login</a>';

if (isset($_SESSION['user_id'])) {
    $role = $_SESSION['user_role'];
    $dashboard_btn = '<a href="' . $role . '/dashboard.php" id="nav-action-btn" class="bg-primary-blue text-white px-6 py-2.5 rounded-xl font-bold hover:bg-primary-dark transition-all shadow-lg shadow-indigo-500/20">Dashboard</a>';
    $template = str_replace($login_btn_html, $dashboard_btn, $template);
}

echo strtr($template, $placeholders);
?>
