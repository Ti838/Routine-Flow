<?php
require_once '../includes/auth_check.php';
require_once '../includes/layout.php';
require_once '../includes/db.php';

checkAuth('admin');

$name = $_SESSION['user_name'];
$role = $_SESSION['user_role'];

$user_id = $_GET['id'] ?? null;
$user_role = $_GET['role'] ?? 'student';

if (!$user_id) {
    header("Location: users.php");
    exit();
}

// Logic: Fetch user details
try {
    $table = ($user_role === 'student') ? 'students' : 'teachers';
    $stmt = $conn->prepare("SELECT u.*, d.name as dept_name FROM $table u LEFT JOIN departments d ON u.department_id = d.id WHERE u.id = ?");
    $stmt->execute([$user_id]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("User not found.");
}

// Logic: Handle Updates
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $stmt = $conn->prepare("UPDATE $table SET full_name = ?, email = ?, department_id = ? WHERE id = ?");
        $stmt->execute([$_POST['full_name'], $_POST['email'], $_POST['department_id'], $user_id]);
        header("Location: users.php?msg=updated");
        exit();
    } catch (PDOException $e) {
        $error_msg = "Error updating user: " . $e->getMessage();
    }
}

$departments = $conn->query("SELECT * FROM departments ORDER BY name ASC")->fetchAll(PDO::FETCH_ASSOC);

// Presentation: Include the view
include 'views/edit-user.html';
?>