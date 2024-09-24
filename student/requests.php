<?php include "./includes/sidebar.php"; ?>
<?php
include './includes/dbconnection.php'; // Include your database connection settings

// Check if the student is logged in
if (!isset($_SESSION['student_id'])) {
    // Redirect to login if not logged in
    header("Location: login.php");
    exit();
}

// Fetch the logged-in student's ID
$student_id = $_SESSION['student_id'];

// Fetch the student's requests from the database
$sql = "SELECT id, request_title, request_description, request_date, status FROM requests WHERE student_id = ? ORDER BY request_date DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $student_id);
$stmt->execute();
$result = $stmt->get_result();

// Store the requests in an array
$requests = $result->fetch_all(MYSQLI_ASSOC);

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>My Requests</title>
    <link rel="stylesheet" href="./css/sidebar.css">
    <link rel="stylesheet" href="./css/my-requests.css">
</head>

<body>
    <h1>My Requests</h1>
    <section class="manage-requests">
        <div class="top-links">
            <a href="sendRequest.php">Send Request</a>
        </div>

        <!-- Display the table of requests only if there are requests -->
        <?php if (!empty($requests)) { ?>
            <table id="requests-table" class="styled-table">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Request Date</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($requests as $request) { ?>
                        <tr>
                            <td><?php echo htmlspecialchars($request['request_title']); ?></td>
                            <td><?php echo date('d/m/Y', strtotime($request['request_date'])); ?></td>
                            <td>
                                <span class="status <?php echo strtolower($request['status']); ?>">
                                    <?php echo ucfirst($request['status']); ?>
                                </span>
                            </td>
                            <td>
                                <a href="viewRequest.php?request_id=<?php echo $request['id']; ?>" class="button">View Details</a>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        <?php } else { ?>
            <p>No requests sent yet.</p> <!-- Optional message if desired -->
        <?php } ?>
    </section>
</body>

</html>