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
    $student_id_val = $_POST['student_id'] ?? null;
    $semester = $_POST['semester'] ?? null;
    $teacher_id_val = $_POST['teacher_id'] ?? null;

    // Check for duplicate email in ALL three tables
    $tables = ['admins', 'teachers', 'students'];
    foreach ($tables as $t) {
        $check = $conn->prepare("SELECT id FROM $t WHERE email = ?");
        $check->execute([$email]);
        if ($check->fetch()) {
            echo json_encode(['success' => false, 'message' => 'Email address already registered']);
            exit;
        }
    }

    try {
        $conn->beginTransaction();

        if ($role === 'student') {
            $stmt = $conn->prepare("INSERT INTO students (email, password, full_name, student_id, department_id, semester) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->execute([$email, $password, $full_name, $student_id_val, $dept_id, $semester]);
        } else if ($role === 'teacher') {
            $stmt = $conn->prepare("INSERT INTO teachers (email, password, full_name, teacher_id, department_id) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([$email, $password, $full_name, $teacher_id_val, $dept_id]);
        } else {
            throw new Exception("Invalid role");
        }

        // Log activity
        logActivity($conn, $_SESSION['user_id'], 'admin', $_SESSION['user_name'], 'create', 'User Created', "Added new $role: $full_name", 'ri-user-add-line', 'success');

        $conn->commit();
        echo json_encode(['success' => true, 'message' => 'User created successfully']);
    } catch (Exception $e) {
        if ($conn->inTransaction())
            $conn->rollBack();
        echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
    }
}
