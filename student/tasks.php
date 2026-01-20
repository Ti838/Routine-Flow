<?php
require_once '../includes/auth_check.php';
require_once '../includes/layout.php';
require_once '../includes/db.php';

checkAuth('student');

$name = $_SESSION['user_name'];
$role = $_SESSION['user_role'];
$user_id = $_SESSION['user_id'];

// Initial fetch for server-side if needed, but we'll use API for JS
include __DIR__ . '/tasks.html';
