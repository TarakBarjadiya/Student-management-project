<?php
session_start();
include "./includes/dbconnection.php";

if (isset($_SESSION['student_id'])) {
    $student_id = $_SESSION['student_id'];
    $stmt = $conn->prepare("SELECT first_name, middle_name, last_name FROM student_info WHERE id = ?");
    $stmt->bind_param("i", $student_id);
    $stmt->execute();
    $stmt->bind_result($first_name,$middle_name, $last_name);
    $stmt->fetch();
    $stmt->close();
} else {
    echo "NO SESSION STARTED";
}

$conn->close();
?>