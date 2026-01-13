<?php
require_once '../includes/auth_check.php';
require_once '../includes/db.php';
require_once '../includes/core.php';

checkAuth('admin');

$current = getSystemSetting('maintenance_mode', '0');
$new = ($current === '1') ? '0' : '1';

if (setSystemSetting('maintenance_mode', $new)) {
    // Log Activity
    $action = ($new === '1') ? 'Activated maintenance mode' : 'Deactivated maintenance mode';
    $logStmt = $conn->prepare("INSERT INTO activity_log (user_id, user_role, user_name, action_type, action_title, action_description, icon_class, badge_type) VALUES (?, 'admin', ?, 'system', 'Maintenance Updated', ?, 'ri-settings-4-line', ?)");
    $logStmt->execute([$_SESSION['user_id'], $_SESSION['user_name'], $action, ($new === '1' ? 'alert' : 'success')]);

    echo json_encode(['success' => true, 'mode' => $new]);
} else {
    echo json_encode(['success' => false]);
}
