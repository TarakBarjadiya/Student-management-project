<?php include "./includes/sidebar.php" ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Manage Class</title>
    <link rel="stylesheet" href="./css/sidebar.css">
    <link rel="stylesheet" href="./css/table.css">
    <link href="https://unpkg.com/gridjs/dist/theme/mermaid.min.css" rel="stylesheet" />
</head>

<body>
    <h1>Manage Fees</h1>
    <section class="manage-fees">

        <div class="nice-form-group">
            <label>Search</label>
            <input type="search" id="search" placeholder="Search by Enroll. No., Student Name, fee amount or class" value="" /><br>
        </div>
        <div class="top-links">
            <button class="button" id="search-button">Start Search</button>
            <button class="button" id="exportToCSV">
                <span>Export CSV</span>
            </button>
        </div>

        <div class="pagination" id="pagination-top">
            <button id="prev-page-top"><span class="m-icon">arrow_back_ios</span></button>
            <div id="page-buttons-top"></div>
            <button id="next-page-top"><span class="m-icon">arrow_forward_ios</span></button>
        </div>

        <small class="header-small">click on headers to sort table columns</small>
        <div id="table-container" data-table-name="student_info" data-columns="id,enrollment_number,full_name,fees_pending,fees_paid"></div>

        <div class="pagination" id="pagination-bottom">
            <button id="prev-page-bottom"><span class="m-icon">arrow_back_ios</span></button>
            <div id="page-buttons-bottom"></div>
            <button id="next-page-bottom"><span class="m-icon">arrow_forward_ios</span></button>
        </div>

    </section>
    <!-- Delete Confirmation Modal -->
    <div id="deleteModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <p>Are you sure you want to delete this class?</p>
            <button id="confirmDelete" class="confirm-btn">Delete</button>
            <button id="cancelDelete" class="cancel-btn">Cancel</button>
        </div>
    </div>
    <script defer src="./js/fetch_table.js"></script>
</body>

</html>