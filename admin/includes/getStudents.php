<?php
include 'dbconnection.php'; // Database connection

$class_id = isset($_GET['class_id']) ? (int)$_GET['class_id'] : 0;

$sql = "SELECT id, CONCAT(first_name, ' ', last_name, ' - ', enrollment_number) AS student_name FROM student_info WHERE class_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $class_id);
$stmt->execute();
$result = $stmt->get_result();

$students = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $students[] = $row;
    }
}

$stmt->close();
$conn->close();
header('Content-Type: application/json');
echo json_encode($students);
?>
