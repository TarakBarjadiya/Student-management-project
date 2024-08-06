<?php
include './dbconnection.php';

$name = 'admin';
$email = 'admin@login.com';
$password = 'Admin@Login';

$passwordHash = password_hash($password, PASSWORD_DEFAULT);

$stmt = $conn->prepare("INSERT INTO admins (admin_name, admin_email, password_hash) VALUES (?, ?, ?)");
$stmt->bind_param('sss', $name, $email, $passwordHash);
$stmt->execute();
if ($stmt->error) {
    echo "failed";
} else {
    echo "success";
}
?>