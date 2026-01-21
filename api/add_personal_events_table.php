<?php
require_once '../includes/db.php';

try {
    $sql = "CREATE TABLE IF NOT EXISTS `personal_events` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `student_id` int(11) NOT NULL,
        `title` varchar(255) NOT NULL,
        `day_of_week` varchar(20) NOT NULL,
        `start_time` time NOT NULL,
        `end_time` time NOT NULL,
        `color` varchar(20) DEFAULT 'orange',
        `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
        PRIMARY KEY (`id`),
        FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;";

    $conn->exec($sql);
    echo "<strong>Success! Personal Events table created.</strong> You can now use the Weekly Planner.";

} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>
