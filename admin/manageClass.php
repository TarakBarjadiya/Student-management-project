<?php
include "./includes/sidebar.php";

error_reporting(E_ALL);
ini_set('display_errors', 1);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Manage Class</title>
    <link rel="stylesheet" href="./css/sidebar.css">
    <link rel="stylesheet" href="./css/table.css">
</head>

<body>
    <h1>Manage Class</h1>
    <section class="manage-class">

        <div class="nice-form-group">
            <label>Search</label>
            <input type="search" id="search" placeholder="Search Batch, Name or Type of Class" value="" /><br>
        </div>
        <div class="top-links">
            <button class="button" id="search-button">Start Search</button>
            <button class="button" id="exportToCSV">
                <span>Export CSV</span>
            </button>
            <a href="./includes/resetFees.php">Add Semester Fees</a>
        </div>

        <div class="pagination" id="pagination-top">
            <button id="prev-page-top"><span class="m-icon">arrow_back_ios</span></button>
            <div id="page-buttons-top"></div>
            <button id="next-page-top"><span class="m-icon">arrow_forward_ios</span></button>
        </div>

        <small>Click on headers to sort table columns</small>
        <div id="table-container" data-table-name="classes" data-columns="id,batch_year,class_name,class_type,class_fees,creation_date"></div>

        <div class="pagination" id="pagination-bottom">
            <button id="prev-page-bottom"><span class="m-icon">arrow_back_ios</span></button>
            <div id="page-buttons-bottom"></div>
            <button id="next-page-bottom"><span class="m-icon">arrow_forward_ios</span></button>
        </div>

    </section>

    <!-- Delete Confirmation Modal -->
    <div id="delete-modal" class="modal" style="display:none;">
        <div class="modal-content">
            <span class="close">&times;</span>
            <p>Are you sure you want to delete this class?</p>
            <button id="confirm-delete" class="confirm-btn">Delete</button>
            <button id="cancel-delete" class="cancel-btn">Cancel</button>
        </div>
    </div>

    <script defer src="./js/fetch_table.js"></script>
    <script>
        let classId; // Variable to hold the class ID to delete
        const table = 'classes'; // Table name

        // Open delete modal and set classId
        function openDeleteModal(id) {
            classId = id; // Set the ID of the class to delete
            document.getElementById('delete-modal').style.display = 'block';
        }

        // Handle closing the modal
        document.querySelector('.close').addEventListener('click', () => {
            document.getElementById('delete-modal').style.display = 'none';
        });

        document.getElementById('cancel-delete').addEventListener('click', () => {
            document.getElementById('delete-modal').style.display = 'none';
        });

        document.addEventListener('keydown', (event) => {
            if (event.key === 'Escape') {
                const modal = document.getElementById('delete-modal');
                if (modal.style.display === 'block') {
                    modal.style.display = 'none';
                }
            }
        });

        document.getElementById('confirm-delete').addEventListener('click', () => {
            fetch(`delete_row.php?id=${classId}&table=${table}`)
                .then(response => {
                    console.log(`Response status: ${response.status}`); // Log the status
                    if (!response.ok) {
                        return response.json().then(err => {
                            throw new Error(err.error || 'Unknown error occurred');
                        });
                    }
                    return response.json(); // Expecting JSON response
                })
                .then(data => {
                    if (data.success) {
                        alert(data.success);
                        window.location.href = 'manageClass.php';
                    } else if (data.error) {
                        alert(data.error);
                    }
                })
                .catch(error => {
                    alert('An error occurred while deleting the class: ' + error.message);
                    console.error('Error:', error);
                });
        });
    </script>

</body>

</html>