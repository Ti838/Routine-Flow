<?php
session_start();
require_once 'includes/db.php';
require_once 'includes/core.php';

// Global Maintenance Check
$login_error = null;
handleMaintenance();

// Handle Login POST
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['login'])) {
    $email = $_POST['email'];
    $password_input = $_POST['password'];

    $found_user = null;
    $user_role = '';

    // Helper function to check credentials in a specific table
    function checkTable($conn, $table, $email, $role_name)
    {
        $stmt = $conn->prepare("SELECT * FROM $table WHERE email = ?");
        $stmt->execute([$email]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($data) {
            return ['data' => $data, 'role' => $role_name];
        }
        return null;
    }

    // Check Tables Sequentially: Admins -> Teachers -> Students
    $admin = checkTable($conn, 'admins', $email, 'Admin');
    if ($admin) {
        $found_user = $admin['data'];
        $user_role = 'Admin';
    } else {
        $teacher = checkTable($conn, 'teachers', $email, 'Teacher');
        if ($teacher) {
            $found_user = $teacher['data'];
            $user_role = 'Teacher';
        } else {
            $student = checkTable($conn, 'students', $email, 'Student');
            if ($student) {
                $found_user = $student['data'];
                $user_role = 'Student';
            }
        }
    }

    // Authenticate
    if ($found_user) {
        if (password_verify($password_input, $found_user['password'])) {
            $_SESSION['user_id'] = $found_user['id'];
            $_SESSION['user_name'] = $found_user['full_name'] ?? $found_user['name'];
            $_SESSION['user_role'] = strtolower($user_role);
            $_SESSION['user_email'] = $found_user['email'];
            $_SESSION['profile_pic'] = $found_user['profile_pic'] ?? null;

            if ($_SESSION['user_role'] === 'student') {
                $_SESSION['student_id'] = $found_user['student_id'] ?? null;
                $_SESSION['department_id'] = $found_user['department_id'] ?? null;
                $_SESSION['semester'] = $found_user['semester'] ?? null;
            }

            // Redirect based on Role
            if ($_SESSION['user_role'] === 'admin') {
                header("Location: ./admin/dashboard.php");
            } elseif ($_SESSION['user_role'] === 'teacher') {
                header("Location: teacher/dashboard.php");
            } else {
                header("Location: student/dashboard.php");
            }
            exit();
        } else {
            $login_error = "Invalid Password.";
        }
    } else {
        $login_error = "No account found with this email.";
    }
}

// Fetch departments for Signup
$departments = [];
try {
    $dept_stmt = $conn->query("SELECT id, name FROM departments ORDER BY name ASC LIMIT 5");
    $departments = $dept_stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    // Silent fail
}

// Presentation: Include the view
include 'views/login.html';
?>