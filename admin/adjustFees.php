<?php
include './includes/dbconnection.php'; // Database connection

// Initialize variables
$student_id = '';
$adjustment_amount = 0;
$adjustment_type = 'none';

// Fetch student and class details if ID is set
if (isset($_GET['id'])) {
    $student_id = $_GET['id'];

    // Use prepared statements to prevent SQL injection
    $query = "
    SELECT si.id, 
           si.enrollment_number, 
           CONCAT(si.first_name, ' ', si.middle_name, ' ', si.last_name) AS student_name, 
           si.fees_pending, 
           si.fees_paid, 
           CONCAT(c.class_name, ' (', c.batch_year , ') ') as class_name
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
    $adjustment_amount = isset($_POST['adjustment_amount']) ? (float)$_POST['adjustment_amount'] : 0;
    $adjustment_type = $_POST['adjustment_type'];
    $paid_date = date('Y-m-d');

    // Validate adjustment amount
    if ($adjustment_type !== 'none' && (!is_numeric($adjustment_amount) || $adjustment_amount <= 0)) {
        echo "<script>alert('Adjustment amount must be a positive number.'); window.location.href = 'adjustFees.php?id=" . htmlspecialchars($student_id) . "';</script>";
        exit;
    }

    // Fetch current fees details
    $stmt = mysqli_prepare($conn, "SELECT fees_pending, fees_paid FROM student_info WHERE id = ?");
    mysqli_stmt_bind_param($stmt, 'i', $student_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_assoc($result);

    if ($row) {
        $fees_pending = $row['fees_pending'];
        $fees_paid = $row['fees_paid'];

        // Adjust fees based on adjustment type
        if ($adjustment_type === 'discount') {
            if ($adjustment_amount > $fees_pending) {
                echo "<script>alert('Discount amount cannot exceed pending fees.'); window.location.href = 'adjustFees.php?id=" . htmlspecialchars($student_id) . "';</script>";
                exit;
            }
            $new_fees_pending = max(0, $fees_pending - $adjustment_amount);
        } elseif ($adjustment_type === 'extra_fees') {
            $new_fees_pending = $fees_pending + $adjustment_amount;
        } else {
            $new_fees_pending = $fees_pending; // No change
        }

        // Update the student info with the new values
        $sql = "UPDATE student_info SET fees_pending = ?, paid_date = ? WHERE id = ?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, 'dsi', $new_fees_pending, $paid_date, $student_id);

        if (mysqli_stmt_execute($stmt)) {
            echo "<script>alert('Fees Adjusted Successfully!'); window.location.href = 'fees.php';</script>";
        } else {
            echo "Error: " . mysqli_error($conn);
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
    <title>Adjust Fees</title>
    <script>
        function updateAdjustmentBox() {
            const adjustmentType = document.querySelector('input[name="adjustment_type"]:checked').value;
            const adjustmentBox = document.getElementById('adjustment_box');
            const adjustBtn = document.getElementById('adjust-fee-btn');

            if (adjustmentType === 'none') {
                adjustBtn.style.display = 'none';
                adjustmentBox.style.display = 'none';
            } else {
                adjustBtn.style.display = 'block';
                adjustmentBox.style.display = 'block';
                document.getElementById('adjustment_amount').value = ''; // Clear amount field
            }
        }

        function validateAdjustmentForm() {
            const adjustmentAmount = parseFloat(document.getElementById('adjustment_amount').value.trim());
            const adjustmentType = document.querySelector('input[name="adjustment_type"]:checked');
            const currentFeesPending = parseFloat(document.getElementById('current_fees_pending').value.trim());

            // Ensure adjustment type is selected and amount is valid
            if (!adjustmentType) {
                alert('Please select an adjustment type.');
                return false;
            }

            if (adjustmentType.value !== 'none' && (isNaN(adjustmentAmount) || adjustmentAmount <= 0)) {
                alert('Please enter a valid adjustment amount (positive number).');
                return false;
            }

            if (adjustmentType.value === 'discount' && adjustmentAmount > currentFeesPending) {
                alert('Discount amount cannot exceed pending fees.');
                return false;
            }

            return true;
        }

        function submitAdjustmentForm() {
            if (validateAdjustmentForm()) {
                document.getElementById('adjust_fees_form').submit();
            }
        }

        // Allow form submission with Enter key
        document.addEventListener('DOMContentLoaded', () => {
            document.getElementById('adjust_fees_form').addEventListener('keydown', function(event) {
                if (event.key === 'Enter') {
                    event.preventDefault(); // Prevent default form submission
                    submitAdjustmentForm(); // Call custom validation and submission
                }
            });

            // Initialize display based on the default selected adjustment type
            updateAdjustmentBox();
        });
    </script>
</head>

<body>
    <h1>Adjust Fees</h1>


    <!-- Fee Adjustment Form -->
    <form id="adjust_fees_form" action="adjustFees.php?id=<?php echo htmlspecialchars($student_id); ?>" method="post">
        <input type="hidden" id="student_id" name="student_id" value="<?php echo htmlspecialchars($student['id']); ?>">
        <div class="nice-form-group">
            <label for="enrollment_number">Enrollment Number:</label>
            <input type="text" id="enrollment_number" name="enrollment_number" value="<?php echo htmlspecialchars($student['enrollment_number']); ?>" readonly>
        </div>
        <div class="nice-form-group">
            <label for="first_name">Student Name:</label>
            <input type="text" id="first_name" name="first_name" value="<?php echo htmlspecialchars($student['student_name']); ?>" readonly>
        </div>

        <div class="nice-form-group">
            <label for="class_name">Class Name:</label>
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

        <fieldset class="nice-form-group">
            <legend for="adjustment_type">Adjustment Type:</legend>
            <div class="nice-form-group">
                <input type="radio" id="none" name="adjustment_type" value="none" checked onchange="updateAdjustmentBox()">
                <label for="none">None</label>
            </div>
            <div class="nice-form-group">
                <input type="radio" id="discount" name="adjustment_type" value="discount" onchange="updateAdjustmentBox()">
                <label for="discount">Discount</label>
            </div>
            <div class="nice-form-group">
                <input type="radio" id="extra_fees" name="adjustment_type" value="extra_fees" onchange="updateAdjustmentBox()">
                <label for="extra_fees">Extra Fees</label>
            </div>
        </fieldset>

        <!-- Adjustment Box -->
        <div id="adjustment_box" style="display:none;">
            <div class="nice-form-group">
                <label for="adjustment_amount">Amount:</label>
                <input type="text" id="adjustment_amount" name="adjustment_amount">
            </div>
        </div>
        <div class="input-button-container">
            <input class="input-button" type="button" id="adjust-fee-btn" value="Adjust Fees" style="display: none;" onclick="submitAdjustmentForm();">
            <input class="input-button" type="button" value="Back" onclick="window.location.href='./fees.php';">
        </div>
    </form>
</body>

</html>