<?php
session_start();
require_once 'includes/db.php';
require_once 'includes/core.php';

$message = '';
$messageType = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $new_password = $_POST['new_password'];

    if (empty($email) || empty($new_password)) {
        $message = "Please fill in all fields.";
        $messageType = "error";
    } else {
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
        $found = false;

        // Check Admins
        $stmt = $conn->prepare("SELECT id FROM admins WHERE email = ?");
        $stmt->execute([$email]);
        if ($stmt->fetch()) {
            $update = $conn->prepare("UPDATE admins SET password = ? WHERE email = ?");
            $update->execute([$hashed_password, $email]);
            $found = true;
        }

        // Check Teachers
        if (!$found) {
            $stmt = $conn->prepare("SELECT id FROM teachers WHERE email = ?");
            $stmt->execute([$email]);
            if ($stmt->fetch()) {
                $update = $conn->prepare("UPDATE teachers SET password = ? WHERE email = ?");
                $update->execute([$hashed_password, $email]);
                $found = true;
            }
        }

        // Check Students
        if (!$found) {
            $stmt = $conn->prepare("SELECT id FROM students WHERE email = ?");
            $stmt->execute([$email]);
            if ($stmt->fetch()) {
                $update = $conn->prepare("UPDATE students SET password = ? WHERE email = ?");
                $update->execute([$hashed_password, $email]);
                $found = true;
            }
        }

        if ($found) {
            $message = "Password successfully reset! You can now login.";
            $messageType = "success";
        } else {
            $message = "Email not found in our records.";
            $messageType = "error";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <script>
        (function () {
            const theme = localStorage.getItem('theme') || 'light';
            if (theme === 'dark') document.documentElement.classList.add('dark');
        })();
    </script>
    <style>
        html.dark {
            background: #0B1121 !important;
        }

        html.dark body {
            background: #0B1121 !important;
        }
    </style>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password - Routine Flow</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="assets/js/tailwind-config.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>

<body class="bg-gray-50 dark:bg-[#0b1121] flex items-center justify-center min-h-screen p-4">

    <div
        class="w-full max-w-md bg-white dark:bg-[#16213e] rounded-2xl shadow-xl overflow-hidden border border-gray-200 dark:border-white/10 p-8">

        <div class="text-center mb-8">
            <h2 class="text-3xl font-extrabold text-gray-900 dark:text-white mb-2">Reset Password</h2>
            <p class="text-gray-500 dark:text-gray-400 text-sm">Enter your email and new password.</p>
        </div>

        <?php if ($message): ?>
            <div
                class="mb-6 p-4 rounded-lg <?php echo $messageType === 'success' ? 'bg-green-100 text-green-700 border border-green-200' : 'bg-red-100 text-red-700 border border-red-200'; ?> text-sm font-semibold flex items-center">
                <i
                    class="<?php echo $messageType === 'success' ? 'ri-checkbox-circle-line' : 'ri-error-warning-line'; ?> text-lg mr-2"></i>
                <?php echo $message; ?>
            </div>
        <?php endif; ?>

        <form method="POST">
            <div class="mb-5 relative">
                <input type="email" name="email" placeholder=" "
                    class="peer w-full bg-transparent border-b-2 border-gray-300 dark:border-gray-600 px-1 py-2 text-gray-900 dark:text-white focus:outline-none focus:border-blue-600 transition-colors placeholder-transparent"
                    required>
                <label
                    class="absolute left-1 -top-3.5 text-xs text-blue-600 transition-all peer-placeholder-shown:text-base peer-placeholder-shown:text-gray-500 peer-placeholder-shown:top-2 peer-focus:-top-3.5 peer-focus:text-xs peer-focus:text-blue-600">Email
                    Address</label>
            </div>

            <div class="mb-8 relative">
                <input type="password" name="new_password" placeholder=" "
                    class="peer w-full bg-transparent border-b-2 border-gray-300 dark:border-gray-600 px-1 py-2 text-gray-900 dark:text-white focus:outline-none focus:border-blue-600 transition-colors placeholder-transparent"
                    required>
                <label
                    class="absolute left-1 -top-3.5 text-xs text-blue-600 transition-all peer-placeholder-shown:text-base peer-placeholder-shown:text-gray-500 peer-placeholder-shown:top-2 peer-focus:-top-3.5 peer-focus:text-xs peer-focus:text-blue-600">New
                    Password</label>
            </div>

            <button type="submit"
                class="w-full py-3 bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 text-white font-bold rounded-xl shadow-lg transform transition hover:-translate-y-0.5">
                Update Password
            </button>
        </form>

        <div class="mt-6 text-center">
            <a href="login.php"
                class="text-sm font-bold text-gray-500 hover:text-blue-600 transition-colors flex items-center justify-center gap-1">
                <i class="ri-arrow-left-line"></i> Back to Login
            </a>
        </div>
    </div>

</body>

</html>