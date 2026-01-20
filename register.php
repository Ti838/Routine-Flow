<?php
session_start();
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/core.php';
handleMaintenance();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // 1. Verify Captcha (Synchronized with JS logic)
    $c_n1 = isset($_POST['captcha_n1']) ? intval($_POST['captcha_n1']) : 0;
    $c_n2 = isset($_POST['captcha_n2']) ? intval($_POST['captcha_n2']) : 0;
    $c_input = isset($_POST['captcha_input']) ? intval($_POST['captcha_input']) : -1;

    if ($c_input !== ($c_n1 + $c_n2)) {
        echo "<script>alert('Incorrect Math Answer. Registration Failed.'); window.history.back();</script>";
        exit();
    }
    $name = $_POST['name'];
    $username = trim($_POST['username']);
    $email = $_POST['email'];
    $password = $_POST['password'];
    $role = $_POST['role']; // Student, Teacher, or Admin
    $gender = isset($_POST['gender']) ? $_POST['gender'] : null;
    $department_id = isset($_POST['department_id']) ? $_POST['department_id'] : null;

    if (empty($name) || empty($username) || empty($email) || empty($password) || empty($role) || empty($gender)) {
        echo "<script>alert('Please fill in all required fields.'); window.history.back();</script>";
        exit();
    }

    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    $semester = isset($_POST['semester']) ? $_POST['semester'] : null;

    try {
        // Fetch department name for consistency
        $dept_name = null;
        if ($department_id) {
            $d_stmt = $conn->prepare("SELECT name FROM departments WHERE id = ?");
            $d_stmt->execute([$department_id]);
            $dept_name = $d_stmt->fetchColumn();
        }

        if ($role === 'Admin') {
            $stmt = $conn->prepare("INSERT INTO admins (full_name, username, email, password, gender) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([$name, $username, $email, $hashed_password, $gender]);
        } elseif ($role === 'Teacher') {
            $stmt = $conn->prepare("INSERT INTO teachers (full_name, username, email, password, gender, department_id, department) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([$name, $username, $email, $hashed_password, $gender, $department_id, $dept_name]);
        } elseif ($role === 'Student') {
            $stmt = $conn->prepare("INSERT INTO students (full_name, username, email, password, gender, department_id, department, semester) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([$name, $username, $email, $hashed_password, $gender, $department_id, $dept_name, $semester]);
        } else {
            echo "<script>alert('Invalid Role Selected.'); window.history.back();</script>";
            exit();
        }

        echo "<script>
            alert('Registration Successful! Please login.');
            window.location.href = 'login.php';
        </script>";
    } catch (PDOException $e) {
        if ($e->getCode() == 23000) { // Duplicate entry
            echo "<script>alert('Error: This email or username is already registered.'); window.history.back();</script>";
        } else {
            echo "Error: " . $e->getMessage();
        }
    }
}
?>