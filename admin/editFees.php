<?php
include './includes/dbconnection.php'; // Database connection

// Initialize variables
$student_id = '';
$student = null;

// Fetch student and class details if ID is set
if (isset($_GET['id'])) {
    $student_id = $_GET['id'];

    // Use prepared statements to prevent SQL injection
    $query = "
        SELECT si.id, si.enrollment_number, si.first_name, si.middle_name, si.last_name, si.fees_pending, si.fees_paid, c.class_name 
        FROM student_info si
        JOIN classes c ON si.class_id = c.id
        WHERE si.id = ?
    ";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, 'i', $student_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $student = mysqli_fetch_assoc($result);

    if (!$student) {
        echo "Student not found.";
        exit;
    }
}

// Check if the request is a POST request
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $student_id = $_POST['student_id'];
    $paid_amount = $_POST['paid_amount'];
    $manual_adjustment = $_POST['manual_adjustment'] ?? 0; // Default to 0 if not set
    $paid_date = date('Y-m-d');

    // Fetch current fees details
    $stmt = mysqli_prepare($conn, "SELECT fees_pending, fees_paid FROM student_info WHERE id = ?");
    mysqli_stmt_bind_param($stmt, 'i', $student_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_assoc($result);

    if ($row) {
        $fees_pending = $row['fees_pending'];
        $fees_paid = $row['fees_paid'];

        // Apply manual adjustment (discount or extra fee)
        $new_fees_pending = $fees_pending - $manual_adjustment;
        $new_fees_pending = max(0, $new_fees_pending); // Ensure it doesn't go below 0

        // Ensure paid amount does not exceed the adjusted pending fees
        if ($paid_amount > $new_fees_pending) {
            echo "<script>alert('Paid amount cannot exceed adjusted pending fees.'); history.back();</script>";
            exit;
        }

        // Update fees
        $new_fees_pending = max(0, $new_fees_pending - $paid_amount);
        $new_fees_paid = $fees_paid + $paid_amount;

        // Update the student info with the new values
        $sql = "UPDATE student_info SET fees_pending = ?, fees_paid = ?, paid_date = ? WHERE id = ?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, 'dids', $new_fees_pending, $new_fees_paid, $paid_date, $student_id);

        if (mysqli_stmt_execute($stmt)) {
            echo "<script>alert('Updated Fees Successfully!!'); window.location.href = 'fees.php';</script>";
        } else {
            echo "Error: " . $stmt->error;
        }
    } else {
        echo "Student not found.";
    }

    mysqli_close($conn);
}
?>

<?php include "./includes/sidebar.php" ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Fees</title>
</head>

<body>
    <h1>Edit Fees</h1>
    <form action="editFees.php?id=<?php echo htmlspecialchars($student_id); ?>" method="post">
        <input type="hidden" id="student_id" name="student_id" value="<?php echo htmlspecialchars($student['id']); ?>">

        <label for="enrollment_number">Enrollment Number:</label>
        <input type="text" id="enrollment_number" name="enrollment_number" value="<?php echo htmlspecialchars($student['enrollment_number']); ?>" readonly><br>

        <label for="first_name">First Name:</label>
        <input type="text" id="first_name" name="first_name" value="<?php echo htmlspecialchars($student['first_name']); ?>" readonly><br>

        <label for="middle_name">Middle Name:</label>
        <input type="text" id="middle_name" name="middle_name" value="<?php echo htmlspecialchars($student['middle_name']); ?>" readonly><br>

        <label for="last_name">Last Name:</label>
        <input type="text" id="last_name" name="last_name" value="<?php echo htmlspecialchars($student['last_name']); ?>" readonly><br>

        <label for="class_name">Class Name:</label>
        <input type="text" id="class_name" name="class_name" value="<?php echo htmlspecialchars($student['class_name']); ?>" readonly><br>

        <label for="current_fees_pending">Current Fees Pending:</label>
        <input type="text" id="current_fees_pending" name="current_fees_pending" value="<?php echo htmlspecialchars($student['fees_pending']); ?>" readonly><br>

        <label for="manual_adjustment">Manual Adjustment (Discount or Extra Fee):</label>
        <input type="text" id="manual_adjustment" name="manual_adjustment" value="0"><br>

        <label for="paid_amount">Paid Amount:</label>
        <input type="text" id="paid_amount" name="paid_amount" required><br>

        <input type="submit" value="Update Payment">
        <input type="button" value="Back" onclick="window.location.href = 'fees.php';">
    </form>

</body>

</html>