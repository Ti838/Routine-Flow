<?php
require_once '../includes/auth_check.php';
require_once '../includes/layout.php';
require_once '../includes/db.php';

checkAuth('student');

$name = $_SESSION['user_name'];
$role = $_SESSION['user_role'];
$dept_id = $_SESSION['department_id'];
$semester = $_SESSION['semester'];

$days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'];
$routine_data = [];
try {
    $stmt = $conn->prepare("SELECT * FROM routines WHERE department_id = ? AND semester = ? AND status = 'active' ORDER BY start_time ASC");
    $stmt->execute([$dept_id, $semester]);
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $routine_data[$row['day_of_week']][] = $row;
    }
} catch (PDOException $e) {
}

include __DIR__ . '/weekly.html';
?>
