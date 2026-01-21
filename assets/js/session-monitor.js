


(function() {
    
    const currentUser = {
        id: '<?php echo $_SESSION["user_id"] ?? ""; ?>',
        role: '<?php echo $_SESSION["user_role"] ?? ""; ?>',
        name: '<?php echo $_SESSION["user_name"] ?? ""; ?>'
    };

    
    if (!currentUser.id) return;

    
    setInterval(async function() {
        try {
            const response = await fetch('../api/check_session.php');
            const data = await response.json();
            
            
            if (data.user_id && data.user_id !== currentUser.id) {
                alert('⚠️ Session Changed!\n\nAnother account has been logged in. This page will reload.');
                window.location.reload();
            }
            
            
            if (!data.user_id && currentUser.id) {
                alert('⚠️ You have been logged out!\n\nThis page will redirect to login.');
                window.location.href = '../login.php';
            }
        } catch (error) {
            console.error('Session check failed:', error);
        }
    }, 5000); 
})();


