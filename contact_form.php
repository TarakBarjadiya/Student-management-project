<?php include "./includes/dbconnection.php" ?>
<?php
// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the form data and trim spaces
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $message = trim($_POST['message']);

    // Validate fields to ensure they are not empty after trimming
    if (empty($name) || empty($email) || empty($message)) {
        echo "<script>alert('All fields are required and cannot be empty or consist solely of spaces.'); window.location.href = 'contact.php';</script>";
    } else {
        // Sanitize the input
        $name = $conn->real_escape_string($name);
        $email = $conn->real_escape_string($email);
        $message = $conn->real_escape_string($message);
        $date = date("Y-m-d H:i:s"); // Current timestamp

        // Insert the data into the inquiries table
        $sql = "INSERT INTO inquiries (inq_name, inq_email, inq_msg, inq_date) VALUES ('$name', '$email', '$message', '$date')";

        if ($conn->query($sql) === TRUE) {
            echo "<script>alert('Your message has been sent successfully.'); window.location.href = 'contact.php';</script>";
        } else {
            echo "<script>alert('Error sending message!! Please try again later.'); window.location.href = 'contact.php';</script>";
        }
    }
}

// Close the database connection
$conn->close();
?>
