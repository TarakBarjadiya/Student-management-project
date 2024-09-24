<?php include "./includes/sidebar.php" ?>
<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "sms";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Initialize variables
$message = "";

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Fetch the data from the form
    $class_id = isset($_POST['class_id']) ? (int)$_POST['class_id'] : 0;
    $title = $_POST['class_notice_title'] ?? '';
    $description = $_POST['class_notice_description'] ?? '';

    // Insert the class notice into the class_notices table
    $sql = "INSERT INTO class_notices (class_id, c_notice_title, c_notice_description, publish_date) VALUES (?, ?, ?, NOW())";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('iss', $class_id, $title, $description);

    if ($stmt->execute()) {
        echo "<script>alert('Class Notice Sent Successfully!!'); window.location.href = 'addClassNotice.php';</script>";
    } else {
        $message = "Failed to send class notice: " . $stmt->error;
    }

    $stmt->close();
}

// Fetch classes
$classQuery = "SELECT id, class_name, batch_year FROM classes";
$classResult = $conn->query($classQuery);
$classes = $classResult->fetch_all(MYSQLI_ASSOC);

// Close the connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Class Notice</title>
</head>

<body>
    <h1>Send Class Notice</h1>

    <form action="" method="POST">
        <div class="nice-form-group">
            <label for="class_select">Select Class:</label>
            <select id="class_select" name="class_id" required>
                <option value="">Select Class</option>
                <?php foreach ($classes as $class): ?>
                    <option value="<?php echo $class['id']; ?>">
                        <?php echo htmlspecialchars($class['class_name'] . ' (' . $class['batch_year'] . ')'); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="nice-form-group">
            <label for="class_notice_title">Notice Title:</label>
            <input type="text" name="class_notice_title" id="class_notice_title" required>
        </div>

        <div class="nice-form-group">
            <label for="class_notice_description">Notice Description:</label>
            <textarea name="class_notice_description" style="resize: vertical;" id="class_notice_description" required></textarea>
        </div>  
        <button class="input-button" type="submit">Send Notice</button>
    </form>
</body>

</html>
