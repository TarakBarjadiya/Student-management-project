<?php
$current_page = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perennial Student Management System</title>
    <link rel="shortcut icon" type="image/x-icon" href="./images/favicon.ico">
    <link rel="stylesheet" href="./css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
</head>

<body>
    <div class="page-header">
        <div class="header-container">
            <div class="logo">
                <a href="./home.php" style="color: white;">Perennial SMS</a>
            </div>
            <button class="menu-toggle" onclick="toggleSidebar()">☰</button> <!-- Menu Toggle Button -->
            <div class="nav-bar">
                <a href="./home.php" class="<?= $current_page == 'home.php' ? 'active' : '' ?>">Home</a>
                <a href="./about.php" class="<?= $current_page == 'about.php' ? 'active' : '' ?>">About</a>
                <a href="./contact.php" class="<?= $current_page == 'contact.php' ? 'active' : '' ?>">Contact</a>
                <div class="dropdown">
                    <a href="javascript:void(0)" class="login-button" onclick="toggleDropdown()">Login</a>
                    <div class="dropdown-content">
                        <a href="./student/login.php">Student Login</a>
                        <a href="./admin/login.php">Admin Login</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="sidebar" class="sidebar">
        <button class="close-btn" onclick="toggleSidebar()">×</button>
        <div class="sidebar-content">
            <a href="./home.php">Home</a>
            <a href="./about.php">About</a>
            <a href="./contact.php">Contact</a>
            <a href="./student/login.php">Student Login</a>
            <a href="./admin/login.php">Admin Login</a>
        </div>
    </div>
    <script src="./js/header.js"></script>
</body>

</html>