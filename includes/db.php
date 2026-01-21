<?php
/**
 * DATABASE CONNECTION FILE
 * Establishes PDO connection to MySQL database and provides logging utility
 */

// Database configuration constants
$servername = "localhost";      // MySQL server address
$username = "root";             // MySQL username
$password = "";                 // MySQL password (empty for default XAMPP)
$dbname = "routine_flow_db";    // Database name

try {
    // Create PDO connection to MySQL
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    
    // Set error mode to throw exceptions for better error handling
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // Debug: Uncomment line below to confirm connection
    // echo "Connected successfully"; 
} catch (PDOException $e) {
    // Database connection failed - display error and stop execution
    die("Connection failed: " . $e->getMessage());
}

/**
 * LOG ACTIVITY FUNCTION
 * Records user actions to the activity_log table for audit trails
 * 
 * @param PDO $conn - Database connection object
 * @param int $user_id - ID of user performing the action
 * @param string $role - User role (admin, teacher, student)
 * @param string $name - User's full name
 * @param string $type - Action type (create, update, delete, etc.)
 * @param string $title - Action title for display
 * @param string $desc - Detailed description of the action
 * @param string $icon - Font icon class (default: info icon)
 * @param string $badge - Badge type for styling (success, warning, info, danger)
 */
if (!function_exists('logActivity')) {
    function logActivity($conn, $user_id, $role, $name, $type, $title, $desc, $icon = 'ri-information-line', $badge = 'info')
    {
        try {
            // Insert activity record into database
            $stmt = $conn->prepare("INSERT INTO activity_log (user_id, user_role, user_name, action_type, action_title, action_description, icon_class, badge_type) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([$user_id, $role, $name, $type, $title, $desc, $icon, $badge]);
        } catch (PDOException $e) {
            // Silently fail logging to not disrupt main application flow
            // In production, log errors to a file instead
        }
    }
}
?>
