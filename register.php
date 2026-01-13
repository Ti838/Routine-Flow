<?php
session_start();
require_once 'includes/db.php';
require_once 'includes/core.php';
handleMaintenance();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $role = $_POST['role']; // Student, Teacher, or Admin
    $gender = isset($_POST['gender']) ? $_POST['gender'] : null;
    $department_id = isset($_POST['department_id']) ? $_POST['department_id'] : null;

    if (empty($name) || empty($email) || empty($password) || empty($role) || empty($gender)) {
        echo "<script>alert('Please fill in all required fields.'); window.history.back();</script>";
        exit();
    }

    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    try {
        if ($role === 'Admin') {
            $stmt = $conn->prepare("INSERT INTO admins (full_name, email, password, gender) VALUES (?, ?, ?, ?)");
            $stmt->execute([$name, $email, $hashed_password, $gender]);
        } elseif ($role === 'Teacher') {
            $stmt = $conn->prepare("INSERT INTO teachers (full_name, email, password, gender, department_id) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([$name, $email, $hashed_password, $gender, $department_id]);
        } elseif ($role === 'Student') {
            $student_id = "S" . rand(2410710000, 2410719999);
            $default_sem = "1st Year, 1st Sem";
            $stmt = $conn->prepare("INSERT INTO students (student_id, full_name, email, password, gender, department_id, semester) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([$student_id, $name, $email, $hashed_password, $gender, $department_id, $default_sem]);
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
            echo "<script>alert('Error: This email is already registered.'); window.history.back();</script>";
        } else {
            echo "Error: " . $e->getMessage();
        }
    }
}
?>