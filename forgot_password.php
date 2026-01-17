<?php
session_start();
require_once 'includes/db.php';
require_once 'includes/core.php';
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
    <!-- reCAPTCHA API -->
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
</head>

<body class="bg-gray-50 dark:bg-[#0b1121] flex items-center justify-center min-h-screen p-4">

    <div
        class="w-full max-w-md bg-white dark:bg-[#16213e] rounded-2xl shadow-xl overflow-hidden border border-gray-200 dark:border-white/10 p-8">

        <div class="text-center mb-8">
            <h2 class="text-3xl font-extrabold text-gray-900 dark:text-white mb-2">Reset Password</h2>
            <p class="text-gray-500 dark:text-gray-400 text-sm">Enter your email and new password.</p>
        </div>

        <div id="statusMessage" class="hidden mb-6 p-4 rounded-lg text-sm font-semibold flex items-center">
            <i id="statusIcon" class="text-lg mr-2"></i>
            <span id="statusText"></span>
        </div>

        <form id="resetForm">
            <div class="mb-5 relative">
                <input type="email" name="email" id="email" placeholder=" "
                    class="peer w-full bg-transparent border-b-2 border-gray-300 dark:border-gray-600 px-1 py-2 text-gray-900 dark:text-white focus:outline-none focus:border-blue-600 transition-colors placeholder-transparent"
                    required>
                <label
                    class="absolute left-1 -top-3.5 text-xs text-blue-600 transition-all peer-placeholder-shown:text-base peer-placeholder-shown:text-gray-500 peer-placeholder-shown:top-2 peer-focus:-top-3.5 peer-focus:text-xs peer-focus:text-blue-600">Email
                    Address</label>
            </div>

            <div class="mb-5 relative">
                <input type="password" name="new_password" id="new_password" placeholder=" "
                    class="peer w-full bg-transparent border-b-2 border-gray-300 dark:border-gray-600 px-1 py-2 text-gray-900 dark:text-white focus:outline-none focus:border-blue-600 transition-colors placeholder-transparent"
                    required minlength="6">
                <label
                    class="absolute left-1 -top-3.5 text-xs text-blue-600 transition-all peer-placeholder-shown:text-base peer-placeholder-shown:text-gray-500 peer-placeholder-shown:top-2 peer-focus:-top-3.5 peer-focus:text-xs peer-focus:text-blue-600">New
                    Password</label>
            </div>

            <div class="mb-8 relative">
                <input type="password" name="confirm_password" id="confirm_password" placeholder=" "
                    class="peer w-full bg-transparent border-b-2 border-gray-300 dark:border-gray-600 px-1 py-2 text-gray-900 dark:text-white focus:outline-none focus:border-blue-600 transition-colors placeholder-transparent"
                    required>
                <label
                    class="absolute left-1 -top-3.5 text-xs text-blue-600 transition-all peer-placeholder-shown:text-base peer-placeholder-shown:text-gray-500 peer-placeholder-shown:top-2 peer-focus:-top-3.5 peer-focus:text-xs peer-focus:text-blue-600">Confirm
                    Password</label>
            </div>

            <!-- reCAPTCHA Widget -->
            <div class="mb-6 flex justify-center">
                <div class="g-recaptcha" data-sitekey="6LeIxAcTAAAAAJcZVRqyHh71UMIEGNQ_MXjiZKhI" data-theme="light">
                </div>
            </div>

            <button type="submit" id="submitBtn"
                class="w-full py-3 bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 text-white font-bold rounded-xl shadow-lg transform transition hover:-translate-y-0.5 disabled:opacity-50 disabled:cursor-not-allowed">
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

    <script>
        document.getElementById('resetForm').addEventListener('submit', async function (e) {
            e.preventDefault();

            const btn = document.getElementById('submitBtn');
            const statusDiv = document.getElementById('statusMessage');
            const statusIcon = document.getElementById('statusIcon');
            const statusText = document.getElementById('statusText');

            // Basic UI State
            btn.disabled = true;
            btn.textContent = 'Processing...';
            statusDiv.classList.add('hidden');

            const formData = new FormData(this);
            const recaptchaResponse = grecaptcha.getResponse();
            const password = document.getElementById('new_password').value;
            const confirm = document.getElementById('confirm_password').value;

            if (password !== confirm) {
                showStatus('Passwords do not match', 'error');
                btn.disabled = false;
                btn.textContent = 'Update Password';
                return;
            }

            if (!recaptchaResponse) {
                showStatus('Please complete the CAPTCHA', 'error');
                btn.disabled = false;
                btn.textContent = 'Update Password';
                return;
            }

            try {
                const response = await fetch('api/reset_password.php', {
                    method: 'POST',
                    body: formData
                });

                const data = await response.json();

                if (data.success) {
                    showStatus(data.message, 'success');
                    this.reset();
                    grecaptcha.reset();
                    setTimeout(() => {
                        window.location.href = 'login.php';
                    }, 2000);
                } else {
                    showStatus(data.message, 'error');
                    grecaptcha.reset();
                }
            } catch (error) {
                showStatus('Network error. Please try again.', 'error');
                grecaptcha.reset();
            } finally {
                btn.disabled = false;
                btn.textContent = 'Update Password';
            }
        });

        function showStatus(message, type) {
            const statusDiv = document.getElementById('statusMessage');
            const statusIcon = document.getElementById('statusIcon');
            const statusText = document.getElementById('statusText');

            statusDiv.classList.remove('hidden', 'bg-green-100', 'text-green-700', 'border-green-200', 'bg-red-100', 'text-red-700', 'border-red-200');

            if (type === 'success') {
                statusDiv.classList.add('bg-green-100', 'text-green-700', 'border', 'border-green-200');
                statusIcon.className = 'ri-checkbox-circle-line text-lg mr-2';
            } else {
                statusDiv.classList.add('bg-red-100', 'text-red-700', 'border', 'border-red-200');
                statusIcon.className = 'ri-error-warning-line text-lg mr-2';
            }

            statusText.textContent = message;
        }

        // Adjust reCAPTCHA theme based on page theme
        window.addEventListener('load', () => {
            const isDark = document.documentElement.classList.contains('dark');
            const widget = document.querySelector('.g-recaptcha');
            if (widget && isDark) {
                widget.setAttribute('data-theme', 'dark');
            }
        });
    </script>

</body>

</html>