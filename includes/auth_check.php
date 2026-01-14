<?php
require_once 'core.php';

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
    // Determine the base path relative to the current file
// This allows includes to work across different folder depths
    $depth = count(explode(DIRECTORY_SEPARATOR, $_SERVER['PHP_SELF'])) - 2;
    return str_repeat('../', max(0, $depth - 1));
}
?>