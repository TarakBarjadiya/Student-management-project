<?php
session_start();
require './includes/dbconnection.php'; // Make sure to replace with your actual DB connection file

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $newPassword = $_POST['new_password'];
    $confirmPassword = $_POST['confirm_password'];

    // Check if the new passwords match
    if ($newPassword !== $confirmPassword) {
        header('Location: reset Password.php?error=password_mismatch');
        exit();
    }

    // Hash the new password
    $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

    // Get student_id from student_info using email
    $stmt = $conn->prepare("SELECT id FROM student_info WHERE stud_email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->bind_result($student_id);
    $stmt->fetch();
    $stmt->close();

    if ($student_id) {
        // Update the password in the student_login table
        $stmt = $conn->prepare("UPDATE student_login SET password = ? WHERE student_id = ?");
        $stmt->bind_param("si", $hashedPassword, $student_id);

        if ($stmt->execute()) {
            if ($stmt->affected_rows > 0) {
                echo "<script>alert('Password Changed Successfully!!'); window.location.href = 'login.php';</script>";
            } else {
                echo "<script>alert('Error updating password. Please try again!'); window.location.href = 'resetPassword.php';</script>";
            }
        } else {
            header('Location: resetPassword.php?error=database_error');
        }
        $stmt->close();
    } else {
        echo "<script>alert('Invalid Email. Try Again!!'); window.location.href = 'resetPassword.php';</script>";
    }

    $conn->close();
}
?>
