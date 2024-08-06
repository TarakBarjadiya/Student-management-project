<?php
$current_page = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perennial SMS</title>
    <link rel="shortcut icon" type="image/x-icon" href="./images/favicon.ico">
    <link rel="stylesheet" href="./css/style.css?v=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
</head>

<body>
    <div class="page-header">
        <div class="header-container">
            <div class="logo">
                <p>asf</p>
            </div>
            <div id="navigation-bar" class="nav-bar">
                <a href="./home.php" class="<?= $current_page == 'index.php' ? 'active' : '' ?>">Home</a>
                <div class="dropdown">
                    <a href="javascript:void(0)" class="dropbtn <?= in_array($current_page, ['student/login.php', 'admin/login.php']) ? 'active' : '' ?>" onclick="toggleDropdown()">Login</a>
                    <div class="dropdown-content">
                        <a href="./student/index.php" class="<?= $current_page == 'student/login.php' ? 'active' : '' ?>">Student Login</a>
                        <a href="./admin/index.php" class="<?= $current_page == 'admin/login.php' ? 'active' : '' ?>">Admin Login</a>
                    </div>
                </div>
                <a href="./about.php" class="<?= $current_page == 'about.php' ? 'active' : '' ?>">About</a>
                <a href="./contact.php" class="<?= $current_page == 'contact.php' ? 'active' : '' ?>">Contact</a>
            </div>
            <a id="menu-icon" class="menu-icon" onclick="onMenuClick()">
                <span class="m-icon">menu</span>
            </a>
        </div>
    </div>
    <script src="./js/header.js"></script>