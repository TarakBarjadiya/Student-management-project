<?php
include './includes/dbconnection.php';

$identifier = $_POST['email'];
$password = $_POST['password'];

$stmt = $conn->prepare("SELECT id, password_hash FROM admins WHERE admin_name = ? OR admin_email = ?");
$stmt->bind_param('ss', $identifier, $identifier);
$stmt->execute();
$result = $stmt->get_result();
$admin = $result->fetch_assoc();

if ($admin && password_verify($password, $admin['password_hash'])) {
    session_start();
    $_SESSION['id'] = $admin['id'];
    header("Location: dashboard.php");
    exit;
} else {
    header('Location: login.php?error=invalid_details');
}
?>