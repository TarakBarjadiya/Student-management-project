<?php include "./includes/dbconnection.php" ?>
<?php include "./includes/getSession.php" ?>

<?php
$current_page = basename($_SERVER['PHP_SELF']);
?>

<title>Admin Dashboard</title>
<link rel="stylesheet" href="./css/sidebar.css">
<div class='dashboard'>

    <!-- navbar -->
    <div class="dashboard-nav">
        <header class="collapsed-header">
            <a href="#!" class="menu-toggle">
                <span class="m-icon">menu</span>
            </a>
            <a href="#" class="brand-logo">
                <span>Perennial</span>
            </a>
            <a href="./myProfile.php" class="sidebar-profile">
                <span class="m-icon">account_circle</span>
            </a>
        </header>
        <nav class="dashboard-nav-list">

            <!-- dashboard menu -->
            <a href="./dashboard.php" class="dashboard-nav-item <?php echo $current_page == 'dashboard.php' ? 'active' : ''; ?>">
                <span class="nav-m-icon">dashboard</span>
                <span>Dashboard</span>
            </a>

            <!-- class menu -->
            <div class='dashboard-nav-dropdown'>
                <a href="#!" class="dashboard-nav-item dashboard-nav-dropdown-toggle <?php echo in_array($current_page, ['addClass.php', 'manageClass.php']) ? 'active' : ''; ?>">
                    <span class="nav-m-icon">meeting_room</span>
                    <span>Classes</span>
                </a>
                <div class='dashboard-nav-dropdown-menu'>
                    <a href="./addClass.php" class="dashboard-nav-dropdown-item <?php echo $current_page == 'addClass.php' ? 'active' : ''; ?>">
                        <span class="nav-m-icon">add_circle</span>
                        <span>Add Class</span>
                    </a>
                    <a href="./manageClass.php" class="dashboard-nav-dropdown-item <?php echo $current_page == 'manageClass.php' ? 'active' : ''; ?>">
                        <span class="nav-m-icon">settings</span>
                        <span>Manage Classes</span>
                    </a>
                </div>
            </div>

            <!-- students menu -->
            <div class='dashboard-nav-dropdown'>
                <a href="#!" class="dashboard-nav-item dashboard-nav-dropdown-toggle <?php echo in_array($current_page, ['addStudent.php', 'manageStudents.php', 'fees.php', 'notifications.php']) ? 'active' : ''; ?>">
                    <span class="nav-m-icon">diversity_3</span>
                    <span>Students</span>
                </a>
                <div class='dashboard-nav-dropdown-menu'>
                    <a href="./addStudent.php" class="dashboard-nav-dropdown-item <?php echo $current_page == 'addStudent.php' ? 'active' : ''; ?>">
                        <span class="nav-m-icon">person_add</span>
                        <span>Add Student</span>
                    </a>
                    <a href="./manageStudents.php" class="dashboard-nav-dropdown-item <?php echo $current_page == 'manageStudents.php' ? 'active' : ''; ?>">
                        <span class="nav-m-icon">manage_accounts</span>
                        <span>Manage Students</span>
                    </a>
                    </a>
                    <a href="./fees.php" class="dashboard-nav-dropdown-item <?php echo $current_page == 'fees.php' ? 'active' : ''; ?>">
                        <span class="nav-m-icon">universal_currency_alt</span>
                        <span>Fees</span>
                    </a>
                    <a href="./notifications.php" class="dashboard-nav-dropdown-item <?php echo $current_page == 'notifications.php' ? 'active' : ''; ?>">
                        <span class="nav-m-icon">notifications</span>
                        <span>Notifications</span>
                    </a>
                </div>
            </div>

            <!-- class notices menu -->
            <div class='dashboard-nav-dropdown'>
                <a href="#!" class="dashboard-nav-item dashboard-nav-dropdown-toggle <?php echo in_array($current_page, ['addClassNotice.php', 'manageClassNotices.php']) ? 'active' : ''; ?>">
                    <span class="nav-m-icon">auto_stories</span>
                    <span>Class Notices</span>
                </a>
                <div class='dashboard-nav-dropdown-menu'>
                    <a href="./addClassNotice.php" class="dashboard-nav-dropdown-item <?php echo $current_page == 'addClassNotice.php' ? 'active' : ''; ?>">
                        <span class="nav-m-icon">box_add</span>
                        <span>Send Notice</span>
                    </a>
                    <a href="./manageClassNotices.php" class="dashboard-nav-dropdown-item <?php echo $current_page == 'manageClassNotices.php' ? 'active' : ''; ?>">
                        <span class="nav-m-icon">box_edit</span>
                        <span>Manage Notices</span>
                    </a>
                </div>
            </div>

            <!-- requests menu -->
            <a href="./requests.php" class="dashboard-nav-item <?php echo $current_page == 'requests.php' ? 'active' : ''; ?>">
                <span class="nav-m-icon">fact_check</span>
                <span>Requests</span>
            </a>

            <!-- inquiries menu -->
            <a href="./inquiries.php" class="dashboard-nav-item <?php echo $current_page == 'inquiries.php' ? 'active' : ''; ?>">
                <span class="nav-m-icon">contact_support</span>
                <span>Inquiries</span>
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
                <div style="margin-right: 5px;">Logged in as <?php echo htmlspecialchars($username); ?>  (Admin)</div>
                <a href="./myProfile.php">
                    <span class="m-icon">account_circle</span>
                </a>
            </div>
        </header>
        <!-- sidebar javascript -->
        <script src="./js/sidebar.js"></script>

        <!-- app content -->
        <div class='dashboard-content'>