<?php
include 'dbconnection.php'; // Database connection

$sql = "SELECT id, class_name, batch_year FROM classes";
$result = $conn->query($sql);

$classes = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $classes[] = $row;
    }
}

$conn->close();
header('Content-Type: application/json');
echo json_encode($classes);
?>
