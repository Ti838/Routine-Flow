<?php
session_start();

// Database configuration
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "routine_flow_db";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['login'])) {
    $email = $_POST['email'];
    $password_input = $_POST['password'];

    $found_user = null;
    $user_role = '';

    // Helper function to check credentials in a specific table
    function checkTable($conn, $table, $email, $role_name)
    {
        $stmt = $conn->prepare("SELECT * FROM $table WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            return ['data' => $result->fetch_assoc(), 'role' => $role_name];
        }
        return null;
    }

    // Check Tables Sequentially: Admins -> Teachers -> Students

    // 1. Check Admins
    $admin = checkTable($conn, 'admins', $email, 'Admin');
    if ($admin) {
        $found_user = $admin['data'];
        $user_role = 'Admin';
    } else {
        // 2. Check Teachers
        $teacher = checkTable($conn, 'teachers', $email, 'Teacher');
        if ($teacher) {
            $found_user = $teacher['data'];
            $user_role = 'Teacher';
        } else {
            // 3. Check Students
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
            // Login Success
            $_SESSION['user_id'] = $found_user['id'];
            $_SESSION['user_name'] = $found_user['name'];
            $_SESSION['user_role'] = $user_role;
            $_SESSION['user_email'] = $found_user['email'];

            // Redirect based on Role
            if ($user_role === 'Admin') {
                header("Location: admin/dashboard.html");
            } elseif ($user_role === 'Teacher') {
                header("Location: teacher/dashboard.html");
            } else {
                header("Location: student/dashboard.html");
            }
            exit();

        } else {
            echo "<script>alert('Invalid Password.'); window.history.back();</script>";
        }
    } else {
        echo "<script>alert('No account found with this email.'); window.history.back();</script>";
    }
}

$conn->close();
?>