<?php
/**
 * Session Check API
 * Returns current session status for multi-tab monitoring
 */
session_start();

header('Content-Type: application/json');

echo json_encode([
    'user_id' => $_SESSION['user_id'] ?? null,
    'user_role' => $_SESSION['user_role'] ?? null,
    'user_name' => $_SESSION['user_name'] ?? null,
    'login_time' => $_SESSION['login_time'] ?? null
]);
?>
