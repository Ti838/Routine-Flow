<?php
require_once '../includes/auth_check.php';
require_once '../includes/layout.php';
require_once '../includes/db.php';
require_once '../includes/file_handler.php';

checkAuth('admin');

$name = $_SESSION['user_name'];
$role = $_SESSION['user_role'];
$success_msg = null;
$error_msg = null;

// Logic: Handle File Upload POST
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['upload_routine'])) {
    $dept_id = $_POST['department_id'];
    $stmt = $conn->prepare("SELECT name FROM departments WHERE id = ?");
    $stmt->execute([$dept_id]);
    $dept_name = $stmt->fetchColumn();

    $upload = uploadRoutineFile($_FILES['routine_file'], $_SESSION['user_id'], $role, [
        'department' => $dept_name,
        'semester' => $_POST['semester'],
        'description' => 'System-wide routine uploaded by admin.'
    ]);

    if ($upload['success']) {
        logActivity($conn, $_SESSION['user_id'], $role, $name, 'upload', 'Routine File Uploaded', "Uploaded routine file for $dept_name ({$_POST['semester']})", 'ri-file-upload-line', 'success');
        $success_msg = "Routine file uploaded and published successfully!";
    } else {
        $error_msg = $upload['error'];
    }
}

// Logic: Handle manual entry POST
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['manual_entry'])) {
    try {
        $stmt = $conn->prepare("INSERT INTO routines (department_id, department, semester, subject_name, teacher_name, room_number, day_of_week, start_time, end_time, color_tag) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([
            $_POST['department_id'],
            $_POST['department_name'],
            $_POST['semester'],
            $_POST['subject'],
            $_POST['teacher'],
            $_POST['room'],
            $_POST['day'],
            $_POST['start'],
            $_POST['end'],
            $_POST['color_tag'] ?? 'indigo'
        ]);

        // Log Activity
        logActivity(
            $conn,
            $_SESSION['user_id'],
            $role,
            $name,
            'create',
            'Routine Published',
            "Published new class: {$_POST['subject']} for {$_POST['department_name']} ({$_POST['semester']})",
            'ri-calendar-check-line',
            'success'
        );

        // Create Public Notification (Optional but requested "notification sob thik koro")
        // Check if a similar notice exists recently to avoid spam (optional, skipping for simplicity/directness)
        $n_title = "New Schedule Update: " . $_POST['department_name'];
        $n_content = "A new class '{$_POST['subject']}' has been scheduled for {$_POST['semester']} on {$_POST['day']}s.";
        $conn->prepare("INSERT INTO notices (title, content) VALUES (?, ?)")->execute([$n_title, $n_content]);

        $success_msg = "Routine entry created & Notified successfully!";
    } catch (PDOException $e) {
        $error_msg = "Error creating routine: " . $e->getMessage();
    }
}

// Logic: Fetch departments for dropdown
$departments = $conn->query("SELECT * FROM departments ORDER BY name ASC")->fetchAll(PDO::FETCH_ASSOC);

// Presentation: Include the view
include __DIR__ . '/create-routine.html';
?>
