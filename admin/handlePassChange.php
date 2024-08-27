<?php
session_start();
require './includes/dbconnection.php'; // Make sure to replace with your actual DB connection file

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $newPassword = $_POST['new_password'];
    $confirmPassword = $_POST['confirm_password'];

    // Check if the new passwords match
    if ($newPassword !== $confirmPassword) {
        header('Location: forgotPassword.php?error=password_mismatch');
        exit();
    }

    // Hash the new password
    $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

    // Prepare SQL statement
    $stmt = $conn->prepare("UPDATE admins SET password_hash = ? WHERE admin_email = ?");
    $stmt->bind_param("ss", $hashedPassword, $email);

    if ($stmt->execute()) {
        if ($stmt->affected_rows > 0) {
            echo "<script>alert('Password Changed Successfully!!'); window.location.href = 'login.php';</script>";
        } else {
            echo "<script>alert('Invalid Credentials. Try Again!!'); window.location.href = 'forgotPassword.php';</script>";
        }
    } else {
        // Error executing the query
        header('Location: reset_password.php?error=database_error');
    }

    $stmt->close();
    $conn->close();
}
?>
