<?php
session_start();

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

// Get student ID from URL
$student_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$selectedClassType = '';
$selectedClassNameId = '';
$selectedState = '';
$selectedCity = '';

if ($student_id) {
    $sql = "
        SELECT si.*, c.class_type, si.class_id AS selected_class_id, si.state AS selected_state, si.city AS selected_city
        FROM student_info si
        JOIN classes c ON si.class_id = c.id
        WHERE si.id = ?
    ";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $student_id);
    $stmt->execute();
    $student = $stmt->get_result()->fetch_assoc();
    $selectedClassType = $student['class_type'];
    $selectedClassNameId = $student['selected_class_id'];
    $selectedState = $student['selected_state'];
    $selectedCity = $student['selected_city'];
    $stmt->close();
}

// Fetch class types
$classTypes = [];
$sql = "SELECT DISTINCT class_type FROM classes";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $classTypes[] = $row['class_type'];
    }
}

// Fetch states for the dropdown
$states = [];
$sql = "SELECT DISTINCT state FROM student_info";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $states[] = $row['state'];
    }
}

// Fetch classes based on selected type for AJAX requests
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
    header('Content-Type: application/json');
    echo json_encode($classes);
    exit();
}

// Fetch cities based on selected state for AJAX requests
if (isset($_GET['state'])) {
    $state = $_GET['state'];
    $sql = "SELECT DISTINCT city FROM student_info WHERE state = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $state);
    $stmt->execute();
    $result = $stmt->get_result();
    $cities = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $cities[] = $row['city'];
        }
    }
    $stmt->close();
    header('Content-Type: application/json');
    echo json_encode($cities);
    exit();
}

$conn->close();
?>

<?php include "./includes/sidebar.php" ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Student</title>
    <script defer src="./js/inputValidation.js"></script>
    <script defer src="./js/state-cities.js"></script>
</head>

<body>
    <h1>Edit Student</h1>
    <?php if (isset($message)) : ?>
        <p><?php echo htmlspecialchars($message); ?></p>
    <?php endif; ?>

    <form action="handleEditStudent.php" method="post">
        <input type="hidden" name="token" value="<?php echo htmlspecialchars($_SESSION['token']); ?>">
        <input type="hidden" name="student_id" value="<?php echo htmlspecialchars($student_id); ?>">

        <!-- ---------------------- Class Selection ------------------------- -->

        <h2>Academic Details</h2>

        <div class="nice-form-group">
            <label for="class_type">Class Type:</label>
            <select id="class_type" name="class_type" onchange="updateClasses()" required>
                <option value="">Select Class Type</option>
                <?php foreach ($classTypes as $type) : ?>
                    <option value="<?php echo htmlspecialchars($type); ?>" <?php echo ($type === $selectedClassType) ? 'selected' : ''; ?>><?php echo htmlspecialchars($type); ?></option>
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

        <!-- ---------------------- Personal Details ------------------------- -->

        <h2>Personal Details</h2>

        <div class="nice-form-group">
            <label for="first_name">First Name:</label>
            <input type="text" id="first_name" name="first_name" value="<?php echo htmlspecialchars($student['first_name']); ?>" required>
        </div>

        <div class="nice-form-group">
            <label for="middle_name">Middle Name:</label>
            <input type="text" id="middle_name" name="middle_name" value="<?php echo htmlspecialchars($student['middle_name']); ?>" required>
        </div>

        <div class="nice-form-group">
            <label for="last_name">Last Name:</label>
            <input type="text" id="last_name" name="last_name" value="<?php echo htmlspecialchars($student['last_name']); ?>" required>
        </div>

        <div class="nice-form-group">
            <label for="gender">Gender:</label>
            <div class="gender-select">
                <input type="radio" id="male" name="gender" value="Male" <?php echo ($student['gender'] == 'Male') ? 'checked' : ''; ?> required>
                <label for="male">Male</label>
                <input type="radio" id="female" name="gender" value="Female" <?php echo ($student['gender'] == 'Female') ? 'checked' : ''; ?> required>
                <label for="female">Female</label>
            </div>
        </div>

        <div class="nice-form-group">
            <label for="dob">Date of Birth:</label>
            <input type="date" id="dob" name="dob" value="<?php echo htmlspecialchars($student['stud_dob']); ?>" required min="1980-01-01" max="<?php echo date('Y-m-d', strtotime('-5 years')); ?>">
        </div>

        <!-- ---------------------- Contact Details ------------------------- -->

        <h2>Contact Details</h2>

        <div class="nice-form-group">
            <label for="house_no">House no. / Street no.:</label>
            <input type="text" id="house_no" name="house_no" value="<?php echo htmlspecialchars($student['house_no']); ?>" required>
        </div>

        <div class="nice-form-group">
            <label for="address_line2">Address Line 2:</label>
            <input type="text" id="address_line2" name="address_line2" value="<?php echo htmlspecialchars($student['address_2']); ?>" required>
        </div>

        <div class="nice-form-group">
            <label for="address_line3">Address Line 3:</label>
            <input type="text" id="address_line3" name="address_line3" value="<?php echo htmlspecialchars($student['address_3']); ?>" required>
        </div>

        <div class="nice-form-group">
            <label for="state">State:</label>
            <select id="state_id" name="state_id" required>
                <option value="">Select State</option>
                <?php foreach ($states as $state) : ?>
                    <option value="<?php echo htmlspecialchars($state); ?>" <?php echo ($state === $selectedState) ? 'selected' : ''; ?>><?php echo htmlspecialchars($state); ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="nice-form-group">
            <label for="city">City:</label>
            <select id="city_id" name="city" required>
                <option value="">Select City</option>
                <!-- Options populated via JavaScript -->
            </select>
        </div>

        <div class="nice-form-group">
            <label for="postal_code">Postal Code:</label>
            <input type="text" id="postal_code" name="postal_code" value="<?php echo htmlspecialchars($student['postal_code']); ?>" required>
        </div>

        <div class="nice-form-group">
            <label for="mobile">Mobile:</label>
            <input type="text" id="mobile" name="mobile" value="<?php echo htmlspecialchars($student['stud_mobile']); ?>" required>
        </div>

        <div class="nice-form-group">
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($student['stud_email']); ?>" required>
        </div>

        <!-- ---------------------- Parent Details ------------------------- -->

        <h2>Parent Details</h2>

        <div class="nice-form-group">
            <label for="father_name">Father's Name:</label>
            <input type="text" id="father_name" name="father_name" value="<?php echo htmlspecialchars($student['father_name']); ?>" required>
        </div>

        <div class="nice-form-group">
            <label for="father_contact">Father's Contact:</label>
            <input type="text" id="father_contact" name="father_contact" value="<?php echo htmlspecialchars($student['father_contact']); ?>" required>
        </div>

        <div class="nice-form-group">
            <label for="mother_name">Mother's Name:</label>
            <input type="text" id="mother_name" name="mother_name" value="<?php echo htmlspecialchars($student['mother_name']); ?>" required>
        </div>

        <div class="nice-form-group">
            <label for="mother_contact">Mother's Contact:</label>
            <input type="text" id="mother_contact" name="mother_contact" value="<?php echo htmlspecialchars($student['mother_contact']); ?>" required>
        </div>

        <button type="submit" class="input-button">Save Changes</button>
        <button type="button" class="input-button" onclick="handleBack()">Back</button>
    </form>

    <script>
        function handleBack(){
            window.location.href = 'manageStudents.php';
        }
        function updateClasses() {
            const classType = document.getElementById('class_type').value;
            const classNameSelect = document.getElementById('class_name');

            if (classType) {
                fetch(`?class_type=${classType}`)
                    .then(response => response.json())
                    .then(data => {
                        classNameSelect.innerHTML = '<option value="">Select Class Name</option>';
                        data.forEach(classItem => {
                            const option = document.createElement('option');
                            option.value = classItem.id;
                            option.textContent = `${classItem.class_name} (${classItem.batch_year}) (₹ ${classItem.class_fees}/-)`;
                            classNameSelect.appendChild(option);
                        });
                        // Set the selected class name if available
                        <?php if ($selectedClassNameId) : ?>
                            classNameSelect.value = "<?php echo $selectedClassNameId; ?>";
                        <?php endif; ?>
                    })
                    .catch(error => console.error('Error:', error));
            } else {
                classNameSelect.innerHTML = '<option value="">Select Class Name</option>';
            }
        }
        updateClasses();

        document.addEventListener("DOMContentLoaded", function() {
            // Populate states dropdown and set previously selected state
            print_state();
            const stateSelect = document.getElementById('state_id');
            const citySelect = document.getElementById('city_id');

            const selectedState = "<?php echo htmlspecialchars($selectedState); ?>";

            const selectedCity = "<?php echo htmlspecialchars($selectedCity); ?>";

            // Function to fetch cities based on the selected state
            function fetchCities(stateIndex) {
                fetch(`?state=${state_arr[stateIndex]}`) // Adjust index for the 0-based array
                    .then(response => response.json())
                    .then(data => {
                        print_city('city_id', stateIndex + 1); // Adjust index for the 0-based array
                        // Set previously selected city
                        if (selectedCity) {
                            citySelect.value = selectedCity;
                        }
                    })
                    .catch(error => console.error('Error:', error));
            }

            // Set previously selected state
            if (selectedState) {
                stateSelect.value = selectedState;
                // Find the index of the selected state and adjust for 0-based index
                const stateIndex = state_arr.indexOf(selectedState);
                if (stateIndex > 0) {
                    fetchCities(stateIndex);
                }
            }

            // Update cities when the state changes
            stateSelect.addEventListener('change', function() {
                const stateIndex = state_arr.indexOf(stateSelect.value); // Adjust for 0-based index
                if (stateIndex > 0) {
                    fetchCities(stateIndex);
                }
            });
        });
    </script>
</body>

</html>