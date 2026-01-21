<?php
/**
 * REGISTRATION PAGE / USER CREATION
 * Handles new user registration for admin, teacher, and student roles
 * Includes CAPTCHA verification and duplicate email checking
 */

session_start();

// Import database and core utilities
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/core.php';

// Check if system is in maintenance mode
handleMaintenance();

// Process registration form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get CAPTCHA numbers from form (sent as hidden fields)
    $c_n1 = isset($_POST['captcha_n1']) ? intval($_POST['captcha_n1']) : 0;
    $c_n2 = isset($_POST['captcha_n2']) ? intval($_POST['captcha_n2']) : 0;
    $c_input = isset($_POST['captcha_input']) ? intval($_POST['captcha_input']) : -1;

    // Verify CAPTCHA answer
    if ($c_input !== ($c_n1 + $c_n2)) {
        echo "<script>alert('Incorrect Math Answer. Registration Failed.'); window.history.back();</script>";
        exit();
    }

    // Get and sanitize form inputs
    $name = $_POST['name'];
    $username = trim($_POST['username']);
    $email = $_POST['email'];
    $password = $_POST['password'];
    $role = $_POST['role'];
    $gender = isset($_POST['gender']) ? $_POST['gender'] : null;
    $department_id = isset($_POST['department_id']) ? $_POST['department_id'] : null;

    // Validate all required fields are filled
    if (empty($name) || empty($username) || empty($email) || empty($password) || empty($role) || empty($gender)) {
        echo "<script>alert('Please fill in all required fields.'); window.history.back();</script>";
        exit();
    }

    // Hash password using secure algorithm
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    
    // Get semester for students (optional)
    $semester = isset($_POST['semester']) ? $_POST['semester'] : null;

    try {
        // Get department name for storage
        $dept_name = null;
        if ($department_id) {
            $d_stmt = $conn->prepare("SELECT name FROM departments WHERE id = ?");
            $d_stmt->execute([$department_id]);
            $dept_name = $d_stmt->fetchColumn();
        }

        // Insert user based on selected role
        if ($role === 'Admin') {
            // Register as admin
            $stmt = $conn->prepare("INSERT INTO admins (full_name, username, email, password, gender) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([$name, $username, $email, $hashed_password, $gender]);
        } elseif ($role === 'Teacher') {
            // Register as teacher with department
            $stmt = $conn->prepare("INSERT INTO teachers (full_name, username, email, password, gender, department_id, department) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([$name, $username, $email, $hashed_password, $gender, $department_id, $dept_name]);
        } elseif ($role === 'Student') {
            // Register as student with department and semester
            $stmt = $conn->prepare("INSERT INTO students (full_name, username, email, password, gender, department_id, department, semester) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([$name, $username, $email, $hashed_password, $gender, $department_id, $dept_name, $semester]);
        } else {
            // Invalid role selected
            echo "<script>alert('Invalid Role Selected.'); window.history.back();</script>";
            exit();
        }

        // Registration successful - redirect to login
        echo "<script>
            alert('Registration Successful! Please login.');
            window.location.href = 'login.php';
        </script>";
    } catch (PDOException $e) {
        // Handle specific database errors
        if ($e->getCode() == 23000) {
            // Duplicate entry error (unique constraint violated)
            echo "<script>alert('Error: This email or username is already registered.'); window.history.back();</script>";
        } else {
            // Other database errors
            echo "Error: " . $e->getMessage();
        }
    }
}
?>
