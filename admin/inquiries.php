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
    <h1>Inquiries</h1>
    <section class="manage-fees">

        <div class="nice-form-group">
            <label>Search</label>
            <input type="search" id="search" placeholder="Search here..." value="" /><br>
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
        <div id="table-container" data-table-name="inquiries" data-columns="id,inq_name,inq_email,inq_date"></div>

        <div class="pagination" id="pagination-bottom">
            <button id="prev-page-bottom"><span class="m-icon">arrow_back_ios</span></button>
            <div id="page-buttons-bottom"></div>
            <button id="next-page-bottom"><span class="m-icon">arrow_forward_ios</span></button>
        </div>

    </section>
    <script defer src="./js/fetch_table.js"></script>
</body>

</html>