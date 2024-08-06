<?php
// Connect to the database
include './includes/dbconnection.php';

$table = $_GET['table'] ?? '';
$id = $_GET['id'] ?? '';

if (!$table || !$id) {
    die('Table name or ID not specified.');
}

// Sanitize table name to prevent SQL injection
$table = preg_replace('/[^a-zA-Z0-9_]/', '', $table);

// Fetch the record by ID
$query = $conn->prepare("SELECT class_name, class_type, batch_year FROM $table WHERE id = ?");
$query->bind_param('i', $id);
$query->execute();
$result = $query->get_result();
$row = $result->fetch_assoc();

if (!$row) {
    die('Record not found.');
}

$classType = $row['class_type'];
$className = $row['class_name'];
$batchYear = $row['batch_year'];

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $class_name = $_POST['class_type'] === 'other' ? $_POST['other_class_name'] : $_POST['class_name'];
    $class_type = $_POST['class_type'];
    $batch_year = $_POST['batch_year'];

    $updateQuery = "UPDATE $table SET class_name = ?, class_type = ?, batch_year = ? WHERE id = ?";
    $stmt = $conn->prepare($updateQuery);
    $stmt->bind_param('ssii', $class_name, $class_type, $batch_year, $id);

    if ($stmt->execute()) {
        echo "<script>alert('Class Details Edited Successfully!!'); window.location.href = 'manageClass.php';</script>";
    } else {
        echo "<script>alert('Error: " . $stmt->error . "'); window.location.href = 'editClass.php';</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Edit Record</title>
    <link rel="stylesheet" href="css/style.css">
    <script src="js/sidebar.js" defer></script>
    <script>
        const degrees = ['BCA', 'BCom', 'BBA', 'BA', 'BSc', 'BTech'];
        const standards = [
            'Class 1',
            'Class 2',
            'Class 3',
            'Class 4',
            'Class 5',
            'Class 6',
            'Class 7',
            'Class 8',
            'Class 9',
            'Class 10',
            'Class 11',
            'Class 12',
        ];

        function updateClassFields() {
            const classType = document.getElementById('class-type').value;
            const classNameSelect = document.getElementById('standard-degree');
            const otherClassNameInput = document.getElementById('other-class-name');

            if (classType === 'Other') {
                classNameSelect.style.display = 'none';
                otherClassNameInput.style.display = 'block';
                otherClassNameInput.required = true;
                classNameSelect.required = false;
                otherClassNameInput.value = "<?= $className ?>";
                return;
            }

            classNameSelect.style.display = 'block';
            otherClassNameInput.style.display = 'none';
            otherClassNameInput.required = false;
            classNameSelect.required = true;

            // Clear existing options
            classNameSelect.innerHTML = '<option value="">--Select Standard/Degree--</option>';

            let options = [];
            if (classType === 'School') {
                options = standards;
            } else if (classType === 'College') {
                options = degrees;
            }

            options.forEach(option => {
                const opt = document.createElement('option');
                opt.value = option;
                opt.text = option;
                classNameSelect.add(opt);
            });

            // Set the selected class name
            const currentClassName = "<?= $className ?>";
            classNameSelect.value = currentClassName;
        }

        document.addEventListener('DOMContentLoaded', () => {
            const classType = "<?= $classType ?>";
            document.getElementById('class-type').value = classType;
            updateClassFields();
        });
    </script>
</head>

<body>
    <?php include "./includes/sidebar.php" ?>
    <h1>Edit Record</h1>
    <form method="post">
        <div class="nice-form-group">
            <label for="class-type">Select type of class</label>
            <select id="class-type" name="class_type" required onchange="updateClassFields()">
                <option value="">Please select a value</option>
                <option value="School" <?= $classType === 'School' ? 'selected' : '' ?>>School</option>
                <option value="College" <?= $classType === 'College' ? 'selected' : '' ?>>College</option>
                <option value="Other" <?= $classType === 'Other' ? 'selected' : '' ?>>Other</option>
            </select>
        </div>

        <!-- class_name -->
        <div class="nice-form-group" id="class_name">
            <label for="standard-degree">Class Name</label>
            <select id="standard-degree" name="class_name" required>
                <option value="">--Select Standard/Degree--</option>
                <!-- Options populated by JavaScript -->
            </select>
            <input type="text" id="other-class-name" name="other_class_name" placeholder="Enter Class Name" style="display:none;" />
        </div>

        <!-- batch_year -->
        <div class="nice-form-group">
            <label for="batch-year">Batch Year</label>
            <select id="batch-year" name="batch_year" required>
                <option value="">--Select Year--</option>
                <?php
                for ($year = 2016; $year <= 2024; $year++) {
                    $selected = $year == $batchYear ? 'selected' : '';
                    echo "<option value=\"$year\" $selected>$year</option>";
                }
                ?>
            </select>
        </div>

        <div class="submit-cancel">
            <input class="input-button" type="submit" value="Save Changes">
            <a class="link-none" href="./manageClass.php">Cancel</a>
        </div>
    </form>

</body>

</html>