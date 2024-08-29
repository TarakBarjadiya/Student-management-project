<?php include "./includes/sidebar.php"; ?>
<?php

$userId = $_SESSION['id'];

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
    $admin_name = $_POST['admin_name'];
    $admin_email = $_POST['admin_email'];

    // Update admin details
    $sql = "UPDATE admins SET admin_name = ?, admin_email = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssi", $admin_name, $admin_email, $userId);

    if ($stmt->execute()) {
        $_SESSION['update_success'] = true;
    } else {
        $_SESSION['update_error'] = $stmt->error;
    }

    $stmt->close();
}

// Fetch current admin details
$sql = "SELECT admin_name, admin_email FROM admins WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $userId);
$stmt->execute();
$stmt->bind_result($admin_name, $admin_email);
$stmt->fetch();
$stmt->close();
$conn->close();
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile</title>
</head>

<body>
    <h1>My Profile</h1>
    <?php
    if (isset($_SESSION['update_success'])) {
        echo "<p style='color:green;'>Profile updated successfully.</p>";
        unset($_SESSION['update_success']);
    }
    if (isset($_SESSION['update_error'])) {
        echo "<p style='color:red;'>Error updating profile: " . $_SESSION['update_error'] . "</p>";
        unset($_SESSION['update_error']);
    }
    ?>
    <form action="myProfile.php" method="post">
        <div class="nice-form-group">
            <label for="admin_name">Name:</label>
            <input type="text" id="admin_name" name="admin_name" value="<?php echo htmlspecialchars($admin_name); ?>" required><br>
        </div>

        <div class="nice-form-group">
            <label for="admin_email">Email:</label>
            <input type="email" id="admin_email" name="admin_email" value="<?php echo htmlspecialchars($admin_email); ?>" required><br>
        </div>

        <input class="input-button" type="submit" value="Update Profile">
    </form>
</body>

</html>