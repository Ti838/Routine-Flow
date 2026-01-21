<?php
/**
 * LOGIN PAGE / AUTHENTICATION HANDLER
 * Handles user authentication for admin, teacher, and student roles
 * Includes CAPTCHA verification for security
 */

session_start();

// Import database and core utilities
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/core.php';

// Initialize error message
$login_error = null;

// Check if system is in maintenance mode
handleMaintenance();

// Generate CAPTCHA numbers for security
$n1 = rand(1, 9);
$n2 = rand(1, 9);
$captcha_question = "$n1 + $n2";

// Process login form submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['login'])) {
    // Get and sanitize user input
    $identifier = trim($_POST['identifier']);  // Can be username or email
    $password_input = $_POST['password'];

    $found_user = null;
    $user_role = '';

    /**
     * Helper function to search user in a specific table
     * Searches by username first, then by email if not found
     * 
     * @param PDO $conn - Database connection
     * @param string $table - Table name to search in (admins, teachers, students)
     * @param string $identifier - Username or email to search for
     * @param string $role_name - Role name for identification
     * @return array|null - User data with role if found, null otherwise
     */
    function checkTableFlexible($conn, $table, $identifier, $role_name)
    {
        // First try to find user by username
        $stmt = $conn->prepare("SELECT * FROM $table WHERE username = ?");
        $stmt->execute([$identifier]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        // If not found by username, try email
        if (!$data) {
            $stmt = $conn->prepare("SELECT * FROM $table WHERE email = ?");
            $stmt->execute([$identifier]);
            $data = $stmt->fetch(PDO::FETCH_ASSOC);
        }

        // Return user data with role if found
        if ($data) {
            return ['data' => $data, 'role' => $role_name];
        }
        return null;
    }

    // Check for admin user first (highest priority)
    $admin = checkTableFlexible($conn, 'admins', $identifier, 'Admin');
    if ($admin) {
        $found_user = $admin['data'];
        $user_role = 'Admin';
    } else {
        // Check for teacher user
        $teacher = checkTableFlexible($conn, 'teachers', $identifier, 'Teacher');
        if ($teacher) {
            $found_user = $teacher['data'];
            $user_role = 'Teacher';
        } else {
            // Check for student user (lowest priority)
            $student = checkTableFlexible($conn, 'students', $identifier, 'Student');
            if ($student) {
                $found_user = $student['data'];
                $user_role = 'Student';
            }
        }
    }

    // Verify user exists and password matches
    if ($found_user) {
        if (password_verify($password_input, $found_user['password'])) {
            // Password correct - create new secure session
            session_regenerate_id(true);  // Regenerate session ID to prevent session fixation attacks

            $_SESSION = array();  // Clear old session data

            // Store common user information in session
            $_SESSION['user_id'] = $found_user['id'];
            $_SESSION['user_name'] = $found_user['full_name'] ?? $found_user['name'];
            $_SESSION['user_role'] = strtolower($user_role);
            $_SESSION['user_email'] = $found_user['email'];
            $_SESSION['profile_pic'] = $found_user['profile_pic'] ?? null;
            $_SESSION['login_time'] = time();

            // Store role-specific session data
            if ($_SESSION['user_role'] === 'student') {
                // Store student-specific information
                $_SESSION['student_id'] = $found_user['student_id'] ?? null;
                $_SESSION['department_id'] = $found_user['department_id'] ?? null;
                $_SESSION['semester'] = $found_user['semester'] ?? null;
            } elseif ($_SESSION['user_role'] === 'teacher') {
                // Store teacher-specific information
                $_SESSION['department_id'] = $found_user['department_id'] ?? null;
                $_SESSION['teacher_id'] = $found_user['teacher_id'] ?? null;
            }

            // Redirect to role-specific dashboard
            if ($_SESSION['user_role'] === 'admin') {
                header("Location: ./admin/dashboard.php");
            } elseif ($_SESSION['user_role'] === 'teacher') {
                header("Location: teacher/dashboard.php");
            } else {
                header("Location: student/dashboard.php");
            }
            exit();
        } else {
            // Password incorrect
            $login_error = "Invalid Password.";
        }
    } else {
        // User account not found
        $login_error = "No account found with this username/email.";
    }
}

// Fetch list of departments for display (optional)
$departments = [];
try {
    $dept_stmt = $conn->query("SELECT id, name FROM departments ORDER BY name ASC LIMIT 5");
    $departments = $dept_stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    // Silently fail if departments can't be fetched
}

// Store CAPTCHA answer in session for verification
$_SESSION['captcha_answer'] = $n1 + $n2;

// Include login HTML template
include 'login.html';
?>
