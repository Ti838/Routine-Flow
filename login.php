<?php
session_start();
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/core.php';

$login_error = null;
handleMaintenance();

$n1 = rand(1, 9);
$n2 = rand(1, 9);
$captcha_question = "$n1 + $n2";
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['login'])) {
    $identifier = trim($_POST['identifier']);
    $password_input = $_POST['password'];

    $found_user = null;
    $user_role = '';

    function checkTableFlexible($conn, $table, $identifier, $role_name)
    {
        $stmt = $conn->prepare("SELECT * FROM $table WHERE username = ?");
        $stmt->execute([$identifier]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);

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

    if ($found_user) {
        if (password_verify($password_input, $found_user['password'])) {
            session_regenerate_id(true);

            $_SESSION = array();

            $_SESSION['user_id'] = $found_user['id'];
            $_SESSION['user_name'] = $found_user['full_name'] ?? $found_user['name'];
            $_SESSION['user_role'] = strtolower($user_role);
            $_SESSION['user_email'] = $found_user['email'];
            $_SESSION['profile_pic'] = $found_user['profile_pic'] ?? null;
            $_SESSION['login_time'] = time();

            if ($_SESSION['user_role'] === 'student') {
                $_SESSION['student_id'] = $found_user['student_id'] ?? null;
                $_SESSION['department_id'] = $found_user['department_id'] ?? null;
                $_SESSION['semester'] = $found_user['semester'] ?? null;
            } elseif ($_SESSION['user_role'] === 'teacher') {
                $_SESSION['department_id'] = $found_user['department_id'] ?? null;
                $_SESSION['teacher_id'] = $found_user['teacher_id'] ?? null;
            }

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
        $login_error = "No account found with this username/email.";
    }

}

$departments = [];
try {
    $dept_stmt = $conn->query("SELECT id, name FROM departments ORDER BY name ASC LIMIT 5");
    $departments = $dept_stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
}

$_SESSION['captcha_answer'] = $n1 + $n2;
include 'login.html';
?>
