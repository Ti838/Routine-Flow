<?php
// Simple configuration for database connection
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

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $gender = isset($_POST['gender']) ? $_POST['gender'] : 'Other'; // Default if missing
    $role = $_POST['role']; // Student, Teacher, or Admin
    $department = isset($_POST['department']) ? $_POST['department'] : '';

    // Basic Validation
    if (empty($name) || empty($email) || empty($password) || empty($role)) {
        echo "<script>alert('Please fill in all required fields.'); window.history.back();</script>";
        exit();
    }

    // Hash the password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    $stmt = null;

    // Logic to insert into separate tables based on Role
    if ($role === 'Admin') {
        // Admin: name, email, password, profile_image
        // Note: Admin table doesn't have gender or department
        $stmt = $conn->prepare("INSERT INTO admins (name, email, password) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $name, $email, $hashed_password);

    } elseif ($role === 'Teacher') {
        // Teacher: name, email, password, gender, department, designation (default), profile_image
        if (empty($department)) {
            echo "<script>alert('Teachers must select a department.'); window.history.back();</script>";
            exit();
        }
        $default_desig = "Lecturer";
        $stmt = $conn->prepare("INSERT INTO teachers (name, email, password, gender, department, designation) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssss", $name, $email, $hashed_password, $gender, $department, $default_desig);

    } elseif ($role === 'Student') {
        // Student: student_id (auto-gen?), name, email, password, gender, department, semester (default), profile_image
        if (empty($department)) {
            echo "<script>alert('Students must select a department.'); window.history.back();</script>";
            exit();
        }
        // Generate a random ID for demo
        $student_id = "S" . rand(1000, 9999);
        $default_sem = "1st Semester";

        $stmt = $conn->prepare("INSERT INTO students (student_id, name, email, password, gender, department, semester) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssssss", $student_id, $name, $email, $hashed_password, $gender, $department, $default_sem);
    } else {
        echo "<script>alert('Invalid Role Selected.'); window.history.back();</script>";
        exit();
    }

    // Execute
    if ($stmt && $stmt->execute()) {
        echo "<script>
            alert('Registration Successful as " . $role . "!');
            window.location.href = 'login.html';
        </script>";
    } else {
        // Handle Duplicate Email Error (MySQL Error 1062)
        if ($conn->errno == 1062) {
            echo "<script>alert('Error: This email is already registered.'); window.history.back();</script>";
        } else {
            echo "Error: " . $stmt->error;
        }
    }

    if ($stmt) {
        $stmt->close();
    }
}

$conn->close();
?>