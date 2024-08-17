<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "sms";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Generate and store token if not already set
if (!isset($_SESSION['token'])) {
    $_SESSION['token'] = bin2hex(random_bytes(32));
}

$classTypes = [];
$sql = "SELECT DISTINCT class_type FROM classes";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $classTypes[] = $row['class_type'];
    }
}

// Fetch classes based on selected type
if (isset($_GET['class_type'])) {
    $classType = $_GET['class_type'];
    $sql = "SELECT id, class_name, batch_year, class_fees FROM classes WHERE class_type = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $classType);
    $stmt->execute();
    $result = $stmt->get_result();
    $classes = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $classes[] = $row;
        }
    }
    $stmt->close();

    // Return JSON response
    header('Content-Type: application/json');
    echo json_encode($classes);
    exit(); // Exit to avoid outputting other content
}
$conn->close();
?>

<?php include "./includes/sidebar.php" ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Registration</title>
    <script defer src="./js/state-cities.js"></script>
    <script defer src="./js/dynamicClassSelection.js"></script>
    <script defer src="./js/inputValidation.js"></script>
</head>

<body>
    <h1>Student Registration</h1>
    <?php if (isset($message)) : ?>
        <p><?php echo htmlspecialchars($message); ?></p>
    <?php endif; ?>

    <form action="handleAddStudent.php" method="post">
        <input type="hidden" name="token" value="<?php echo htmlspecialchars($_SESSION['token']); ?>">

        <!-- ---------------------- Class Selection ------------------------- -->

        <h2>Academic Details</h2>

        <div class="nice-form-group">
            <label for="class_type">Class Type:</label>
            <select id="class_type" name="class_type" onchange="updateClasses()" required>
                <option value="">Select Class Type</option>
                <?php foreach ($classTypes as $type) : ?>
                    <option value="<?php echo htmlspecialchars($type); ?>"><?php echo htmlspecialchars($type); ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="nice-form-group">
            <label for="class_name">Class Name:</label>
            <select id="class_name" name="class_name" required>
                <option value="">Select Class Name</option>
                <!-- Options populated via JavaScript -->
            </select>
        </div>

        <div class="nice-form-group">
            <label for="class_fees">Class Fees/Semester (6 months):</label>
            <input type="text" id="class_fees" name="class_fees" placeholder="class fees" readonly required >
        </div>

        <!-- ---------------------- Personal Details ------------------------- -->

        <h2>Personal Details</h2>

        <div class="nice-form-group">
            <label for="first_name">First Name:</label>
            <input type="text" id="first_name" name="first_name" required>
        </div>

        <div class="nice-form-group">
            <label for="middle_name">Middle Name:</label>
            <input type="text" id="middle_name" name="middle_name" required>
        </div>

        <div class="nice-form-group">
            <label for="last_name">Last Name:</label>
            <input type="text" id="last_name" name="last_name" required>
        </div>

        <div class="nice-form-group">
            <label for="gender">Gender:</label>
            <div class="gender-select">
                <input type="radio" id="male" name="gender" value="Male" required>
                <label for="male">Male</label>
                <input type="radio" id="female" name="gender" value="Female" required>
                <label for="female">Female</label>
            </div>
            </select>
        </div>

        <div class="nice-form-group">
            <label for="dob">Date of Birth:</label>
            <input type="date" id="dob" name="dob" required min="1980-01-01" max="<?php echo date('Y-m-d', strtotime('-5 years')); ?>">
        </div>

        <!-- ---------------------- Contact Details ------------------------- -->

        <h2>Contact Details</h2>

        <!-- --------Address start------- -->

        <div class="nice-form-group">
            <label for="house_no">House no. / Street no.:</label>
            <input type="text" id="house_no" name="house_no" required>
        </div>

        <div class="nice-form-group">
            <label for="address_line2">Address Line 2:</label>
            <input type="text" id="address_line2" name="address_line2" required>
        </div>

        <div class="nice-form-group">
            <label for="address_line3">Address Line 3:</label>
            <input type="text" id="address_line3" name="address_line3" required>
        </div>

        <div class="nice-form-group">
            <label for="state">State:</label>
            <select type="text" class="limited-dropdown" id="state_id" name="state" onchange="print_city('city_id', this.selectedIndex);" required></select>
        </div>

        <div class="nice-form-group">
            <label for="city">City:</label>
            <select type="text" class="limited-dropdown" id="city_id" name="city" required></select>
        </div>

        <div class="nice-form-group">
            <label for="postal_code">Postal Code:</label>
            <input type="tel" maxlength="6" id="postal_code" name="postal_code" oninput="validatePostalCode()" required>
        </div>

        <!-- --------Address end------- -->

        <div class="nice-form-group">
            <label for="mobile">Mobile:</label>
            <input type="text" id="mobile" name="mobile" maxlength="10" oninput="validateMobileNumber()" required>
        </div>

        <div class="nice-form-group">
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>
        </div>

        <!-- ---------------------- Parents Details ------------------------- -->

        <h2>Parents / Guardians Information</h2>

        <div class="nice-form-group">
            <label for="father_name">Father's Name:</label>
            <input type="text" id="father_name" name="father_name" required>
        </div>

        <div class="nice-form-group">
            <label for="father_contact">Father's Contact:</label>
            <input type="text" id="father_contact" name="father_contact" oninput="validateMobileNumber()" required>
        </div>

        <div class="nice-form-group">
            <label for="mother_name">Mother's Name:</label>
            <input type="text" id="mother_name" name="mother_name" required>
        </div>

        <div class="nice-form-group">
            <label for="mother_contact">Mother's Contact:</label>
            <input type="text" id="mother_contact" name="mother_contact" oninput="validateMobileNumber()" required>
        </div>

        <!-- ---------------------- Submit Buttons ------------------------- -->

        <input class="input-button" type="reset" value="Reset">
        <input class="input-button" type="submit" value="Submit">

    </form>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Populate states dropdown and set previously selected state
            print_state();
        });
    </script>
</body>

</html>