<?php
header('Content-Type: application/json');
require_once '../includes/auth_check.php';
require_once '../includes/db.php';

checkAuth('admin');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $role = $_POST['role'];
    $full_name = $_POST['full_name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $dept_id = $_POST['dept_id'];

    // Additional fields
    $student_id = $_POST['student_id'] ?? null;
    $semester = $_POST['semester'] ?? null;
    $teacher_id = $_POST['teacher_id'] ?? null;

    try {
        $conn->beginTransaction();

        // 1. Insert into base users table
        $stmt = $conn->prepare("INSERT INTO users (email, password, full_name, role) VALUES (?, ?, ?, ?)");
        $stmt->execute([$email, $password, $full_name, $role]);
        $user_id = $conn->lastInsertId();

        // 2. Insert into role-specific table
        if ($role === 'student') {
            $stmt = $conn->prepare("INSERT INTO students (user_id, student_id, department_id, semester) VALUES (?, ?, ?, ?)");
            $stmt->execute([$user_id, $student_id, $dept_id, $semester]);
        } else if ($role === 'teacher') {
            $stmt = $conn->prepare("INSERT INTO teachers (user_id, teacher_id, department_id) VALUES (?, ?, ?)");
            $stmt->execute([$user_id, $teacher_id, $dept_id]);
        }

        // Log activity
        logActivity($conn, $_SESSION['user_id'], 'admin', $_SESSION['user_name'], 'create', 'User Created', "Added new $role: $full_name", 'ri-user-add-line', 'success');

        $conn->commit();
        echo json_encode(['success' => true, 'message' => 'User created successfully']);
    } catch (Exception $e) {
        $conn->rollBack();
        echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
    }
}
