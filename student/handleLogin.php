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
                echo "Login successful!";
                // Redirect to dashboard or home page
            } else {
                echo "Incorrect password. Please try again.";
            }
        } else {
            echo "No login record found for this student.";
        }
    } else {
        echo "No account found with that login information.";
    }

    $stmt->close();
    $conn->close();
}
?>
