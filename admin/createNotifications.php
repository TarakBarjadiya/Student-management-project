<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "sms";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Initialize variables
$message = "";

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Fetch the data from the form
    $student_id = isset($_POST['student_id']) ? (int)$_POST['student_id'] : 0;
    $title = $_POST['notification_title'] ?? '';
    $description = $_POST['notification_description'] ?? '';

    // Insert into the database
    $sql = "INSERT INTO student_notifications (student_id, notification_title, notification_description) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('iss', $student_id, $title, $description);

    if ($stmt->execute()) {
        echo "<script>alert('Notification Sent Successfully!!'); window.location.href = 'notifications.php';</script>";
    } else {
        $message = "Failed to create notification: " . $stmt->error;
    }

    $stmt->close();
}

// Fetch classes
$classQuery = "SELECT id, class_name FROM classes";
$classResult = $conn->query($classQuery);
$classes = $classResult->fetch_all(MYSQLI_ASSOC);

// Close the connection
$conn->close();
?>

<?php include "./includes/sidebar.php" ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Notification</title>
</head>

<body>
    <h1>Send Notification</h1>


    <form action="" method="POST">
        <div class="nice-form-group">
            <label for="class_select">Select Class:</label>
            <select id="class_select" name="class_id" required>
                <option value="">Select Class</option>
            </select>
        </div>

        <div class="nice-form-group">
            <label for="student_select">Select Student:</label>
            <select id="student_select" name="student_id" required>
                <option value="">Select Student</option>
            </select>
        </div>

        <div class="nice-form-group">
            <label for="notification_title">Title:</label>
            <input type="text" name="notification_title" id="notification_title" required>
        </div>

        <div class="nice-form-group">
            <label for="notification_description">Description:</label>
            <textarea name="notification_description" style="resize: vertical;" id="notification_description" required></textarea>
        </div>

        <button class="input-button" id="search-button" onclick="window.location.href='notifications.php';">Back</button>
        <button class="input-button" type="submit">Send Notification</button>
    </form>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Fetch and populate classes
            fetch('./includes/getClasses.php')
                .then(response => response.json())
                .then(data => {
                    const classSelect = document.getElementById('class_select');
                    data.forEach(classItem => {
                        const option = document.createElement('option');
                        option.value = classItem.id;
                        // Display both class_name and batch_year
                        option.textContent = `${classItem.class_name} (${classItem.batch_year})`;
                        classSelect.appendChild(option);
                    });
                });

            // Fetch students when a class is selected
            document.getElementById('class_select').addEventListener('change', function() {
                const classId = this.value;
                const studentSelect = document.getElementById('student_select');

                // Clear previous options
                studentSelect.innerHTML = '<option value="">Select Student</option>';

                if (classId) {
                    fetch(`./includes/getStudents.php?class_id=${classId}`)
                        .then(response => response.json())
                        .then(data => {
                            data.forEach(student => {
                                const option = document.createElement('option');
                                option.value = student.id;
                                option.textContent = student.student_name;
                                studentSelect.appendChild(option);
                            });
                        });
                }
            });
        });
    </script>
</body>

</html>