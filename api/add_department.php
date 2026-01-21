<?php
header('Content-Type: application/json');
require_once '../includes/db.php';
require_once '../includes/auth_check.php';

checkAuth('admin');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'] ?? '';
    $code = strtoupper($_POST['code'] ?? '');

    if (empty($name) || empty($code)) {
        echo json_encode(['success' => false, 'message' => 'All fields are required']);
        exit;
    }

    $logo_path = null;
    if (isset($_FILES['logo']) && $_FILES['logo']['error'] === 0) {
        $upload_dir = '../assets/img/departments/';
        if (!is_dir($upload_dir))
            mkdir($upload_dir, 0777, true);

        $ext = pathinfo($_FILES['logo']['name'], PATHINFO_EXTENSION);
        $logo_name = $code . '_' . time() . '.' . $ext;
        if (move_uploaded_file($_FILES['logo']['tmp_name'], $upload_dir . $logo_name)) {
            $logo_path = 'assets/img/departments/' . $logo_name;
        }
    }

    try {
        $stmt = $conn->prepare("INSERT INTO departments (name, code, logo_path) VALUES (?, ?, ?)");
        $stmt->execute([$name, $code, $logo_path]);
        echo json_encode(['success' => true]);
    } catch (PDOException $e) {
        if ($e->getCode() == 23000) {
            echo json_encode(['success' => false, 'message' => 'Department or code already exists']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
        }
    }
}

