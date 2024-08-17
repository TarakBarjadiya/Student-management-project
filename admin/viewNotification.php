<?php
include './includes/dbconnection.php'; // Include your database connection settings

if (isset($_GET['notification_id'])) {
    $notificationId = $_GET['notification_id'];

    // Sanitize the ID
    $notificationId = mysqli_real_escape_string($conn, $notificationId);

    // Fetch the notification details from the database
    $query = "SELECT * FROM student_notifications WHERE notification_id = '$notificationId'";
    $result = mysqli_query($conn, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        $notification = mysqli_fetch_assoc($result);
    } else {
        echo "Notification not found.";
        exit;
    }
} else {
    echo "No notification ID provided.";
    exit;
}
?>

<?php include "./includes/sidebar.php" ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Notification</title>
</head>
<body>
    <h1>Notification Details</h1>
</body>
</html>