<?php
header('Content-Type: application/json');
require_once '../includes/db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
    exit;
}

$email = filter_var(trim($_POST['email'] ?? ''), FILTER_VALIDATE_EMAIL);
$new_password = $_POST['new_password'] ?? '';
$recaptcha_response = $_POST['g-recaptcha-response'] ?? '';

if (!$email) {
    echo json_encode(['success' => false, 'message' => 'Please provide a valid email address']);
    exit;
}

if (strlen($new_password) < 6) {
    echo json_encode(['success' => false, 'message' => 'Password must be at least 6 characters long']);
    exit;
}

if (empty($recaptcha_response)) {
    echo json_encode(['success' => false, 'message' => 'Please complete the CAPTCHA']);
    exit;
}

// 1. Verify reCAPTCHA
$secret = '6LeIxAcTAAAAAGG-vFI1TnRWxMZNFuojJ4WifJWe'; // Official Google Test Secret Key
$verify_url = 'https://www.google.com/recaptcha/api/siteverify';

// Use curl for better reliability than file_get_contents on some servers
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "https://www.google.com/recaptcha/api/siteverify");
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query(['secret' => $secret, 'response' => $recaptcha_response]));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($ch);
curl_close($ch);

$response_data = json_decode($response);

if (!$response_data || !$response_data->success) {
    echo json_encode(['success' => false, 'message' => 'CAPTCHA verification failed. Please try again.']);
    exit;
}

// 2. Process Password Reset
$hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
$found = false;

try {
    // We check all tables. Even if an email exists in multiple (which shouldn't happen), 
    // we reset them all to be "perfectly" sure the user can log in.

    $tables = ['admins', 'teachers', 'students'];
    $total_updated = 0;

    foreach ($tables as $table) {
        $stmt = $conn->prepare("UPDATE $table SET password = ? WHERE email = ?");
        $stmt->execute([$hashed_password, $email]);
        if ($stmt->rowCount() > 0) {
            $found = true;
            $total_updated++;
        } else {
            // Check if user exists but password is the same (rowCount would be 0)
            $stmt = $conn->prepare("SELECT id FROM $table WHERE email = ?");
            $stmt->execute([$email]);
            if ($stmt->fetch()) {
                $found = true;
            }
        }
    }

    if ($found) {
        echo json_encode(['success' => true, 'message' => 'Password successfully reset! You can now log in.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'This email is not registered in our system.']);
    }

} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Security Error: Verification failed.']);
}
?>
