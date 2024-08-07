<?php
include 'dbconnection.php'; // Database connection

// Fetch all students
$students = mysqli_query($conn, "SELECT id, class_id, fees_pending FROM student_info");

while ($student = mysqli_fetch_assoc($students)) {
    $student_id = $student['id'];
    $class_id = $student['class_id'];
    $current_fees_pending = $student['fees_pending'];

    // Fetch class fees
    $result = mysqli_query($conn, "SELECT class_fees FROM classes WHERE id = '$class_id'");
    $row = mysqli_fetch_assoc($result);
    $class_fees = $row['class_fees'];

    // Add class fees to current pending fees
    $new_fees_pending = $current_fees_pending + $class_fees;

    // Update fees_pending
    $sql = "UPDATE student_info SET fees_pending = '$new_fees_pending' WHERE id = '$student_id'";
    
    if (!mysqli_query($conn, $sql)) {
        echo "Error updating fees for student ID $student_id: " . mysqli_error($conn) . "<br>";
    }
}

echo "<script>alert('Fees Added Successfully!!'); window.location.href = '../fees.php';</script>";
mysqli_close($conn);
?>
