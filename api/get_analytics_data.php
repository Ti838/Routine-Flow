<?php
header('Content-Type: application/json');
require_once '../includes/db.php';

try {
    // 1. Room Usage
    $roomUsage = $conn->query("
        SELECT room_number as label, COUNT(*) as value 
        FROM routines 
        WHERE status = 'active' AND room_number != ''
        GROUP BY room_number 
        ORDER BY value DESC 
        LIMIT 10
    ")->fetchAll(PDO::FETCH_ASSOC);

    // 2. Teacher Workload
    $teacherWorkload = $conn->query("
        SELECT teacher_name as label, COUNT(*) as value 
        FROM routines 
        WHERE status = 'active'
        GROUP BY teacher_name 
        ORDER BY value DESC 
        LIMIT 10
    ")->fetchAll(PDO::FETCH_ASSOC);

    // 3. Department Distribution
    $deptDist = $conn->query("
        SELECT department as label, COUNT(*) as value 
        FROM routines 
        WHERE status = 'active'
        GROUP BY department 
        ORDER BY value DESC
    ")->fetchAll(PDO::FETCH_ASSOC);

    // 4. Daily Load (Classes per day)
    $dailyLoad = $conn->query("
        SELECT day_of_week as label, COUNT(*) as value 
        FROM routines 
        WHERE status = 'active'
        GROUP BY day_of_week
        ORDER BY FIELD(day_of_week, 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday')
    ")->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        'success' => true,
        'data' => [
            'roomUsage' => $roomUsage,
            'teacherWorkload' => $teacherWorkload,
            'deptDistribution' => $deptDist,
            'dailyLoad' => $dailyLoad
        ]
    ]);

} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}

