<?php
/**
 * AUTHENTICATION CHECK FILE
 * Provides authentication middleware for protected pages
 */

require_once __DIR__ . '/core.php';

/**
 * CHECK AUTH FUNCTION
 * Verifies user is logged in and has required role
 * Redirects to login if authentication fails
 * 
 * @param string $required_role - Optional role requirement (admin, teacher, student)
 * @throws Redirects on auth failure
 */
function checkAuth($required_role = null)
{
    // Start session if not already active
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    
    // Check if system is in maintenance mode
    handleMaintenance();

    // Get the base URL for correct redirects
    $baseUrl = getBaseUrl();

    // Check if user_id is set in session (user logged in)
    if (!isset($_SESSION['user_id'])) {
        // User not logged in - redirect to login page
        header("Location: {$baseUrl}login.php");
        exit();
    }

    // If role check required, verify user has correct role
    if ($required_role && $_SESSION['user_role'] !== $required_role) {
        // User doesn't have permission for this page - redirect to index
        header("Location: {$baseUrl}index.php");
        exit();
    }
}

/**
 * GET BASE URL FUNCTION
 * Calculates the relative path to root directory for proper URL redirects
 * 
 * @return string - Relative path prefix (../ repeated appropriate times)
 */
function getBaseUrl()
{
    // Convert backslashes to forward slashes for URL consistency
    $script_name = str_replace('\\', '/', $_SERVER['PHP_SELF']);
    
    // Calculate directory depth from root
    $depth = count(explode('/', $script_name)) - 2;
    
    // Return appropriate number of ../ to reach root
    return str_repeat('../', max(0, $depth - 1));
}
?>
