<?php include "./includes/sidebar.php" ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>requests</title>
    <link rel="stylesheet" href="./css/table.css">
    <style>
        /* Container for status filter */
        #statusFilter {
            display: inline-block;
            padding: 8px 12px;
            font-size: 14px;
            border: 1px solid #ccc;
            border-radius: 5px;
            background-color: #fff;
            color: #333;
            cursor: pointer;
            margin-bottom: 15px;
            font: inherit;
            font-size: 14px;
        }

        /* Dropdown for status filter */
        #statusFilter option {
            padding: 10px;
        }

        #status-label {
            font-size: 0.850em;
            color: #374151;
        }
    </style>
</head>

<body>
    <h1>Requests</h1>
    <section class="manage-class">

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
        <div class="filter-group">
            <label id="status-label" for="statusFilter">Filter by Status:</label>
            <select id="statusFilter">
                <option value="all">All</option>
                <option value="pending">Pending</option>
                <option value="approved">Approved</option>
                <option value="rejected">Rejected</option>
            </select>
        </div>

        <div class="pagination" id="pagination-top">
            <button id="prev-page-top"><span class="m-icon">arrow_back_ios</span></button>
            <div id="page-buttons-top"></div>
            <button id="next-page-top"><span class="m-icon">arrow_forward_ios</span></button>
        </div>

        <small>click on headers to sort table columns</small>
        <div id="table-container" data-table-name="requests" data-columns="id,request_title,student_details,status,request_date"></div>

        <div class="pagination" id="pagination-bottom">
            <button id="prev-page-bottom"><span class="m-icon">arrow_back_ios</span></button>
            <div id="page-buttons-bottom"></div>
            <button id="next-page-bottom"><span class="m-icon">arrow_forward_ios</span></button>
        </div>

    </section>
    <script defer src="./js/fetch_table.js"></script>
</body>

</html>