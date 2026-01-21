<?php
/**
 * LOGOUT PAGE
 * Handles user session termination and cleanup
 * Removes all session data and redirects to login page
 */

// Start session to access current session data
session_start();

// Clear all session variables
session_unset();

// Destroy the session completely
session_destroy();

// Redirect user back to login page
header("Location: login.php");
exit();
?>
