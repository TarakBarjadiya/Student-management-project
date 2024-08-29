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

// Get form data
$student_id = isset($_POST['student_id']) ? (int)$_POST['student_id'] : 0;
$studentStatus = $_POST['stud_status'] ?? '';
$classId = $_POST['class_name'] ?? '';
$firstName = $_POST['first_name'] ?? '';
$middleName = $_POST['middle_name'] ?? '';
$lastName = $_POST['last_name'] ?? '';
$gender = $_POST['gender'] ?? '';
$dob = $_POST['dob'] ?? '';
$house_no = $_POST['house_no'] ?? '';
$address_2 = $_POST['address_line2'] ?? '';
$address_3 = $_POST['address_line3'] ?? '';
$state = $_POST['state_id'] ?? '';
$city = $_POST['city'] ?? '';
$postal_code = $_POST['postal_code'] ?? '';
$email = $_POST['email'] ?? '';
$fatherName = $_POST['father_name'] ?? '';
$motherName = $_POST['mother_name'] ?? '';
$mobile = trim($_POST['mobile'] ?? '');
$fatherContact = trim($_POST['father_contact'] ?? '');
$motherContact = trim($_POST['mother_contact'] ?? '');

// Format address
$address = $house_no . ', ' . $address_2 . ', ' . $address_3 . ', ' . $city . ', ' . $state . ' - ' . $postal_code;

// Fetch the original class ID, fees pending, and amount paid from the database
$sql = "SELECT class_id, fees_pending, fees_paid FROM student_info WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $student_id);
$stmt->execute();
$stmt->bind_result($originalClassId, $originalFeesPending, $amountPaid);
$stmt->fetch();
$stmt->close();

// Fetch the class fees for the selected class
$sql = "SELECT class_fees FROM classes WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $classId);
$stmt->execute();
$stmt->bind_result($classFees);
$stmt->fetch();
$stmt->close();

// Determine the new fees pending value
if ($originalClassId != $classId) {
    // If the class has changed, calculate the new fees pending
    $feesPending = $classFees - $amountPaid;
    // Ensure feesPending is not less than 0
    $feesPending = max($feesPending, 0);
} else {
    // If the class has not changed, keep the original fees pending
    $feesPending = $originalFeesPending;
}

// Update student information in the database
$sql = "
    UPDATE student_info
    SET stud_status = ?,
        class_id = ?,
        first_name = ?,
        middle_name = ?,
        last_name = ?,
        gender = ?,
        stud_dob = ?,
        stud_address = ?,
        house_no = ?,
        address_2 = ?,
        address_3 = ?,
        state = ?,
        city = ?,
        postal_code = ?,
        stud_mobile = ?,
        stud_email = ?,
        father_name = ?,
        father_contact = ?,
        mother_name = ?,
        mother_contact = ?,
        fees_pending = ?
    WHERE id = ?
";

$stmt = $conn->prepare($sql);
$stmt->bind_param(
    'sissssssssssssssssssii',
    $studentStatus,
    $classId,
    $firstName,
    $middleName,
    $lastName,
    $gender,
    $dob,
    $address,
    $house_no,
    $address_2,
    $address_3,
    $state,
    $city,
    $postal_code,
    $mobile,
    $email,
    $fatherName,
    $fatherContact,
    $motherName,
    $motherContact,
    $feesPending,
    $student_id
);

if ($stmt->execute()) {
    $_SESSION['message'] = "Student details updated successfully.";
} else {
    $_SESSION['message'] = "Failed to update student details: " . $stmt->error;
}

$stmt->close();
$conn->close();

// Redirect back to the edit page with a message
header("Location: manageStudents.php");
exit();
?>