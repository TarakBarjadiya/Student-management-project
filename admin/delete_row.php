<?php
header('Content-Type: application/json');

// Include the database connection
include './includes/dbconnection.php';

$id = $_GET['id'] ?? '';
$table = $_GET['table'] ?? '';

if (!$id || !$table) {
    echo json_encode(['error' => 'ID or table name not specified.']);
    exit;
}

// Sanitize table name to prevent SQL injection
$table = preg_replace('/[^a-zA-Z0-9_]/', '', $table);

// Prepare the SQL query to delete the row
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
