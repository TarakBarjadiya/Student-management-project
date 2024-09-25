<?php
session_start(); // Start the session

// Check if a session exists
if (isset($_SESSION['student_id'])) {
    // Destroy all session variables
    session_unset();
    session_destroy();
    
    // Redirect to the login page (or any other page)
    header("Location: login.php");
    exit();
} else {
    // If no session exists, redirect to login page directly
    header("Location: login.php");
    exit();
}
?>
