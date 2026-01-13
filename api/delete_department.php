<?php
header('Content-Type: application/json');
require_once '../includes/db.php';
require_once '../includes/auth_check.php';

checkAuth('admin');

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    try {
        $stmt = $conn->prepare("DELETE FROM departments WHERE id = ?");
        $stmt->execute([$id]);
        echo json_encode(['success' => true]);
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Cannot delete department: ' . $e->getMessage()]);
    }
}
