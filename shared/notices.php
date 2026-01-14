<?php
require_once '../includes/auth_check.php';
require_once '../includes/layout.php';
require_once '../includes/db.php';

checkAuth();

$name = $_SESSION['user_name'];
$role = $_SESSION['user_role'];
$user_id = $_SESSION['user_id'];

// Logic: Fetch all notices
$notices = [];
try {
    $stmt = $conn->query("SELECT * FROM notices ORDER BY created_at DESC");
    $notices = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    //
}

// Presentation: Include the view
include 'views/notices.html';
?>