<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');

require_once '../includes/db.php';

// Get JSON Input
$rawData = file_get_contents("php://input");
$data = json_decode($rawData, true);

if (!isset($data['items']) || !is_array($data['items'])) {
    echo json_encode(['success' => false, 'message' => 'Invalid data format']);
    exit;
}

$items = $data['items'];
$count = 0;

try {
    $conn->beginTransaction();

    // Conflict Check Statement
    $checkStmt = $conn->prepare("
        SELECT subject_name, teacher_name, room_number, start_time, end_time 
        FROM routines 
        WHERE day_of_week = :day AND status = 'active' AND (
            (teacher_id = :teacher_id AND teacher_id IS NOT NULL) OR 
            (room_number = :room AND room_number != '')
        ) AND (
            (start_time < :end AND end_time > :start)
        )
    ");

    $insertStmt = $conn->prepare("INSERT INTO routines (subject_name, day_of_week, start_time, end_time, room_number, teacher_id, teacher_name, department, semester) VALUES (:subject, :day, :start, :end, :room, :teacher_id, :teacher_name, :dept, :sem)");

    $conflicts = [];
    $processedItems = [];

    foreach ($items as $index => $item) {
        $teacherId = intval($item['teacher_id'] ?? 0);
        $teacherName = $item['teacher_name'] ?? 'Unknown';
        $itemStart = $item['start'];
        $itemEnd = $item['end'];
        $itemDay = $item['day'];
        $itemRoom = $item['room'];

        // 1. Check against Database
        $checkStmt->execute([
            ':day' => $itemDay,
            ':teacher_id' => $teacherId,
            ':room' => $itemRoom,
            ':start' => $itemStart,
            ':end' => $itemEnd
        ]);

        while ($conflict = $checkStmt->fetch(PDO::FETCH_ASSOC)) {
            $type = ($conflict['room_number'] == $itemRoom) ? "Room $itemRoom" : "Teacher $teacherName";
            $conflicts[] = [
                'index' => $index,
                'subject' => $item['subject'],
                'message' => "Conflict with existing class: {$conflict['subject_name']} ($type) at {$conflict['start_time']}-{$conflict['end_time']}"
            ];
        }

        // 2. Check against internal batch items
        foreach ($processedItems as $p) {
            if ($p['day'] === $itemDay && ($p['start'] < $itemEnd && $p['end'] > $itemStart)) {
                if ($p['room'] === $itemRoom) {
                    $conflicts[] = [
                        'index' => $index,
                        'subject' => $item['subject'],
                        'message' => "Internal conflict: Room $itemRoom is already used by '{$p['subject']}' in this batch."
                    ];
                }
                if ($p['teacher_id'] === $teacherId && $teacherId !== 0) {
                    $conflicts[] = [
                        'index' => $index,
                        'subject' => $item['subject'],
                        'message' => "Internal conflict: Teacher $teacherName is already assigned to '{$p['subject']}' in this batch."
                    ];
                }
            }
        }

        if (empty($conflicts)) {
            $insertStmt->execute([
                ':subject' => $item['subject'],
                ':day' => $itemDay,
                ':start' => $itemStart,
                ':end' => $itemEnd,
                ':room' => $itemRoom,
                ':teacher_id' => $teacherId ?: null,
                ':teacher_name' => $teacherName,
                ':dept' => $item['department'],
                ':sem' => $item['semester']
            ]);
            $count++;
            $processedItems[] = [
                'day' => $itemDay,
                'start' => $itemStart,
                'end' => $itemEnd,
                'room' => $itemRoom,
                'teacher_id' => $teacherId,
                'subject' => $item['subject']
            ];
        }
    }

    if (!empty($conflicts)) {
        $conn->rollBack();
        echo json_encode([
            'success' => false,
            'message' => 'Scheduling conflicts detected',
            'conflicts' => $conflicts
        ]);
    } else {
        $conn->commit();

        // Log Activity
        session_start();
        if (isset($_SESSION['user_id'])) {
            $logStmt = $conn->prepare("INSERT INTO activity_log (user_id, user_role, user_name, action_type, action_title, action_description, icon_class, badge_type) VALUES (?, ?, ?, 'routine', 'Routine Published', ?, 'ri-calendar-event-line', 'success')");
            $logStmt->execute([$_SESSION['user_id'], $_SESSION['user_role'], $_SESSION['user_name'], "Published $count routine entries to " . ($items[0]['department'] ?? 'Department')]);
        }

        echo json_encode([
            'success' => true,
            'message' => "Successfully added $count routine entries",
            'total_processed' => count($items)
        ]);
    }

} catch (PDOException $e) {
    if ($conn->inTransaction()) {
        $conn->rollBack();
    }
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}
?>