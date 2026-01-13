<?php
require_once '../includes/auth_check.php';
require_once '../includes/layout.php';
require_once '../includes/db.php';
require_once '../includes/file_handler.php';

checkAuth('teacher');

$name = $_SESSION['user_name'];
$role = $_SESSION['user_role'];
$dept_id = $_SESSION['department_id'];

// Logic: Fetch department name
try {
    $stmt = $conn->prepare("SELECT name FROM departments WHERE id = ?");
    $stmt->execute([$dept_id]);
    $dept_name = $stmt->fetchColumn() ?: 'General Engineering';
} catch (PDOException $e) {
    $dept_name = 'Error';
}

// Logic: Handle Upload
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['upload_routine'])) {
    if (isset($_FILES['routine_file'])) {
        $result = uploadRoutineFile($_FILES['routine_file'], $_SESSION['user_id'], 'teacher', [
            'department' => $dept_name,
            'semester' => $_POST['semester']
        ]);

        if ($result['success']) {
            $success_msg = "Routine published effectively to the department!";
        } else {
            $error_msg = $result['error'];
        }
    }
}

// Presentation: Include the view
include 'views/create-routine.html';
?>