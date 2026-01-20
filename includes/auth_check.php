<?php
require_once __DIR__ . '/core.php';

function checkAuth($required_role = null)
{
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    handleMaintenance();

    $baseUrl = getBaseUrl();

    if (!isset($_SESSION['user_id'])) {
        header("Location: {$baseUrl}login.php");
        exit();
    }

    if ($required_role && $_SESSION['user_role'] !== $required_role) {
        // Forbidden access for this role - redirect to their dashboard or login
        header("Location: {$baseUrl}index.php");
        exit();
    }
}

function getBaseUrl()
{
    // Use forward slash for URLs regardless of OS
    $script_name = str_replace('\\', '/', $_SERVER['PHP_SELF']);
    $depth = count(explode('/', $script_name)) - 2;
    return str_repeat('../', max(0, $depth - 1));
}
?>