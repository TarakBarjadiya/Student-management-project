<?php include "./includes/dbconnection.php" ?>
<?php
session_start();
if (!isset($_SESSION['id'])) {
    // Redirect to login page if not logged in
    header("Location: login.php");
    exit();
}
$userId = $_SESSION['id'];

$sql = "SELECT admin_name FROM admins WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $userId);
$stmt->execute();
$stmt->bind_result($username);
$stmt->fetch();
$stmt->close();
?>

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
$conn->close();
?>

<link rel="stylesheet" href="./css/dashboard.css">
<link rel="stylesheet" href="./css/cards.css">
<!-- <main class="main"> -->

<div class="main-header">
    <div class="main-header__heading">Hello , <?php echo htmlspecialchars($username); ?></div>
    <!-- <div class="main-header__updates" id="clock"></div> -->
</div> <!--End Main Header-->

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
        <div class="overviewcard__info">{amount}</div>
    </div>
</div>
<!--End Main Overview-->
<!-- <script src="./js/dashboard.js"></script> -->