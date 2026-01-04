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
    $gender = $_POST['gender'];
    $role = $_POST['role'];
    $department = isset($_POST['department']) ? $_POST['department'] : '';

    // Simple validation (can be improved)
    if (empty($name) || empty($email) || empty($password) || empty($role)) {
        echo "<script>alert('Please fill in all required fields.'); window.history.back();</script>";
        exit();
    }

    // specific validation for admin who doesn't need department
    if ($role != 'Admin' && empty($department)) {
         echo "<script>alert('Please select a department.'); window.history.back();</script>";
         exit();
    }

    // Hash the password for security
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Prepare SQL statement to prevent SQL injection
    $stmt = $conn->prepare("INSERT INTO users (name, email, password, gender, role, department) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssss", $name, $email, $hashed_password, $gender, $role, $department);

    // Execute and check result
    if ($stmt->execute()) {
        // Registration successful
        echo "<script>
            alert('Registration successful! You can now login.');
            window.location.href = 'login.html';
        </script>";
    } else {
        // Check for duplicate email error
        if ($conn->errno == 1062) {
             echo "<script>alert('Email already registered.'); window.history.back();</script>";
        } else {
             echo "Error: " . $stmt->error;
        }
    }

    $stmt->close();
}

$conn->close();
?>
