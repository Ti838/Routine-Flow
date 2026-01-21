<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "routine_flow_db";

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    // set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // echo "Connected successfully"; 
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

if (!function_exists('logActivity')) {
    function logActivity($conn, $user_id, $role, $name, $type, $title, $desc, $icon = 'ri-information-line', $badge = 'info')
    {
        try {
            $stmt = $conn->prepare("INSERT INTO activity_log (user_id, user_role, user_name, action_type, action_title, action_description, icon_class, badge_type) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([$user_id, $role, $name, $type, $title, $desc, $icon, $badge]);
        } catch (PDOException $e) {
            // Silently fail logging to not disrupt main flow
        }
    }
}
?>
