<?php
/**
 * CREATE ROUTINE API ENDPOINT
 * Handles creation of new routine entries with conflict detection
 * Accepts JSON POST data containing routine items
 * Validates against time/room/teacher conflicts before committing
 */

// Set response headers for JSON API
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');

require_once '../includes/db.php';

// Get JSON data from request body
$rawData = file_get_contents("php://input");
$data = json_decode($rawData, true);

// Validate input data format
if (!isset($data['items']) || !is_array($data['items'])) {
    echo json_encode(['success' => false, 'message' => 'Invalid data format']);
    exit;
}

$items = $data['items'];
$count = 0;  // Counter for successfully added routines

try {
    // Start database transaction - all or nothing approach
    $conn->beginTransaction();

    /**
     * Prepared statement to check for scheduling conflicts
     * Checks if time slot is already taken by same room or teacher
     */
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

    /**
     * Prepared statement to insert new routine entries
     */
    $insertStmt = $conn->prepare("INSERT INTO routines (subject_name, day_of_week, start_time, end_time, room_number, teacher_id, teacher_name, department, semester) VALUES (:subject, :day, :start, :end, :room, :teacher_id, :teacher_name, :dept, :sem)");

    $conflicts = [];  // Array to store any conflicts found
    $processedItems = [];  // Array to track items already processed in batch

    // Process each routine item
    foreach ($items as $index => $item) {
        // Extract and validate item data
        $teacherId = intval($item['teacher_id'] ?? 0);
        $teacherName = $item['teacher_name'] ?? 'Unknown';
        $itemStart = $item['start'];
        $itemEnd = $item['end'];
        $itemDay = $item['day'];
        $itemRoom = $item['room'];

        // STEP 1: Check for conflicts against existing database routines
        $checkStmt->execute([
            ':day' => $itemDay,
            ':teacher_id' => $teacherId,
            ':room' => $itemRoom,
            ':start' => $itemStart,
            ':end' => $itemEnd
        ]);

        // Collect any conflicts found in database
        while ($conflict = $checkStmt->fetch(PDO::FETCH_ASSOC)) {
            $type = ($conflict['room_number'] == $itemRoom) ? "Room $itemRoom" : "Teacher $teacherName";
            $conflicts[] = [
                'index' => $index,
                'subject' => $item['subject'],
                'message' => "Conflict with existing class: {$conflict['subject_name']} ($type) at {$conflict['start_time']}-{$conflict['end_time']}"
            ];
        }

        // STEP 2: Check for conflicts against items in current batch
        foreach ($processedItems as $p) {
            // Check if time slots overlap on same day
            if ($p['day'] === $itemDay && ($p['start'] < $itemEnd && $p['end'] > $itemStart)) {
                // Check for room conflict
                if ($p['room'] === $itemRoom) {
                    $conflicts[] = [
                        'index' => $index,
                        'subject' => $item['subject'],
                        'message' => "Internal conflict: Room $itemRoom is already used by '{$p['subject']}' in this batch."
                    ];
                }
                // Check for teacher conflict
                if ($p['teacher_id'] === $teacherId && $teacherId !== 0) {
                    $conflicts[] = [
                        'index' => $index,
                        'subject' => $item['subject'],
                        'message' => "Internal conflict: Teacher $teacherName is already assigned to '{$p['subject']}' in this batch."
                    ];
                }
            }
        }

        // STEP 3: If no conflicts, insert the routine item
        if (empty($conflicts)) {
            $insertStmt->execute([
                ':subject' => $item['subject'],
                ':day' => $itemDay,
                ':start' => $itemStart,
                ':end' => $itemEnd,
                ':room' => $itemRoom,
                ':teacher_id' => $teacherId ?: null,  // Use NULL if no teacher assigned
                ':teacher_name' => $teacherName,
                ':dept' => $item['department'],
                ':sem' => $item['semester']
            ]);
            $count++;
            
            // Add to processed items for batch conflict checking
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

    // STEP 4: Check final result - commit or rollback entire transaction
    if (!empty($conflicts)) {
        // Conflicts found - reject entire batch and rollback
        $conn->rollBack();
        echo json_encode([
            'success' => false,
            'message' => 'Scheduling conflicts detected',
            'conflicts' => $conflicts
        ]);
    } else {
        // No conflicts - commit all changes
        $conn->commit();

        // Log the successful routine creation action
        session_start();
        if (isset($_SESSION['user_id'])) {
            $logStmt = $conn->prepare("INSERT INTO activity_log (user_id, user_role, user_name, action_type, action_title, action_description, icon_class, badge_type) VALUES (?, ?, ?, 'routine', 'Routine Published', ?, 'ri-calendar-event-line', 'success')");
            $logStmt->execute([$_SESSION['user_id'], $_SESSION['user_role'], $_SESSION['user_name'], "Published $count routine entries to " . ($items[0]['department'] ?? 'Department')]);
        }

        // Send success response
        echo json_encode([
            'success' => true,
            'message' => "Successfully added $count routine entries",
            'total_processed' => count($items)
        ]);
    }

} catch (PDOException $e) {
    // Database error occurred - rollback transaction
    if ($conn->inTransaction()) {
        $conn->rollBack();
    }
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}
?>
