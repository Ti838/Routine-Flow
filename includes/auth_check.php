<?php
require_once 'core.php';

function checkAuth($required_role = null)
{
    handleMaintenance();
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    if (!isset($_SESSION['user_id'])) {
        header('Location: ../login.php');
        exit();
    }

    if ($required_role && $_SESSION['user_role'] !== $required_role) {
        // Forbidden access for this role
        header('Location: ../login.php');
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