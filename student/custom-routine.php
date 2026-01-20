<?php
require_once '../includes/auth_check.php';
require_once '../includes/layout.php';
require_once '../includes/db.php';

checkAuth('student');

$name = $_SESSION['user_name'];
$role = $_SESSION['user_role'];
$user_id = $_SESSION['user_id'];
$dept_id = $_SESSION['department_id'] ?? 0;
$semester = $_SESSION['semester'] ?? '1st Year, 1st Sem';

$success_msg = null;
$error_msg = null;

// Handle Image Upload
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['upload_image'])) {
    if (isset($_FILES['routine_image']) && $_FILES['routine_image']['error'] === 0) {
        $upload_dir = '../assets/uploads/routines/';
        if (!is_dir($upload_dir))
            mkdir($upload_dir, 0777, true);

        $ext = strtolower(pathinfo($_FILES['routine_image']['name'], PATHINFO_EXTENSION));
        if (!in_array($ext, ['png', 'jpg', 'jpeg', 'pdf'])) {
            $error_msg = "Unsupported file type. Please upload Image or PDF.";
        } else {
            $file_name = 'student_' . $user_id . '_' . time() . '.' . $ext;
            $target_file = $upload_dir . $file_name;

            if (move_uploaded_file($_FILES['routine_image']['tmp_name'], $target_file)) {
                $stmt = $conn->prepare("INSERT INTO routine_files (user_id, role, file_path, file_type, description) VALUES (?, 'student', ?, ?, ?)");
                $stmt->execute([$user_id, $file_name, $ext, $_POST['description'] ?? 'Personal Resource']);
                $success_msg = "Resource uploaded successfully!";
            } else {
                $error_msg = "Failed to upload file.";
            }
        }
    }
}

// Fetch Official Routines for personalization
$stmt = $conn->prepare("
    SELECT r.*, src.color_code, src.is_starred 
    FROM routines r 
    LEFT JOIN student_routine_customizations src ON r.id = src.routine_id AND src.student_id = ?
    WHERE r.department_id = ? AND r.semester = ? AND r.status = 'active'
    ORDER BY r.day_of_week, r.start_time
");
$stmt->execute([$user_id, $dept_id, $semester]);
$routines = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch Personal Image Routines
$stmt = $conn->prepare("SELECT * FROM routine_files WHERE user_id = ? AND role = 'student' ORDER BY created_at DESC");
$stmt->execute([$user_id]);
$personal_files = $stmt->fetchAll(PDO::FETCH_ASSOC);

include __DIR__ . '/custom-routine.html';