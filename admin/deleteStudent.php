<?php
include "./includes/dbconnection.php";

// Get the student ID from the URL
$student_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($student_id) {
    // Delete the student record
    $query = "DELETE FROM student_info WHERE id = $student_id";

    if (mysqli_query($conn, $query)) {
        echo "Student deleted successfully.";
    } else {
        echo "Error deleting record: " . mysqli_error($conn);
    }

    // Redirect to manageStudents.php after deletion
    header("Location: manageStudents.php");
} else {
    echo "Invalid student ID.";
}
?>