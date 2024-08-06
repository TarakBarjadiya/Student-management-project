<?php
include "./includes/dbconnection.php";

$records_per_page = 5;
$current_page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$start_from = ($current_page - 1) * $records_per_page;

// Default sorting
$sort_by = isset($_GET['sort_by']) ? $_GET['sort_by'] : 'enrollment_number';
$sort_order = 'ASC'; // Default sorting order

if ($sort_by == 'name') {
    $order_by = "si.first_name, si.last_name";
} elseif ($sort_by == 'class_type') {
    $order_by = "c.class_name";
} else {
    $order_by = "si.enrollment_number";
}

// Search functionality
$search_query = isset($_GET['search']) ? mysqli_real_escape_string($conn, $_GET['search']) : '';
$search_condition = "";

if ($search_query) {
    // Split the search query into parts
    $parts = explode(' ', $search_query);
    $first_name_part = isset($parts[0]) ? $parts[0] : '';
    $last_name_part = isset($parts[1]) ? $parts[1] : '';

    // Build search condition
    if ($last_name_part) {
        $search_condition = "AND (si.first_name LIKE '%$first_name_part%' 
                              AND si.last_name LIKE '%$last_name_part%' 
                              OR CONCAT(si.first_name, ' ', si.last_name) LIKE '%$search_query%' 
                              OR si.enrollment_number LIKE '%$search_query%' 
                              OR c.class_name LIKE '%$search_query%')";
    } else {
        $search_condition = "AND (si.first_name LIKE '%$search_query%' 
                              OR si.last_name LIKE '%$search_query%' 
                              OR CONCAT(si.first_name, ' ', si.last_name) LIKE '%$search_query%' 
                              OR si.enrollment_number LIKE '%$search_query%' 
                              OR c.class_name LIKE '%$search_query%')";
    }
}

// Fetch student data from the database with sorting and searching
$query = "SELECT si.id, si.enrollment_number, si.class_id, si.stud_mobile, si.stud_email, 
          si.first_name, si.middle_name, si.last_name, si.gender, si.stud_dob, si.stud_address, 
          si.father_name, si.mother_name, si.father_contact, si.mother_contact, 
          c.class_name, c.batch_year 
          FROM student_info si 
          JOIN classes c ON si.class_id = c.id 
          WHERE 1=1 $search_condition
          ORDER BY $order_by $sort_order
          LIMIT $start_from, $records_per_page";

$result = mysqli_query($conn, $query);

// Count query
$total_query = "SELECT COUNT(*) AS total FROM student_info si 
                JOIN classes c ON si.class_id = c.id 
                WHERE 1=1 $search_condition";
$total_result = mysqli_query($conn, $total_query);
$total_records = mysqli_fetch_assoc($total_result)['total'];
$total_pages = ceil($total_records / $records_per_page);

if (isset($_GET['export'])) {
    // Start output buffering
    ob_start();

    // Set headers for CSV export
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment;filename=students.csv');

    $output = fopen('php://output', 'w');
    fputcsv($output, ['Enrollment Number', 'Name', 'Class', 'Mobile', 'Email', 'Gender', 'DOB', 'Address', 'Father Name', 'Mother Name', 'Father Contact', 'Mother Contact']);

    $result_export = mysqli_query($conn, "SELECT si.enrollment_number, CONCAT(si.first_name, ' ', si.middle_name, ' ', si.last_name) AS name, c.class_name, si.stud_mobile, si.stud_email, si.gender, si.stud_dob, si.stud_address, si.father_name, si.mother_name, si.father_contact, si.mother_contact 
                                         FROM student_info si 
                                         JOIN classes c ON si.class_id = c.id 
                                         WHERE 1=1 $search_condition");
    while ($row = mysqli_fetch_assoc($result_export)) {
        fputcsv($output, $row);
    }
    fclose($output);

    // End output buffering and flush
    ob_end_flush();
    exit;
}
include "./includes/sidebar.php";
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Student</title>
    <link rel="stylesheet" href="./css/table.css">
    <link rel="stylesheet" href="./css/cards.css">
    <link rel="stylesheet" href="./css/deleteModal.css">
    <script>
        function refreshPage() {
            window.location.href = 'manageStudents.php';
        }
        document.addEventListener("DOMContentLoaded", function() {
            const refreshButton = document.getElementById('refresh-button');
            refreshButton.addEventListener('click', refreshPage);

            // F5 key event listener
            document.addEventListener('keydown', function(event) {
                if (event.key === 'F5') {
                    event.preventDefault(); // Prevent default F5 behavior
                    refreshPage();
                }
            });
        });
    </script>
</head>

<body>
    <h1>Manage Students</h1>

    <form class="inline-inputs" method="get" action="manageStudents.php">
        <div class="search-container">
            <div class="nice-form-group">
                <label>Search</label>
                <input type="search" id="search" name="search" placeholder="Search Student By Name, Enrollment No. or Class" value="<?php echo htmlspecialchars($search_query); ?>" />
                <input type="hidden" name="sort_by" value="<?php echo htmlspecialchars($sort_by); ?>">
                <div class="actions">
                    <button type="submit" id="search-button">Start Search</button>
                    <button type="button" onclick="refreshPage()" id="refresh-button">Refresh</button>
                </div>
            </div>
        </div>
    </form>

    <div class="inline-content">
        <form class="inline-inputs" method="get" action="manageStudents.php">
            <div class="sort-options">
                <div class="nice-form-group">
                    <label for="sort-by">Sort By</label>
                    <select name="sort_by" id="sort-by">
                        <option value="enrollment_number" <?php if ($sort_by == 'enrollment_number') echo 'selected'; ?>>Enrollment Number</option>
                        <option value="name" <?php if ($sort_by == 'name') echo 'selected'; ?>>Name</option>
                        <option value="class_type" <?php if ($sort_by == 'class_type') echo 'selected'; ?>>Class Type</option>
                    </select>
                    <input type="hidden" name="search" value="<?php echo htmlspecialchars($search_query); ?>">
                    <div class="actions">
                        <button type="submit">Sort</button>
                    </div>
                </div>
            </div>
        </form>
        <div class="top-links">
            <a href="manageStudents.php?export=1<?php if ($search_query) echo '&search=' . urlencode($search_query); ?>">
                <button class="button">
                    <span>Export CSV</span>
                </button>
            </a>
            <a href="./addStudent.php">Add Student +</a>
        </div>
    </div>
    <div class="pagination" id="pagination-top">
        <?php
        // Get the current page from the URL
        $current_page = isset($_GET['page']) ? (int)$_GET['page'] : 1;

        // Loop to generate pagination links
        for ($i = 1; $i <= $total_pages; $i++) {
            // Check if this link is for the current page
            $active_class = ($i == $current_page) ? 'active' : '';
            echo "<a href='manageStudents.php?page=$i&sort_by=" . urlencode($sort_by) . "&search=" . urlencode($search_query) . "' class='$active_class'>$i</a>";
        }
        ?>
    </div>

    <div id="table-container">
        <?php while ($row = mysqli_fetch_assoc($result)) { ?>
            <div class="card">
                <div class="heading">
                    <h1><?php echo $row['first_name'] . ' ' . $row['middle_name'] . ' ' . $row['last_name']; ?></h1>
                    <p class="subheading"><?php echo $row['enrollment_number'] . ' | ' . $row['class_name'] . ' (' . $row['batch_year'] . ')'; ?></p>
                    <p class="subheading"><?php echo $row['stud_mobile'] . ' | ' . $row['stud_email']; ?></p>
                </div>
                <div class="details">
                    <div class="column">
                        <p><strong>Gender</strong>
                        <div><?php echo $row['gender']; ?></div>
                        </p>
                        <p><strong>DOB</strong>
                        <div><?php echo $row['stud_dob']; ?></div>
                        </p>
                        <div class="address">
                            <p><strong>Address</strong>
                            <div><?php echo $row['stud_address']; ?></div>
                            </p>
                        </div>
                    </div>
                    <div class="column">
                        <p><strong>Father's Name</strong>
                        <div><?php echo $row['father_name']; ?></div>
                        </p>
                        <p><strong>Mother's Name</strong>
                        <div><?php echo $row['mother_name']; ?></div>
                        </p>
                        <p><strong>Father's Contact</strong>
                        <div><?php echo $row['father_contact']; ?></div>
                        </p>
                        <p><strong>Mother's Contact</strong>
                        <div><?php echo $row['mother_contact']; ?></div>
                        </p>
                    </div>
                </div>
                <div class="actions">
                    <button onclick="editStudent(<?php echo $row['id']; ?>)">Edit</button>
                    <button onclick="deleteStudent(<?php echo $row['id']; ?>, '<?php echo addslashes($row['first_name'] . ' ' . $row['last_name']); ?>')">Delete</button>
                </div>
            </div>
        <?php } ?>
    </div>

    <div class="pagination" id="pagination-bottom">
        <?php
        // Get the current page from the URL
        $current_page = isset($_GET['page']) ? (int)$_GET['page'] : 1;

        // Loop to generate pagination links
        for ($i = 1; $i <= $total_pages; $i++) {
            // Check if this link is for the current page
            $active_class = ($i == $current_page) ? 'active' : '';
            echo "<a href='manageStudents.php?page=$i&sort_by=" . urlencode($sort_by) . "&search=" . urlencode($search_query) . "' class='$active_class'>$i</a>";
        }
        ?>
    </div>

    <div id="deleteModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <p>Are you sure you want to delete the student: <span id="studentName"></span>?</p>
            <button id="confirmDelete" class="confirm-btn">Delete</button>
            <button id="cancelDelete" class="cancel-btn">Cancel</button>
        </div>
    </div>

    <script>
        function editStudent(id) {
            window.location.href = './editStudent.php?id=' + id;
        }
    </script>
    <script src="./js/deleteModal.js"></script>
</body>

</html>