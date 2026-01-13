<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

$host = 'localhost';
$user = 'root';
$password = '';
$dbname = 'routine_flow_db';

$conn = new mysqli($host, $user, $password, $dbname);

if ($conn->connect_error) {
    echo json_encode(['success' => false, 'message' => 'Database connection failed']);
    exit;
}

// Helper to get count
function getCount($conn, $table)
{
    $result = $conn->query("SELECT COUNT(*) as count FROM $table");
    return $result->fetch_assoc()['count'];
}

// Helper to get distinct departments count (from students and teachers combined or just defined list)
// For now, let's count distinct departments in students + teachers
function getDeptCount($conn)
{
    $sql = "SELECT COUNT(DISTINCT department) as count FROM (SELECT department FROM students UNION SELECT department FROM teachers) as depts";
    $result = $conn->query($sql);
    return $result->fetch_assoc()['count'];
}

$response = [
    'success' => true,
    'stats' => [
        'users' => getCount($conn, 'students') + getCount($conn, 'teachers') + getCount($conn, 'admins'),
        'routines' => getCount($conn, 'routines'),
        'departments' => getDeptCount($conn),
        'alerts' => getCount($conn, 'notices')
    ]
];

echo json_encode($response);

$conn->close();
?>