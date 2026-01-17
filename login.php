<?php
session_start();
require_once 'includes/db.php';
require_once 'includes/core.php';

// Global Maintenance Check
// Global Maintenance Check
$login_error = null;
handleMaintenance();

// CAPTCHA: Generate New Question (Always run on page load/render)
$n1 = rand(1, 9);
$n2 = rand(1, 9);
$captcha_question = "$n1 + $n2";
// Store *correct* answer for the NEXT request
// We only update the session if we are NOT verifying a request, OR after verification.
// Actually, for simplicity, let's generate a NEW one for the view every time.
// But we must check the OLD one from the POST request first.

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['login'])) {

    // 1. Verify Captcha First
    if (!isset($_POST['captcha_input']) || intval($_POST['captcha_input']) !== $_SESSION['captcha_answer']) {
        $login_error = "Incorrect Math Answer. Please try again.";
        // Don't process login further
    } else {
        // Captcha correct, proceed with Login Logic
        $identifier = trim($_POST['identifier']); // Can be username or email
        $password_input = $_POST['password'];

        $found_user = null;
        $user_role = '';

        // Helper function to check credentials by username or email
        function checkTableFlexible($conn, $table, $identifier, $role_name)
        {
            // Try username first
            $stmt = $conn->prepare("SELECT * FROM $table WHERE username = ?");
            $stmt->execute([$identifier]);
            $data = $stmt->fetch(PDO::FETCH_ASSOC);

            // If not found by username, try email
            if (!$data) {
                $stmt = $conn->prepare("SELECT * FROM $table WHERE email = ?");
                $stmt->execute([$identifier]);
                $data = $stmt->fetch(PDO::FETCH_ASSOC);
            }

            if ($data) {
                return ['data' => $data, 'role' => $role_name];
            }
            return null;
        }

        // Check Tables Sequentially: Admins -> Teachers -> Students
        $admin = checkTableFlexible($conn, 'admins', $identifier, 'Admin');
        if ($admin) {
            $found_user = $admin['data'];
            $user_role = 'Admin';
        } else {
            $teacher = checkTableFlexible($conn, 'teachers', $identifier, 'Teacher');
            if ($teacher) {
                $found_user = $teacher['data'];
                $user_role = 'Teacher';
            } else {
                $student = checkTableFlexible($conn, 'students', $identifier, 'Student');
                if ($student) {
                    $found_user = $student['data'];
                    $user_role = 'Student';
                }
            }
        }

        // Authenticate
        if ($found_user) {
            if (password_verify($password_input, $found_user['password'])) {
                // Password is correct - Create new session
                // Regenerate session ID to prevent session fixation
                session_regenerate_id(true);

                // Clear any existing session data
                $_SESSION = array();

                // Set new session variables
                $_SESSION['user_id'] = $found_user['id'];
                $_SESSION['user_name'] = $found_user['full_name'] ?? $found_user['name'];
                $_SESSION['user_role'] = strtolower($user_role);
                $_SESSION['user_email'] = $found_user['email'];
                $_SESSION['profile_pic'] = $found_user['profile_pic'] ?? null;
                $_SESSION['login_time'] = time(); // Added login time

                // Role-specific session data
                if ($_SESSION['user_role'] === 'student') {
                    $_SESSION['student_id'] = $found_user['student_id'] ?? null;
                    $_SESSION['department_id'] = $found_user['department_id'] ?? null;
                    $_SESSION['semester'] = $found_user['semester'] ?? null;
                } elseif ($_SESSION['user_role'] === 'teacher') { // Added teacher specific session data
                    $_SESSION['department_id'] = $found_user['department_id'] ?? null;
                    $_SESSION['teacher_id'] = $found_user['teacher_id'] ?? null;
                }

                // Redirect based on Role
                if ($_SESSION['user_role'] === 'admin') {
                    header("Location: ./admin/dashboard.php");
                } elseif ($_SESSION['user_role'] === 'teacher') {
                    header("Location: teacher/dashboard.php");
                } else { // student
                    header("Location: student/dashboard.php");
                }
                exit();
            } else {
                $login_error = "Invalid Password.";
            }
        } else {
            $login_error = "No account found with this username/email.";
        }
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

// Store the answer for the current generated question
$_SESSION['captcha_answer'] = $n1 + $n2;

// Presentation: Include the view
include 'views/login.html';
?>