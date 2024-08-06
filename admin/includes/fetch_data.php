<?php
include 'dbconnection.php'; // Ensure this file contains correct database connection settings

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $tableName = $_POST['table'];
    $columns = explode(',', $_POST['columns']);

    // Sanitize table and column names
    $tableName = mysqli_real_escape_string($conn, $tableName);
    $columns = array_map(function ($col) use ($conn) {
        return mysqli_real_escape_string($conn, $col);
    }, $columns);

    // Construct the SQL query
    $columnsList = implode(', ', $columns);
    $query = "SELECT $columnsList FROM $tableName";

    $result = mysqli_query($conn, $query);

    if (!$result) {
        echo json_encode(['error' => 'Query failed: ' . mysqli_error($conn)]);
        exit;
    }

    $data = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $data[] = $row;
    }

    echo json_encode(['columns' => $columns, 'data' => $data]);
}
?>