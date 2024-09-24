<?php include "./includes/sidebar.php"; ?>
<?php include "./includes/dbconnection.php"; ?>

<?php
if (isset($_SESSION['student_id'])) {
    $student_id = $_SESSION['student_id'];

    // Fetch the class_id for the logged-in student
    $stmt = $conn->prepare("
        SELECT class_id 
        FROM student_info 
        WHERE id = ?
    ");
    $stmt->bind_param("i", $student_id);
    $stmt->execute();
    $stmt->bind_result($class_id);
    $stmt->fetch();
    $stmt->close();

    // Fetch class notices for the student's class
    $stmt = $conn->prepare("
        SELECT c_notice_title, c_notice_description, publish_date 
        FROM class_notices 
        WHERE class_id = ?
        ORDER BY publish_date DESC
    ");
    $stmt->bind_param("i", $class_id);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($title, $description, $date);

        // Initialize variables to track dates
        $currentDateLabel = "";

        echo '<h1>Class Notices</h1>';
        echo '<div class="notifications">';

        while ($stmt->fetch()) {
            // Convert the notification_date to a PHP DateTime object
            $notificationDate = new DateTime($date);
            $today = new DateTime('today');
            $yesterday = new DateTime('yesterday');

            // Determine the date label
            if ($notificationDate >= $today) {
                $dateLabel = "Today";
            } elseif ($notificationDate >= $yesterday) {
                $dateLabel = "Yesterday";
            } else {
                $dateLabel = $notificationDate->format('d F, Y (l)'); // 'l' gives the full textual day name
            }

            // Display the date label only once for each group of notifications
            if ($dateLabel !== $currentDateLabel) {
                if ($currentDateLabel !== "") {
                    // Close the previous date section
                    echo "</div>";
                }
                echo "<h2>$dateLabel</h2>";
                echo '<div class="notification-group">';
                $currentDateLabel = $dateLabel;
            }

            // Display the class notice
            echo '<div class="notification">';
            echo '<h3>' . htmlspecialchars($title) . '</h3>';
            echo '<p>' . htmlspecialchars($description) . '</p>';
            echo '<hr>';
            echo '</div>';
        }

        // Close the last date section
        echo "</div>";
        echo '</div>';
    } else {
        echo '<h1>Class Notices</h1>';
        // No notices found
        echo "<p>No notices found.</p>";
    }

    $stmt->close();
} else {
    // Redirect to login page if session is not set
    header("Location: login.php");
    exit();
}

$conn->close();
?>

<style>
    .notification-group {
        margin-bottom: 20px;
    }

    .notification {
        margin-left: 20px;
        border-left: 2px solid #ccc;
        padding-left: 10px;
        margin-top: 10px;
    }

    .notification h3 {
        margin: 0;
        font-size: 1.1em;
        color: #2a559f;
    }

    .notification p {
        margin: 5px 0;
        font-size: 0.9em;
    }

    h2 {
        margin-top: 20px;
        font-size: 1.4em;
    }
</style>
