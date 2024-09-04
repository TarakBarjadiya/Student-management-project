<?php include "./includes/sidebar.php" ?>
<?php include './includes/dbconnection.php'; ?>

<?php
if (isset($_SESSION['student_id'])) {
    $student_id = $_SESSION['student_id'];

    // Use prepared statements to prevent SQL injection
    $query = "
    SELECT si.id, 
           si.enrollment_number, 
           CONCAT(si.first_name, ' ', si.middle_name, ' ', si.last_name) AS student_name, 
           si.fees_pending, 
           si.fees_paid, 
           CONCAT(c.class_name, ' (Batch: ', c.batch_year , ') ') as class_name
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
if ($_SERVER['REQUEST_METHOD'] == 'POST' && $_POST['action'] == 'pay_fees') {
    $student_id = $_POST['student_id'];
    $paid_amount = $_POST['paid_amount'];
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

        // Ensure paid amount does not exceed pending fees
        if ($paid_amount > $fees_pending) {
            echo "<script>alert('Paid amount cannot exceed pending fees.'); history.back();</script>";
            exit;
        }

        // Update fees
        $new_fees_pending = max(0, $fees_pending - $paid_amount);
        $new_fees_paid = $fees_paid + $paid_amount;

        // Update the student info with the new values
        $sql = "UPDATE student_info SET fees_pending = ?, fees_paid = ?, paid_date = ? WHERE id = ?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, 'dids', $new_fees_pending, $new_fees_paid, $paid_date, $student_id);

        if (mysqli_stmt_execute($stmt)) {
            echo "<script>alert('Payment updated successfully!'); window.location.href = 'payFees.php?id={$student_id}';</script>";
        } else {
            echo "Error: " . $sql . " " . mysqli_error($conn);
        }
    } else {
        echo "Student not found.";
    }

    mysqli_close($conn);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pay Fees</title>
    <script>
        function validatePaymentForm() {
            const paidAmount = document.getElementById('paid_amount').value;

            if (paidAmount === '' || isNaN(paidAmount) || paidAmount <= 0) {
                alert('Please enter a valid paid amount (positive number).');
                return false;
            }

            return true;
        }

        function submitPaymentForm() {
            if (validatePaymentForm()) {
                document.getElementById('pay_fees_form').submit();
            }
        }

        document.addEventListener('DOMContentLoaded', (event) => {
            document.getElementById('pay_fees_form').addEventListener('keydown', function(event) {
                if (event.key === 'Enter') {
                    event.preventDefault(); // Prevent default form submission
                    submitPaymentForm(); // Call custom validation and submission
                }
            });
        });
    </script>
</head>

<body>
    <h1>Pay Fees</h1>

    <!-- Fee Payment Form -->
    <form id="pay_fees_form" action="payFees.php?id=<?php echo htmlspecialchars($student_id); ?>" method="post">
        <input type="hidden" id="student_id" name="student_id" value="<?php echo htmlspecialchars($student['id']); ?>">
        <input type="hidden" name="action" value="pay_fees">

        <div class="nice-form-group">
            <label for="enrollment_number">Student Details:</label>
            <input type="text" id="enrollment_number" name="enrollment_number" value="<?php echo htmlspecialchars($student['enrollment_number'] . ' - ' . $student['student_name']); ?>" readonly>
        </div>

        <div class="nice-form-group">
            <label for="class_name">Class:</label>
            <input type="text" id="class_name" name="class_name" value="<?php echo htmlspecialchars($student['class_name']); ?>" readonly>
        </div>

        <div class="nice-form-group">
            <label for="current_fees_pending">Current Fees Pending:</label>
            <input type="text" id="current_fees_pending" name="current_fees_pending" value="<?php echo htmlspecialchars($student['fees_pending']); ?>" readonly>
        </div>

        <div class="nice-form-group">
            <label for="fees_paid">Fees Paid:</label>
            <input type="text" id="fees_paid" name="fees_paid" value="<?php echo htmlspecialchars($student['fees_paid']); ?>" readonly>
        </div>

        <div class="nice-form-group">
            <label for="paid_amount">Amount to Pay:</label>
            <input type="text" id="paid_amount" name="paid_amount" required>
        </div>

        <input class="input-button" type="button" value="Save Changes" onclick="submitPaymentForm();">
    </form>
</body>

</html>