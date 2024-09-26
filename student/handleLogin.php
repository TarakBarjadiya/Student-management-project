<?php
session_start();
include './includes/dbconnection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $login_input = $_POST['email'];
    $password = $_POST['password'];

    // Fetch student_id from student_info based on email, enrollment_number, or stud_mobile
    $stmt = $conn->prepare("
        SELECT id 
        FROM student_info 
        WHERE stud_email = ? OR enrollment_number = ? OR stud_mobile = ?
    ");
    $stmt->bind_param("sss", $login_input, $login_input, $login_input);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($student_id);
        $stmt->fetch();

        // Fetch login details from student_login
        $stmt = $conn->prepare("
            SELECT password 
            FROM student_login 
            WHERE student_id = ?
        ");
        $stmt->bind_param("i", $student_id);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $stmt->bind_result($hashed_password);
            $stmt->fetch();

            // Verify the password
            if (password_verify($password, $hashed_password)) {
                // Update last_login on successful login
                $stmt = $conn->prepare("UPDATE student_login SET last_login = NOW() WHERE student_id = ?");
                $stmt->bind_param("i", $student_id);
                $stmt->execute();

                $_SESSION['student_id'] = $student_id; // Store student_id in session
                header("Location: myProfile.php");
                // Redirect to dashboard or home page
            } else {
                echo "<script>alert('Incorrect Password!!Please try again.'); window.location.href = 'login.php';</script>";
            }
        } else {
            echo "<script>alert('No login record found for this student.'); window.location.href = 'login.php';</script>";
        }
    } else {
        echo "<script>alert('No account found with that login information.'); window.location.href = 'login.php';</script>";
    }

    $stmt->close();
    $conn->close();
}
?>
