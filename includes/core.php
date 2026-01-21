<?php
require_once __DIR__ . '/db.php';

// Helper to get system settings
function getSystemSetting($key, $default = null)
{
    global $conn;
    try {
        // Ensure table exists
        $conn->exec("CREATE TABLE IF NOT EXISTS system_settings (
            setting_key VARCHAR(50) PRIMARY KEY,
            setting_value TEXT,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        )");

        $stmt = $conn->prepare("SELECT setting_value FROM system_settings WHERE setting_key = ?");
        $stmt->execute([$key]);
        $val = $stmt->fetchColumn();
        return ($val !== false) ? $val : $default;
    } catch (PDOException $e) {
        return $default;
    }
}

function setSystemSetting($key, $value)
{
    global $conn;
    try {
        $stmt = $conn->prepare("INSERT INTO system_settings (setting_key, setting_value) VALUES (?, ?) ON DUPLICATE KEY UPDATE setting_value = ?");
        $stmt->execute([$key, $value, $value]);
        return true;
    } catch (PDOException $e) {
        return false;
    }
}

// Global Maintenance Check
function handleMaintenance()
{
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    // Admins can bypass maintenance mode to fix things
    if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin') {
        return;
    }

    $is_maintenance = getSystemSetting('maintenance_mode', '0');

    // Check if current page is already maintenance.php or a specific bypass page
    $current_page = basename($_SERVER['PHP_SELF']);
    if ($is_maintenance === '1' && $current_page !== 'maintenance.php' && $current_page !== 'login.php') {
        // Determine path to maintenance.php
        $depth = count(explode(DIRECTORY_SEPARATOR, $_SERVER['PHP_SELF'])) - 2;
        $prefix = str_repeat('../', max(0, $depth - 1));

        // If we are in the root, prefix is empty
        if ($depth <= 1)
            $prefix = '';

        header("Location: " . $prefix . "maintenance.php");
        exit;
    }
}

