<?php
include './includes/dbconnection.php'; // Include your database connection settings

if (isset($_GET['notification_id'])) {
    $notificationId = $_GET['notification_id'];

    // Sanitize the ID
    $notificationId = mysqli_real_escape_string($conn, $notificationId);

    // Fetch the notification details from the database
    $query = "SELECT 
            sn.notification_title, 
            sn.notification_description, 
            sn.notification_date,
            CONCAT(si.first_name, ' ', si.middle_name, ' ', si.last_name) AS name,
            CONCAT(c.class_name, ' (', c.batch_year, ') ') AS classname
        FROM student_notifications sn
        JOIN student_info si ON sn.student_id = si.id
        JOIN classes c ON si.class_id = c.id
        WHERE sn.notification_id = '$notificationId'
    ";
    $result = mysqli_query($conn, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        $notification = mysqli_fetch_assoc($result);
        $date = new DateTime($notification['notification_date']);
        $formattedDate = $date->format('d/m/Y - h:i a');

        // Replace the original notification date with the formatted date
        $notification['notification_date'] = $formattedDate;
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
    <link rel="stylesheet" href="./css/cards.css">
</head>

<body>
    <h1>Notification Details</h1>
    <?php if (!empty($notification)) { ?>
        <div class="card" style="margin-top: 20px;">
            <div class="heading" style="background-color: #d9d9d9;">
                <h1><?php echo htmlspecialchars($notification['name']); ?></h1>
                <p><?php echo htmlspecialchars($notification['classname']); ?></p>
            </div>
            <div class="details">
                <div class="column">
                    <p><strong>Title</strong>
                    <div><?php echo htmlspecialchars($notification['notification_title']); ?></div>
                    </p>
                    <p><strong>Time</strong>
                    <div><?php echo htmlspecialchars($notification['notification_date']); ?></div>
                    </p>
                    <p><strong>Description</strong>
                    <div>
                        <?php echo htmlspecialchars($notification['notification_description']); ?></p>
                    </div>
                    </p>
                </div>
            </div>
            <div class="actions">
                <button class="button" id="search-button" onclick="window.location.href='notifications.php';">Back</button>
            </div>
        </div>
    <?php } ?>
</body>

</html>