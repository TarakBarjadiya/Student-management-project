<?php
header('Content-Type: application/json');

include './includes/dbconnection.php';

$id = $_GET['id'] ?? '';
$table = $_GET['table'] ?? '';

if (!$id || !$table) {
    echo json_encode(['error' => 'ID or table name not specified.']);
    exit;
}

// Sanitize table name to prevent SQL injection
$table = preg_replace('/[^a-zA-Z0-9_]/', '', $table);

if ($table === 'classes') {
    // Check if there are students associated with this class
    $checkStudents = $conn->prepare("SELECT COUNT(*) AS student_count FROM student_info WHERE class_id = ?");
    $checkStudents->bind_param('i', $id);
    $checkStudents->execute();
    $result = $checkStudents->get_result();
    $row = $result->fetch_assoc();

    if ($row['student_count'] > 0) {
        echo json_encode(['error' => 'Cannot delete class because students are still assigned to it. Please remove students first.']);
        $checkStudents->close();
        $conn->close();
        exit;
    }
    $checkStudents->close();
}

// Prepare the SQL query to delete the class
$query = $conn->prepare("DELETE FROM $table WHERE id = ?");
$query->bind_param('i', $id);

if ($query->execute()) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['error' => $query->error]);
}

$query->close();
$conn->close();
?>
