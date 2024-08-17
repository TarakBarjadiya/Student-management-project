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

    // If the table is student_info, include class_name from classes table
    if ($tableName === 'student_info') {
        $columns[] = 'class_name';
        $columnsList = implode(', ', $columns);
        $query = "SELECT student_info.id, student_info.enrollment_number, CONCAT(student_info.first_name, ' ', student_info.middle_name, ' ', student_info.last_name) AS full_name, student_info.fees_pending, student_info.fees_paid, CONCAT(classes.class_name ,' (',classes.batch_year,')') as class_name
                  FROM student_info 
                  JOIN classes ON student_info.class_id = classes.id";
    } elseif ($tableName === 'student_notifications') {
        // $columns[] = 'student_name'; // Add other columns if needed
        $columnsList = implode(', ', $columns);
        $query = "SELECT student_notifications.notification_id, student_notifications.notification_title,student_notifications.notification_description, student_notifications.notification_date, CONCAT(student_info.first_name,' ',student_info.last_name) as student_name
                  FROM student_notifications
                  JOIN student_info ON student_notifications.student_id = student_info.id";
    } else {
        $columnsList = implode(', ', $columns);
        $query = "SELECT $columnsList FROM $tableName";
    }

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
