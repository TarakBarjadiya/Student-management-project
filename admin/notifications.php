<?php include "./includes/sidebar.php" ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notifications</title>
    <link rel="stylesheet" href="./css/table.css">
</head>

<body>
    <h1>Notifications</h1>
    <section class="manage-class">

        <div class="nice-form-group">
            <label>Search</label>
            <input type="search" id="search" placeholder="Search by student name or notification title" value="" /><br>
        </div>
        <div class="top-links">
            <button class="button" id="search-button">Start Search</button>
            <button class="button" id="exportToCSV">
                <span>Export CSV</span>
            </button>
            <button class="button" id="search-button" onclick="window.location.href='createNotifications.php';">Send Notification</button>
        </div>

        <div class="pagination" id="pagination-top">
            <button id="prev-page-top"><span class="m-icon">arrow_back_ios</span></button>
            <div id="page-buttons-top"></div>
            <button id="next-page-top"><span class="m-icon">arrow_forward_ios</span></button>
        </div>

        <small>click on headers to sort table columns</small>
        <div id="table-container" data-table-name="student_notifications" data-columns="id,student_name,class_name,notification_title,notification_date"></div>

        <div class="pagination" id="pagination-bottom">
            <button id="prev-page-bottom"><span class="m-icon">arrow_back_ios</span></button>
            <div id="page-buttons-bottom"></div>
            <button id="next-page-bottom"><span class="m-icon">arrow_forward_ios</span></button>
        </div>

    </section>
    <!-- Delete Confirmation Modal -->
    <div id="delete-modal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <p>Are you sure you want to delete this class?</p>
            <button id="confirm-delete" class="confirm-btn">Delete</button>
            <button id="cancel-delete" class="cancel-btn">Cancel</button>
        </div>
    </div>
    <script defer src="./js/fetch_table.js"></script>
</body>

</html>