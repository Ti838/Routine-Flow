<?php
require_once '../includes/auth_check.php';
require_once '../includes/layout.php';
require_once '../includes/db.php';

checkAuth('teacher');

$name = $_SESSION['user_name'];
$role = $_SESSION['user_role'];
$dept_id = $_SESSION['department_id'];

try {
    $dept_stmt = $conn->prepare("SELECT name FROM departments WHERE id = ?");
    $dept_stmt->execute([$dept_id]);
    $dept_name = $dept_stmt->fetchColumn();

    $selected_semester = $_GET['semester'] ?? 'All Semesters';
    $params = [$dept_id];
    $sql = "SELECT * FROM routines WHERE department_id = ? AND status = 'active'";

    if ($selected_semester !== 'All Semesters') {
        $sql .= " AND semester = ?";
        $params[] = $selected_semester;
    }

    $sql .= " ORDER BY FIELD(day_of_week, 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'), start_time ASC";

    $stmt = $conn->prepare($sql);
    $stmt->execute($params);
    $routines = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $routines = [];
    $dept_name = "Department";
}

include __DIR__ . '/department.html';

