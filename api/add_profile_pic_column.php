<?php
require_once '../includes/db.php';

try {
    $tables = ['admins', 'teachers', 'students'];
    $cols_added = 0;

    foreach ($tables as $table) {
        // Check if column exists
        $stmt = $conn->prepare("SHOW COLUMNS FROM $table LIKE 'profile_pic'");
        $stmt->execute();

        if ($stmt->rowCount() == 0) {
            $sql = "ALTER TABLE $table ADD COLUMN profile_pic VARCHAR(255) DEFAULT NULL";
            $conn->exec($sql);
            echo "Added profile_pic to $table.<br>";
            $cols_added++;
        } else {
            echo "profile_pic already exists in $table.<br>";
        }
    }

    if ($cols_added > 0) {
        echo "<strong>Success! All tables updated. You can now close this page.</strong>";
    } else {
        echo "<strong>Database was already up to date.</strong>";
    }

} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>
