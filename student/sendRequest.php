<?php include "./includes/sidebar.php"; ?>
<?php
include './includes/dbconnection.php'; // Include your database connection
// Initialize variables
$message = "";

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Fetch the data from the form
    $student_id = isset($_SESSION['student_id']) ? (int)$_SESSION['student_id'] : 0;
    $title = $_POST['request_title'] ?? '';
    $description = $_POST['request_description'] ?? '';

    // Insert into the database
    $sql = "INSERT INTO requests (student_id, request_title, request_description, request_date, status) VALUES (?, ?, ?, NOW(), 'pending')";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('iss', $student_id, $title, $description);

    if ($stmt->execute()) {
        echo "<script>alert('Request Sent Successfully!'); window.location.href = 'requests.php';</script>";
    } else {
        $message = "Failed to send request: " . $stmt->error;
    }

    $stmt->close();
}

// Close the connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Send Request</title>
    <link rel="stylesheet" href="./css/cards.css"> <!-- Reuse styles for a clean layout -->
    <link rel="stylesheet" href="./css/forms.css"> <!-- Link to form styles for consistent look -->
</head>

<body>
    <h1>Send a Request</h1>

    <form action="" method="POST">
        <div class="nice-form-group">
            <label for="request_title">Request Title:</label>
            <input type="text" name="request_title" id="request_title" required>
        </div>

        <div class="nice-form-group">
            <label for="request_description">Request Description:</label>
            <textarea name="request_description" id="request_description" style="resize: vertical;" required></textarea>
        </div>

        <div class="form-actions">
            <button class="input-button" type="button" onclick="window.location.href='requests.php';">Back</button>
            <button class="input-button" type="submit">Send Request</button>
        </div>
    </form>
</body>

</html>
