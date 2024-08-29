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

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $classId = $_POST['class_name'];
    $firstName = $_POST['first_name'];
    $middleName = $_POST['middle_name'];
    $lastName = $_POST['last_name'];
    $gender = $_POST['gender'];
    $dob = $_POST['dob'];
    $address = $_POST['house_no'] . ', ' . $_POST['address_line2'] . ', ' . $_POST['address_line3'] . ', ' . $_POST['city'] . ', ' . $_POST['state'] . ' - ' . $_POST['postal_code'];
    $house_no = $_POST['house_no'];
    $address_2 = $_POST['address_line2'];
    $address_3 = $_POST['address_line3'];
    $state = $_POST['state'];
    $city = $_POST['city'];
    $postal_code = $_POST['postal_code'];
    $email = $_POST['email'];
    $fatherName = $_POST['father_name'];
    $motherName = $_POST['mother_name'];
    $mobile = trim($_POST['mobile']);
    $fatherContact = trim($_POST['father_contact']);
    $motherContact = trim($_POST['mother_contact']);
    $classFees = $_POST['class_fees'];

    // Check for duplicate email
    $sql = "SELECT COUNT(*) FROM student_info WHERE stud_email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->bind_result($count);
    $stmt->fetch();
    $stmt->close();

    if ($count > 0) {
        echo "<script>alert('A student with email $email already exists!'); window.location.href = 'addStudent.php';</script>";
        exit;
    } else {
        $enrollmentNumber = generateEnrollmentNumber($conn);

        // Prepare SQL statement to insert student information
        $sql = "INSERT INTO student_info (enrollment_number, class_id, first_name, middle_name, last_name, gender, stud_dob, stud_address, house_no, address_2, address_3, state, city, postal_code, stud_mobile, stud_email, father_name, father_contact, mother_name, mother_contact, fees_pending, stud_status) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?, 'on roll')";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sissssssssssssssssssi", $enrollmentNumber, $classId, $firstName, $middleName, $lastName, $gender, $dob, $address, $house_no, $address_2, $address_3, $state, $city, $postal_code, $mobile, $email, $fatherName, $fatherContact, $motherName, $motherContact, $classFees);

        if ($stmt->execute()) {
            // Generate password in the format FirstName@DDMMYYYY
            $dobFormatted = date('dmY', strtotime($dob));
            $password = $firstName . '@' . $dobFormatted;

            // Hash the password before storing it
            $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

            // Get the last inserted student ID from the connection
            $studentId = $conn->insert_id;

            // Insert into student_login table
            $sqlLogin = "INSERT INTO student_login (student_id, password, failed_attempts, last_login) VALUES (?, ?, 0, NULL)";
            $stmtLogin = $conn->prepare($sqlLogin);
            $stmtLogin->bind_param("is", $studentId, $hashedPassword);

            if ($stmtLogin->execute()) {
                echo "<script>alert('Student Added Successfully!!'); window.location.href = 'addStudent.php';</script>";
            } else {
                echo "<script>alert('Error adding login details: " . $stmtLogin->error . "'); window.location.href = 'addStudent.php';</script>";
            }

            $stmtLogin->close();
        } else {
            echo "<script>alert('Error: " . $stmt->error . "'); window.location.href = 'addStudent.php';</script>";
        }

        $stmt->close();
    }
}

$conn->close();

// Function to generate the next enrollment number
function generateEnrollmentNumber($conn)
{
    $sql = "SELECT MAX(enrollment_number) AS last_enrollment FROM student_info";
    $result = $conn->query($sql);
    $lastEnrollment = $result->fetch_assoc()['last_enrollment'];

    if ($lastEnrollment) {
        $lastNumber = (int)substr($lastEnrollment, 4);
        $nextNumber = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
        return "STUD" . $nextNumber;
    } else {
        return "STUD0001";
    }
}
?>
