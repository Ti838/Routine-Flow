<?php
/**
 * ADD USER API ENDPOINT
 * Admin-only endpoint to create new users (students and teachers)
 * Includes email duplicate checking and activity logging
 */

// Set response headers for JSON API
header('Content-Type: application/json');

// Import database and authentication
require_once '../includes/auth_check.php';
require_once '../includes/db.php';

// Verify requesting user is admin
checkAuth('admin');

// Only allow POST requests
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $role = $_POST['role'];
    $full_name = $_POST['full_name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);  // Hash password securely
    $dept_id = $_POST['dept_id'];

    // Optional fields based on role
    $student_id_val = $_POST['student_id'] ?? null;    // For students
    $semester = $_POST['semester'] ?? null;             // For students
    $teacher_id_val = $_POST['teacher_id'] ?? null;    // For teachers

    // Check for duplicate email across all user tables
    $tables = ['admins', 'teachers', 'students'];
    foreach ($tables as $t) {
        $check = $conn->prepare("SELECT id FROM $t WHERE email = ?");
        $check->execute([$email]);
        if ($check->fetch()) {
            // Email already exists
            echo json_encode(['success' => false, 'message' => 'Email address already registered']);
            exit;
        }
    }

    try {
        // Start transaction for data integrity
        $conn->beginTransaction();

        // Insert user based on role
        if ($role === 'student') {
            // Create student account with semester and department
            $stmt = $conn->prepare("INSERT INTO students (email, password, full_name, student_id, department_id, semester) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->execute([$email, $password, $full_name, $student_id_val, $dept_id, $semester]);
        } else if ($role === 'teacher') {
            // Create teacher account with department
            $stmt = $conn->prepare("INSERT INTO teachers (email, password, full_name, teacher_id, department_id) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([$email, $password, $full_name, $teacher_id_val, $dept_id]);
        } else {
            // Invalid role
            throw new Exception("Invalid role");
        }

        // Log the user creation action for audit trail
        logActivity($conn, $_SESSION['user_id'], 'admin', $_SESSION['user_name'], 'create', 'User Created', "Added new $role: $full_name", 'ri-user-add-line', 'success');

        // Commit all changes to database
        $conn->commit();
        
        // Send success response
        echo json_encode(['success' => true, 'message' => 'User created successfully']);
    } catch (Exception $e) {
        // Rollback transaction on any error
        if ($conn->inTransaction())
            $conn->rollBack();
        
        // Send error response
        echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
    }
}
?>