<?php
require_once '../includes/auth_check.php';
require_once '../includes/layout.php';
require_once '../includes/db.php';

checkAuth();

$name = $_SESSION['user_name'];
$role = $_SESSION['user_role'];
$user_id = $_SESSION['user_id'];

// Logic: Handle Updates
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $table = ($role === 'student') ? 'students' : (($role === 'teacher') ? 'teachers' : 'admins');

    if (isset($_POST['update_profile'])) {
        $full_name = $_POST['full_name'];
        $email = $_POST['email'];
        $uploaded_file = null;

        // Student Specific Fields
        $student_id = ($role === 'student' && isset($_POST['student_id'])) ? $_POST['student_id'] : null;
        $semester = ($role === 'student' && isset($_POST['semester'])) ? $_POST['semester'] : null;

        // Teacher Specific Fields
        $teacher_id = ($role === 'teacher' && isset($_POST['teacher_id'])) ? $_POST['teacher_id'] : null;
        $department = ($role === 'teacher' && isset($_POST['department'])) ? $_POST['department'] : null;

        $gender = isset($_POST['gender']) ? $_POST['gender'] : null;

        // Handle File Upload
        if (isset($_FILES['profile_pic']) && $_FILES['profile_pic']['error'] === UPLOAD_ERR_OK) {
            $allowed_types = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
            $file_tmp = $_FILES['profile_pic']['tmp_name'];
            $file_type = mime_content_type($file_tmp);

            if (in_array($file_type, $allowed_types)) {
                $ext = pathinfo($_FILES['profile_pic']['name'], PATHINFO_EXTENSION);
                $filename = "user_" . $user_id . "_" . time() . "." . $ext;
                $destination = "../assets/uploads/profiles/" . $filename;

                if (move_uploaded_file($file_tmp, $destination)) {
                    $uploaded_file = $filename;
                }
            }
        }

        try {
            if ($role === 'student') {
                $sql = "UPDATE students SET full_name = ?, email = ?, gender = ?, student_id = ?, semester = ?";
                $params = [$full_name, $email, $gender, $student_id, $semester];
            } elseif ($role === 'teacher') {
                $sql = "UPDATE teachers SET full_name = ?, email = ?, gender = ?, teacher_id = ?, department = ?";
                $params = [$full_name, $email, $gender, $teacher_id, $department];
            } else {
                $sql = "UPDATE $table SET full_name = ?, email = ?, gender = ?";
                $params = [$full_name, $email, $gender];
            }

            if ($uploaded_file) {
                $sql .= ", profile_pic = ?";
                $params[] = $uploaded_file;
            }

            $sql .= " WHERE id = ?";
            $params[] = $user_id;

            $stmt = $conn->prepare($sql);
            $result = $stmt->execute($params);

            if ($result) {
                $_SESSION['user_name'] = $full_name;
                if ($uploaded_file) {
                    $_SESSION['profile_pic'] = $uploaded_file;
                }
                // Update specific session vars
                if ($role === 'student') {
                    $_SESSION['student_id'] = $student_id;
                    $_SESSION['semester'] = $semester;
                } elseif ($role === 'teacher') {
                    $_SESSION['teacher_id'] = $teacher_id;
                    $_SESSION['department'] = $department; // careful if department name vs id logic changes
                }

                $success_msg = "Profile updated successfully!";
                $name = $full_name;
            }
        } catch (PDOException $e) {
            $error_msg = "Update failed: " . $e->getMessage();
        }
    }

    if (isset($_POST['update_password'])) {
        $new_pass = password_hash($_POST['password'], PASSWORD_DEFAULT);
        try {
            $stmt = $conn->prepare("UPDATE $table SET password = ? WHERE id = ?");
            if ($stmt->execute([$new_pass, $user_id])) {
                $success_msg = "Password updated securely!";
            }
        } catch (PDOException $e) {
            $error_msg = "Password update failed.";
        }
    }
}

// Logic: Fetch user data & Calculate Completion
try {
    $table = ($role === 'student') ? 'students' : (($role === 'teacher') ? 'teachers' : 'admins');
    $stmt = $conn->prepare("SELECT * FROM $table WHERE id = ?");
    $stmt->execute([$user_id]);
    $user_data = $stmt->fetch(PDO::FETCH_ASSOC);

    // Calculate Completion
    $filled = 0;
    $total = 0;

    // Common Fields
    $fields = ['full_name', 'email', 'profile_pic', 'gender'];
    if ($role === 'student') {
        $fields = array_merge($fields, ['student_id', 'semester']);
    } elseif ($role === 'teacher') {
        $fields = array_merge($fields, ['teacher_id', 'department']);
    }

    $total = count($fields);
    foreach ($fields as $f) {
        if (!empty($user_data[$f]))
            $filled++;
    }

    $completion_percent = round(($filled / $total) * 100);

} catch (PDOException $e) {
    die("Database error occurred.");
}

// Presentation: Include the view
include 'views/profile.html';
?>