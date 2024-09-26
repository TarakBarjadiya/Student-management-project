<?php
// Include your sidebar and database connection files
include './includes/sidebar.php';
include './includes/dbconnection.php'; // assuming dbconnection.php connects to your DB

// Get the inquiry_id from the URL
if (isset($_GET['inq_id'])) {
    $inquiry_id = $_GET['inq_id'];

    // Fetch the inquiry details from the database
    $sql = "SELECT inquiries.id, inquiries.inq_name, inquiries.inq_email, inquiries.inq_msg, inquiries.inq_date
            FROM inquiries
            WHERE inquiries.id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $inquiry_id); // 'i' denotes an integer type
    $stmt->execute();
    $result = $stmt->get_result();
    $inquiry = $result->fetch_assoc();

    // Check if inquiry exists
    if (!$inquiry) {
        echo "<p>Inquiry not found!</p>";
        exit;
    }
} else {
    echo "<p>No inquiry ID provided!</p>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Inquiry</title>
    <link rel="stylesheet" href="./css/styles.css">
    <style>
        /* Styling for the card */
        .inquiry-card {
            background-color: #fff;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            margin: 20px auto;
            max-width: 600px;
            word-wrap: break-word;
            /* Ensures long words break onto the next line */
        }

        .inquiry-card h2 {
            font-size: 1.5rem;
            margin-bottom: 15px;
            color: #333;
        }

        .inquiry-card .field {
            margin-bottom: 10px;
            padding: 10px 0;
            border-bottom: 1px solid #ddd;
            word-wrap: break-word;
            /* Ensures long text breaks onto the next line */
        }

        .inquiry-card .field label {
            font-weight: bold;
            display: block;
            color: #555;
        }

        .inquiry-card .field p {
            margin: 0;
            color: #333;
            word-break: break-word;
            /* Ensures long words break when necessary */
        }
    </style>
</head>

<body>
    <h1>Inquiry Details</h1>

    <?php if ($inquiry): ?>
        <!-- Card layout for inquiry details -->
        <div class="inquiry-card">
            <h2>Inquiry #<?php echo $inquiry['id']; ?></h2>

            <div class="field">
                <label>Name</label>
                <p><?php echo htmlspecialchars($inquiry['inq_name']); ?></p>
            </div>

            <div class="field">
                <label>Email</label>
                <p><?php echo htmlspecialchars($inquiry['inq_email']); ?></p>
            </div>

            <div class="field">
                <label>Message</label>
                <p><?php echo htmlspecialchars($inquiry['inq_msg']); ?></p>
            </div>

            <div class="field">
                <label>Inquiry Date</label>
                <p><?php echo htmlspecialchars($inquiry['inq_date']); ?></p>
            </div>
        </div>
    <?php endif; ?>

</body>

</html>

<?php
// Close the database connection
$conn->close();
?>