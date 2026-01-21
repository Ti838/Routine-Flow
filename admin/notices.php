<?php
require_once '../includes/auth_check.php';
require_once '../includes/layout.php';
require_once '../includes/db.php';

checkAuth('admin');

$name = $_SESSION['user_name'];
$role = $_SESSION['user_role'];

// Handle Deletion
if (isset($_GET['delete'])) {
    $stmt = $conn->prepare("DELETE FROM notices WHERE id = ?");
    if ($stmt->execute([$_GET['delete']])) {
        header("Location: notices.php?success=Deleted successfully");
        exit;
    }
}

// Handle Creation
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['title'])) {
    $title = $_POST['title'];
    $content = $_POST['content'];

    if (!empty($title) && !empty($content)) {
        $stmt = $conn->prepare("INSERT INTO notices (title, content) VALUES (?, ?)");
        if ($stmt->execute([$title, $content])) {
            // Log Activity
            $logStmt = $conn->prepare("INSERT INTO activity_log (user_id, user_role, user_name, action_type, action_title, action_description, icon_class, badge_type) VALUES (?, 'admin', ?, 'bulletin', 'Notice Published', ?, 'ri-notification-badge-line', 'info')");
            $logStmt->execute([$_SESSION['user_id'], $name, "Published a new institutional bulletin: $title"]);

            header("Location: notices.php?success=Notice Published");
            exit;
        }
    }
}

// Fetch all notices
try {
    $notices = $conn->query("SELECT * FROM notices ORDER BY created_at DESC")->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $notices = [];
}

include __DIR__ . '/notices.html';

