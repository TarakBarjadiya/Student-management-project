<?php include "./includes/sidebar.php"; ?>
<?php
include './includes/dbconnection.php'; // Include your database connection settings

// Check if the request ID is set
if (isset($_GET['request_id'])) {
    $request_id = (int)$_GET['request_id'];

    // Fetch the request details from the database
    $sql = "SELECT request_title, request_description, request_date, status FROM requests WHERE request_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $request_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $request = $result->fetch_assoc();
    } else {
        echo "Request not found.";
        exit;
    }

    $stmt->close();
} else {
    echo "No request ID provided.";
    exit;
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Request Details</title>
    <link rel="stylesheet" href="./css/sidebar.css">
    <link rel="stylesheet" href="./css/req_details.css">
</head>

<body>
    <h1>Request Details</h1>
    <div class="request-details">
        <h2><?php echo htmlspecialchars($request['request_title']); ?></h2>
        <p><strong>Request Date:</strong> <?php echo date('d/m/Y', strtotime($request['request_date'])); ?></p>
        <p><strong>Status:</strong> <span class="status <?php echo strtolower($request['status']); ?>"><?php echo ucfirst($request['status']); ?></span></p>
        <p><strong>Description:</strong></p>
        <p><?php echo nl2br(htmlspecialchars($request['request_description'])); ?></p>
    </div>
    <button onclick="window.location.href='requests.php';">Back to My Requests</button>
</body>

</html>
