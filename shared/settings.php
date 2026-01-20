<?php
require_once '../includes/auth_check.php';
require_once '../includes/layout.php';
require_once '../includes/db.php';

checkAuth();

$name = $_SESSION['user_name'];
$role = $_SESSION['user_role'];

// Presentation: Include the view
include __DIR__ . '/settings.html';
?>