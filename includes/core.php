<?php
/**
 * CORE UTILITY FUNCTIONS
 * Contains helper functions for system settings and maintenance mode
 */

require_once __DIR__ . '/db.php';

/**
 * GET SYSTEM SETTING FUNCTION
 * Retrieves configuration values from system_settings table
 * 
 * @param string $key - The setting key to retrieve
 * @param mixed $default - Default value if setting not found
 * @return mixed - The setting value or default
 */
function getSystemSetting($key, $default = null)
{
    global $conn;
    try {
        // Ensure system_settings table exists (auto-create if missing)
        $conn->exec("CREATE TABLE IF NOT EXISTS system_settings (
            setting_key VARCHAR(50) PRIMARY KEY,
            setting_value TEXT,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        )");

        // Query the setting from database
        $stmt = $conn->prepare("SELECT setting_value FROM system_settings WHERE setting_key = ?");
        $stmt->execute([$key]);
        $val = $stmt->fetchColumn();
        
        // Return setting value or default if not found
        return ($val !== false) ? $val : $default;
    } catch (PDOException $e) {
        // If error occurs, return default value
        return $default;
    }
}

/**
 * SET SYSTEM SETTING FUNCTION
 * Saves or updates a configuration value in system_settings table
 * 
 * @param string $key - The setting key to save
 * @param mixed $value - The value to save
 * @return bool - True on success, false on failure
 */
function setSystemSetting($key, $value)
{
    global $conn;
    try {
        // Insert or update the setting (uses ON DUPLICATE KEY UPDATE for upsert)
        $stmt = $conn->prepare("INSERT INTO system_settings (setting_key, setting_value) VALUES (?, ?) ON DUPLICATE KEY UPDATE setting_value = ?");
        $stmt->execute([$key, $value, $value]);
        return true;
    } catch (PDOException $e) {
        // Log error in production instead of silently failing
        return false;
    }
}

/**
 * HANDLE MAINTENANCE FUNCTION
 * Checks if system is in maintenance mode and redirects non-admin users
 * Admins can always bypass maintenance mode to perform fixes
 */
function handleMaintenance()
{
    // Start session if not already started
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    // Allow admins to bypass maintenance mode for emergency fixes
    if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin') {
        return;
    }

    // Get maintenance mode setting from database
    $is_maintenance = getSystemSetting('maintenance_mode', '0');

    // Get current page name to avoid redirect loops
    $current_page = basename($_SERVER['PHP_SELF']);
    
    // If maintenance mode is enabled and not on maintenance/login page, redirect user
    if ($is_maintenance === '1' && $current_page !== 'maintenance.php' && $current_page !== 'login.php') {
        // Calculate relative path depth to maintenance.php
        $depth = count(explode(DIRECTORY_SEPARATOR, $_SERVER['PHP_SELF'])) - 2;
        $prefix = str_repeat('../', max(0, $depth - 1));

        // If we are in the root directory, no prefix needed
        if ($depth <= 1)
            $prefix = '';

        // Redirect to maintenance page
        header("Location: " . $prefix . "maintenance.php");
        exit;
    }
}
?>