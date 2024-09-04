<?php include "./includes/sidebar.php"; ?>
<?php include "./includes/dbconnection.php"; ?>

<?php
$student_id = $_SESSION['student_id'];

// Query to fetch student information
$query = "SELECT student_info.*, classes.class_name, classes.batch_year 
    FROM student_info 
    JOIN classes ON student_info.class_id = classes.id 
    WHERE student_info.id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $student_id);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

if ($row) {
?>
    <h1>My Profile</h1>
    <link rel="stylesheet" href="./css/cards.css">
    <div class="card">
        <div class="heading">
            <h1><?php echo $row['first_name'] . ' ' . $row['middle_name'] . ' ' . $row['last_name']; ?></h1>
            <p class="subheading" style="text-transform: capitalize;"><i><?php echo $row['stud_status']; ?></i></p>
            <p class="subheading"><?php echo $row['enrollment_number'] . ' | ' . $row['class_name'] . ' (' . $row['batch_year'] . ')'; ?></p>
            <p class="subheading"><?php echo $row['stud_mobile'] . ' | ' . $row['stud_email']; ?></p>
        </div>
        <div class="details">
            <div class="column">
                <p><strong>Gender</strong>
                <div><?php echo $row['gender']; ?></div>
                </p>
                <p><strong>DOB</strong>
                <div><?php echo $row['stud_dob']; ?></div>
                </p>
                <div class="address">
                    <p><strong>Address</strong>
                    <div><?php echo $row['stud_address']; ?></div>
                    </p>
                </div>
            </div>
            <div class="column">
                <p><strong>Father's Name</strong>
                <div><?php echo $row['father_name']; ?></div>
                </p>
                <p><strong>Mother's Name</strong>
                <div><?php echo $row['mother_name']; ?></div>
                </p>
                <p><strong>Father's Contact</strong>
                <div><?php echo $row['father_contact']; ?></div>
                </p>
                <p><strong>Mother's Contact</strong>
                <div><?php echo $row['mother_contact']; ?></div>
                </p>
            </div>
        </div>
    </div>
<?php
} else {
    echo "<p>No student information found.</p>";
}

// Close the statement and connection
$stmt->close();
$conn->close();
?>
