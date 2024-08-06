<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "sms"; // Replace with your database name

    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $class_type = $_POST['class_type'];
    $class_name = $class_type === 'other' ? $_POST['other_class_name'] : $_POST['class_name'];
    $batch_year = $_POST['batch_year'];
    $class_fess = $_POST['class_fees'];

    // Check for duplicate entry
    $check_sql = "SELECT * FROM classes WHERE class_type = ? AND class_name = ? AND batch_year = ?";
    $check_stmt = $conn->prepare($check_sql);
    $check_stmt->bind_param("sss", $class_type, $class_name, $batch_year);
    $check_stmt->execute();
    $result = $check_stmt->get_result();

    if ($result->num_rows > 0) {
        echo "<script>alert('Duplicate entry found. The class already exists.'); window.location.href = 'addClass.php';</script>";
    } else {
        // Insert the new record
        $sql = "INSERT INTO classes (class_type, class_name, batch_year, class_fees) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssi", $class_type, $class_name, $batch_year, $class_fess);

        if ($stmt->execute()) {
            echo "<script>alert('Class Added Successfully!!'); window.location.href = 'addClass.php';</script>";
        } else {
            echo "<script>alert('Error: " . $stmt->error . "'); window.location.href = 'addClass.php';</script>";
        }

        $stmt->close();
    }

    $check_stmt->close();
    $conn->close();
}
?>

<?php include "./includes/sidebar.php"; ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Add Class</title>
</head>

<body>
    <h1>Add Class</h1>
    <form action="addClass.php" method="post">
        <!-- class_type -->
        <div class="nice-form-group">
            <label for="class-type">Select type of class</label>
            <select onchange="updateClassFields()" id="class-type" name="class_type" required>
                <option value="">Please select a value</option>
                <option value="school">School</option>
                <option value="college">College</option>
                <option value="other">Other</option>
            </select>

        </div>
        <!-- class_name -->
        <div class="nice-form-group" id="class_name">
            <label for="standard-degree">Class Name</label>
            <select id="standard-degree" name="class_name" required>
                <option value="">--Select Standard/Degree--</option>
            </select>
            <input type="text" id="other-class-name" placeholder="Enter Class Name" name="other_class_name" style="display:none;" />
        </div>
        <!-- batch_year -->
        <div class="nice-form-group" id="batch-year">
            <label for="batch-year">Batch Year</label>
            <select id="batch-year" name="batch_year" required>
                <option value="">--Select Year--</option>
                <?php
                for ($year = 2016; $year <= 2024; $year++) {
                    echo "<option value=\"$year\">$year</option>";
                }
                ?>
            </select>
        </div>
        <!-- class_fees  -->
        <div class="nice-form-group" id="batch-year">
            <label for="batch-year">Class Fees/Semester(6 months)</label>
            <input type="number" name="class_fees" id="class_fees" required placeholder="fees of 6 months">
        </div>

        <input class="input-button" type="submit" value="Add Class">
    </form>
    <script src="./js/dynamicContent.js"></script>
</body>

</html>