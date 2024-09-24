<?php include "./includes/sidebar.php" ?>
<?php
include './includes/dbconnection.php'; // Include your database connection settings

if (isset($_GET['notice_id'])) {
    $noticeId = $_GET['notice_id'];

    // Sanitize the ID
    $noticeId = mysqli_real_escape_string($conn, $noticeId);

    // Fetch the notice details from the database
    $query = "SELECT 
            cn.c_notice_title, 
            cn.c_notice_description, 
            cn.publish_date,
            CONCAT(c.class_name, ' (', c.batch_year, ')') AS classname
        FROM class_notices cn
        JOIN classes c ON cn.class_id = c.id
        WHERE cn.id = '$noticeId'
    ";
    $result = mysqli_query($conn, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        $notice = mysqli_fetch_assoc($result);
        $date = new DateTime($notice['publish_date']);
        $formattedDate = $date->format('d/m/Y - h:i a');

        // Replace the original notice date with the formatted date
        $notice['publish_date'] = $formattedDate;
    } else {
        echo "Class notice not found.";
        exit;
    }
} else {
    echo "No class notice ID provided.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Class Notice</title>
    <link rel="stylesheet" href="./css/cards.css">
</head>

<body>
    <h1>Class Notice Details</h1>
    <?php if (!empty($notice)) { ?>
        <div class="card" style="margin-top: 20px;">
            <div class="heading" style="background-color: #d9d9d9;">
                <h1><?php echo htmlspecialchars($notice['classname']); ?></h1>
            </div>
            <div class="details">
                <div>
                    <p><strong>Title</strong></p>
                    <div><?php echo htmlspecialchars($notice['c_notice_title']); ?></div>

                    <p><strong>Time</strong></p>
                    <div><?php echo htmlspecialchars($notice['publish_date']); ?></div>

                    <p><strong>Description</strong></p>
                    <div><?php echo htmlspecialchars($notice['c_notice_description']); ?></div>
                </div>
            </div>
            <div class="actions">
                <button class="button" id="search-button" onclick="window.location.href='manageClassNotices.php';">Back</button>
            </div>
        </div>
    <?php } ?>
</body>

</html>
