<?php include "./includes/dbconnection.php" ?>

<?php include "./includes/sidebar.php" ?>
<?php
$number_of_classes = "SELECT COUNT(*) AS total_classes FROM classes";
$result = $conn->query($number_of_classes);
$total_classes = 0;
if ($result) {
    $row = $result->fetch_assoc();
    $total_classes = $row['total_classes'];
} else {
    $error_message = $conn->error;
}

$number_of_students = "SELECT COUNT(*) AS total_students FROM student_info";
$result = $conn->query($number_of_students);
$total_students = 0;
if ($result) {
    $row = $result->fetch_assoc();
    $total_students = $row['total_students'];
} else {
    $error_message = $conn->error;
}

// Query for total pending fees amount
$total_fees_query = "SELECT SUM(fees_pending) AS total_fees_pending FROM student_info WHERE fees_pending > 0";
$result = $conn->query($total_fees_query);
$total_fees_pending = 0;
if ($result) {
    $row = $result->fetch_assoc();
    $total_fees_pending = $row['total_fees_pending'];
} else {
    $error_message = $conn->error;
}

// Query for the number of students with pending fees
$pending_students_query = "SELECT COUNT(*) AS pending_students FROM student_info WHERE fees_pending > 0";
$result = $conn->query($pending_students_query);
$pending_students = 0;
if ($result) {
    $row = $result->fetch_assoc();
    $pending_students = $row['pending_students'];
} else {
    $error_message = $conn->error;
}

$conn->close();
?>

<link rel="stylesheet" href="./css/dashboard.css">
<link rel="stylesheet" href="./css/cards.css">

<!--Begin Main Overview-->
<div class="main-overview">
    <div class="overviewcard g-student">
        <div class="overviewcard__icon">Total Students</div>
        <div class="overviewcard__info"><?php echo htmlspecialchars($total_students); ?></div>
    </div>
    <div class="overviewcard g-class">
        <div class="overviewcard_icon">Total Classes</div>
        <div class="overviewcard__info"><?php echo htmlspecialchars($total_classes); ?></div>
    </div>
    <div class="overviewcard g-requests">
        <div class="overviewcard__icon">Pending Requests</div>
        <div class="overviewcard__info">{number}</div>
    </div>
    <div class="overviewcard g-cfees">
        <div class="overviewcard__icon">Remaining Fees</div>
        <div class="overviewcard__info"><?php echo htmlspecialchars($total_fees_pending); ?></div>
    </div>
    <div class="overviewcard g-sfees">
        <div class="overviewcard__icon">Students with Pending Fees</div>
        <div class="overviewcard__info"><?php echo htmlspecialchars($pending_students); ?></div>
    </div>
</div>
<a href="./includes/resetFees.php">Reset Fees</a>