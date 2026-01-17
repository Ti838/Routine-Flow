// Multi-Tab Session Manager
// Detects when session changes and alerts user

(function() {
    // Store current session info
    const currentUser = {
        id: '<?php echo $_SESSION["user_id"] ?? ""; ?>',
        role: '<?php echo $_SESSION["user_role"] ?? ""; ?>',
        name: '<?php echo $_SESSION["user_name"] ?? ""; ?>'
    };

    // Only run if user is logged in
    if (!currentUser.id) return;

    // Check session every 5 seconds
    setInterval(async function() {
        try {
            const response = await fetch('../api/check_session.php');
            const data = await response.json();
            
            // If session changed (different user logged in)
            if (data.user_id && data.user_id !== currentUser.id) {
                alert('⚠️ Session Changed!\n\nAnother account has been logged in. This page will reload.');
                window.location.reload();
            }
            
            // If session destroyed (logged out)
            if (!data.user_id && currentUser.id) {
                alert('⚠️ You have been logged out!\n\nThis page will redirect to login.');
                window.location.href = '../login.php';
            }
        } catch (error) {
            console.error('Session check failed:', error);
        }
    }, 5000); // Check every 5 seconds
})();
