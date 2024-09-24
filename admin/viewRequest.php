<?php
// Include your sidebar, and database connection files
include './includes/sidebar.php';
include './includes/dbconnection.php'; // assuming db_connect.php connects to your DB

// Get the request_id from the URL
if (isset($_GET['request_id'])) {
    $request_id = $_GET['request_id'];

    // Fetch the request details from the database
    $sql = "SELECT requests.id, requests.request_title, requests.request_description, requests.request_date, requests.status,
                   CONCAT(student_info.enrollment_number , ' - ', student_info.first_name, ' ', student_info.last_name,' : ', classes.class_name, ' (',classes.batch_year, ')') as student_details
            FROM requests
            JOIN student_info ON requests.student_id = student_info.id
            JOIN classes ON student_info.class_id = classes.id
            WHERE requests.id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $request_id); // 'i' denotes an integer type
    $stmt->execute();
    $result = $stmt->get_result();
    $request = $result->fetch_assoc();

    // Check if request exists
    if ($request) {
        // Handle form submission for updating status
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $new_status = $_POST['status'];

            // Update the status in the database
            $update_sql = "UPDATE requests SET status = ? WHERE id = ?";
            $update_stmt = $conn->prepare($update_sql);
            $update_stmt->bind_param("si", $new_status, $request_id);
            if ($update_stmt->execute()) {
                echo "<script>alert('Status Updated Successfully!!'); window.location.href = 'requests.php';</script>";
                // Refresh request data to reflect the change
                $stmt->execute();
                $request = $stmt->get_result()->fetch_assoc();
            } else {
                echo "<script>alert('Failed to update status!!');</script>";
            }
        }
    } else {
        echo "<p>Request not found!</p>";
        exit;
    }
} else {
    echo "<p>No request ID provided!</p>";
    exit;
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Request</title>
    <link rel="stylesheet" href="./css/styles.css">
    <style>
        /* Styling for the card */
        .request-card {
            background-color: #fff;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            margin: 20px auto;
            max-width: 600px;
        }

        .request-card h2 {
            font-size: 1.5rem;
            margin-bottom: 15px;
            color: #333;
        }

        .request-card .field {
            margin-bottom: 10px;
            padding: 10px 0;
            border-bottom: 1px solid #ddd;
        }

        .request-card .field label {
            font-weight: bold;
            display: block;
            color: #555;
        }

        .request-card .field p {
            margin: 0;
            color: #333;
        }

        .status-update-form {
            margin-top: 20px;
        }

        .status-update-form label {
            font-size: 1rem;
            font-weight: bold;
            margin-right: 10px;
        }

        .status-update-form select {
            padding: 8px;
            font-size: .9rem;
            border: 1px solid #ccc;
            border-radius: 5px;
            font: inherit;
            cursor: pointer;
        }

        .status-update-form button {
            padding: 8px 16px;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font: inherit;
        }

        .status-update-form button:hover {
            background-color: #0056b3;
        }
    </style>
</head>

<body>
    <h1>Request Details</h1>

    <?php if ($request): ?>
        <!-- Card layout for request details -->
        <div class="request-card">
            <h2>Request #<?php echo $request['id']; ?></h2>

            <div class="field">
                <label>Request Title</label>
                <p><?php echo htmlspecialchars($request['request_title']); ?></p>
            </div>

            <div class="field">
                <label>Student Details</label>
                <p><?php echo htmlspecialchars($request['student_details']); ?></p>
            </div>

            <div class="field">
                <label>Description</label>
                <p><?php echo htmlspecialchars($request['request_description']); ?></p>
            </div>

            <div class="field">
                <label>Request Date</label>
                <p><?php echo $request['request_date']; ?></p>
            </div>

            <div class="field">
                <label>Status</label>
                <p><?php echo ucfirst($request['status']); ?></p>
            </div>

            <!-- Form to update the status -->
            <div class="status-update-form">
                <form action="" method="POST">
                    <label for="status">Change Status:</label>
                    <select name="status" id="status">
                        <option value="pending" <?php if ($request['status'] == 'Pending') echo 'selected'; ?>>Pending</option>
                        <option value="approved" <?php if ($request['status'] == 'Approved') echo 'selected'; ?>>Approved</option>
                        <option value="rejected" <?php if ($request['status'] == 'Rejected') echo 'selected'; ?>>Rejected</option>
                    </select>
                    <button type="submit">Update Status</button>
                </form>
            </div>
        </div>
    <?php endif; ?>

</body>

</html>

<?php
// Close the database connection
$conn->close();
?>