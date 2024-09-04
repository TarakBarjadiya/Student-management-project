<?php include "./includes/dbconnection.php" ?>
<?php include "./includes/getSession.php"; ?>

<?php
$current_page = basename($_SERVER['PHP_SELF']);
?>

<title>Student Dashboard</title>
<link rel="stylesheet" href="./css/sidebar.css">
<div class='dashboard'>

    <!-- navbar -->
    <div class="dashboard-nav">
        <header class="collapsed-header">
            <a href="#!" class="menu-toggle">
                <span class="m-icon">menu</span>
            </a>
            <a href="#" class="brand-logo">
                <span>XYZ Name</span>
            </a>
        </header>
        <nav class="dashboard-nav-list">

            <!-- dashboard menu -->
            <a href="./dashboard.php" class="dashboard-nav-item <?php echo $current_page == 'dashboard.php' ? 'active' : ''; ?>">
                <span class="nav-m-icon">dashboard</span>
                <span>Dashboard</span>
            </a>

            <a href="./myProfile.php" class="dashboard-nav-item <?php echo $current_page == 'myProfile.php' ? 'active' : ''; ?>">
                <span class="nav-m-icon">account_circle</span>
                <span>My Profile</span>
            </a>
            
            <a href="./payFees.php" class="dashboard-nav-item <?php echo $current_page == 'payFees.php' ? 'active' : ''; ?>">
                <span class="nav-m-icon">universal_currency_alt</span>
                <span>Pay Fees</span>
            </a>

            <a href="./notifications.php" class="dashboard-nav-item <?php echo $current_page == 'notifications.php' ? 'active' : ''; ?>">
                <span class="nav-m-icon">notifications</span>
                <span>Notifications</span>
            </a>

            <a href="./dashboard.php" class="dashboard-nav-item <?php echo $current_page == 'classNotices.php' ? 'active' : ''; ?>">
                <span class="nav-m-icon">auto_stories</span>
                <span>Class Notices</span>
            </a>
            
            <a href="./dashboard.php" class="dashboard-nav-item <?php echo $current_page == 'sendRequest.php' ? 'active' : ''; ?>">
                <span class="nav-m-icon">send</span>
                <span>Send Request</span>
            </a>

            <!-- logout menu -->
            <div class="nav-item-divider"></div>
            <a href="./logout.php" class="dashboard-nav-item">
                <span class="nav-m-icon">logout</span>
                <span>Logout</span>
            </a>

        </nav>
    </div>

    <!-- app -->
    <div class='dashboard-app'>
        <!-- app header -->
        <header class='dashboard-toolbar'>
            <a href="#!" class="menu-toggle">
                <span class="m-icon">menu</span>
            </a>
            <div style="display: flex; align-items: center;">
                <div style="margin-right: 5px;">Logged in as <?php echo htmlspecialchars($first_name . ' ' .$middle_name . ' ' .$last_name); ?></div>
            </div>
        </header>
        <!-- sidebar javascript -->
        <script src="./js/sidebar.js"></script>

        <!-- app content -->
        <div class='dashboard-content'>