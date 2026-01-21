<?php
require_once '../includes/db.php';

try {
    // Add teacher_id column if it doesn't exist
    $conn->exec("ALTER TABLE teachers ADD COLUMN IF NOT EXISTS teacher_id VARCHAR(50) AFTER profile_pic");

    // Add department column if it doesn't exist (it should, but just in case)
    $conn->exec("ALTER TABLE teachers ADD COLUMN IF NOT EXISTS department VARCHAR(100) AFTER teacher_id");

    echo "Successfully updated teachers table with teacher_id column.";
} catch (PDOException $e) {
    echo "Error updating table: " . $e->getMessage();
}
?>
