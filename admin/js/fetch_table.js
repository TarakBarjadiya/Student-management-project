document.addEventListener('DOMContentLoaded', function () {
  const headerMapping = {
    class_type: 'Class Type',
    class_name: 'Class Name',
    batch_year: 'Batch Year',
    creation_date: 'Creation Date',
    class_fees: 'Class Fees',

    enrollment_number: 'Enroll. No.',
    full_name: 'Full Name',
    fees_pending: 'Fees Pending',
    fees_paid: 'Fees Paid',

    student_name: 'Student Name',
    notification_title: 'Notification Title',
    notification_date: 'Notification Date',

    c_notice_title: 'Notice Title',
    publish_date: 'Publish Date',

    request_title:'Request Title',
    student_details:'Student Details',
    status:'Status',
    request_date:'Request Date',

    inq_name: 'Name',
    inq_email:'Email',
    inq_date: 'Date'
  };

  let tableData = [];
  let columnNames = [];
  let filteredData = [];
  let currentSort = { column: '', order: '' };
  let currentPage = 1;
  const rowsPerPage = 10;
  let deleteId = null;
  let deleteTable = null;

  const tableContainer = document.getElementById('table-container');
  const searchInput = document.getElementById('search');
  const searchButton = document.getElementById('search-button');
  const exportCSVButton = document.getElementById('exportToCSV');

  // Top pagination controls
  const prevPageButtonTop = document.getElementById('prev-page-top');
  const nextPageButtonTop = document.getElementById('next-page-top');
  const pageButtonsContainerTop = document.getElementById('page-buttons-top');

  // Bottom pagination controls
  const prevPageButtonBottom = document.getElementById('prev-page-bottom');
  const nextPageButtonBottom = document.getElementById('next-page-bottom');
  const pageButtonsContainerBottom = document.getElementById(
    'page-buttons-bottom',
  );

  const tableName = tableContainer.getAttribute('data-table-name');
  let columns = tableContainer.getAttribute('data-columns');

  // fetch('./includes/fetch_data.php', {
  //   method: 'POST',
  //   headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
  //   body: new URLSearchParams({
  //     table: tableName,
  //     columns: columns,
  //   }),
  // })
  //   .then((response) => response.json())
  //   .then((data) => {
  //     console.log('Fetched data:', data);
  //     if (data.error) {
  //       console.error(data.error);
  //       return;
  //     }
  //     tableData = data.data;
  //     columnNames = data.columns.filter((col) => col !== 'id');
  //     filteredData = tableData;
  //     renderTable(filteredData, columnNames, currentPage);
  //     updatePaginationControls(filteredData);
  //   })
  //   .catch((error) => console.error('Error fetching data:', error));

  fetch('./includes/fetch_data.php', {
    method: 'POST',
    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
    body: new URLSearchParams({
      table: tableName,
      columns: columns,
    }),
  })
    .then((response) => response.text()) // Change to text() for debugging
    .then((data) => {
      console.log(data); // Log the raw response for inspection
      const jsonData = JSON.parse(data); // Then parse it
      if (jsonData.error) {
        console.error(jsonData.error);
        return;
      }
      tableData = jsonData.data;
      columnNames = jsonData.columns.filter((col) => col !== 'id');
      filteredData = tableData;
      renderTable(filteredData, columnNames, currentPage);
      updatePaginationControls(filteredData);
    })
    .catch((error) => console.error('Error fetching data:', error));

  const statusFilter = document.getElementById('statusFilter');

  statusFilter.addEventListener('change', function () {
    filterByStatus();
  });

  function filterByStatus() {
    const selectedStatus = statusFilter.value;
    filteredData = tableData.filter((row) => {
      if (selectedStatus === 'all') {
        return true; // Show all if 'All' is selected
      }
      return row.status.toLowerCase() === selectedStatus.toLowerCase();
    });
    currentPage = 1;
    renderTable(filteredData, columnNames, currentPage);
    updatePaginationControls(filteredData);
  }

  function renderTable(data, columnNames, page) {
    let start = (page - 1) * rowsPerPage;
    let end = start + rowsPerPage;
    let paginatedData = data.slice(start, end);
    let tableHtml = '<table><thead><tr>';

    columnNames.forEach((col) => {
      const displayName = headerMapping[col] || capitalizeFirstLetter(col);
      tableHtml += `<th data-column="${col}">${displayName}</th>`;
    });

    tableHtml += '<th>Actions</th></tr></thead><tbody>';

    paginatedData.forEach((row) => {
      tableHtml += '<tr>';
      columnNames.forEach((col) => {
        tableHtml += `<td>${row[col]}</td>`;
      });
      if (tableName === 'classes') {
        tableHtml += `
          <td>
            <a href="editClass.php?id=${row.id}&table=${tableName}" class="edit-link">Edit</a>
            <button class="delete-btn" data-id="${row.id}" data-table="${tableName}">Delete</button>
          </td>`;
        tableHtml += '</tr>';
      }
      if (tableName === 'student_info') {
        tableHtml += `
          <td>
            <a href="adjustFees.php?id=${row.id}&table=${tableName}" class="edit-link">Adjust Fees</a>
          </td>`;
        tableHtml += '</tr>';
      }
      if (tableName === 'student_notifications') {
        tableHtml += `
          <td>
            <a href="viewNotification.php?notification_id=${row.notification_id}&table=${tableName}" class="edit-link">Details</a>
          </td>`;
        tableHtml += '</tr>';
      }
      if (tableName === 'class_notices') {
        tableHtml += `
          <td>
            <a href="viewClassNotice.php?notice_id=${row.id}&table=${tableName}" class="edit-link">Details</a>
          </td>`;
        tableHtml += '</tr>';
      }
      if (tableName === 'requests') {
        tableHtml += `
          <td>
            <a href="viewRequest.php?request_id=${row.id}&table=${tableName}" class="edit-link">View</a>
          </td>`;
        tableHtml += '</tr>';
      }
      if (tableName === 'inquiries') {
        tableHtml += `
          <td>
            <a href="viewInq.php?inq_id=${row.id}&table=${tableName}" class="edit-link">View</a>
          </td>`;
        tableHtml += '</tr>';
      }
    });

    tableHtml += '</tbody></table>';
    tableContainer.innerHTML = tableHtml;

    addSorting();
    addDeleteEventListeners();
  }

  function updatePaginationControls(data) {
    const totalPages = Math.ceil(data.length / rowsPerPage);

    prevPageButtonTop.disabled = currentPage === 1;
    nextPageButtonTop.disabled = currentPage === totalPages;
    pageButtonsContainerTop.innerHTML = '';

    prevPageButtonBottom.disabled = currentPage === 1;
    nextPageButtonBottom.disabled = currentPage === totalPages;
    pageButtonsContainerBottom.innerHTML = '';

    for (let i = 1; i <= totalPages; i++) {
      const pageButtonTop = document.createElement('button');
      pageButtonTop.textContent = i;
      pageButtonTop.classList.add('page-btn');
      pageButtonTop.dataset.page = i;
      if (i === currentPage) {
        pageButtonTop.classList.add('active');
      }
      pageButtonsContainerTop.appendChild(pageButtonTop);

      const pageButtonBottom = document.createElement('button');
      pageButtonBottom.textContent = i;
      pageButtonBottom.classList.add('page-btn');
      pageButtonBottom.dataset.page = i;
      if (i === currentPage) {
        pageButtonBottom.classList.add('active');
      }
      pageButtonsContainerBottom.appendChild(pageButtonBottom);
    }

    addPaginationEventListeners();
  }

  function addSorting() {
    const headers = document.querySelectorAll('th');
    headers.forEach((header) => {
      header.addEventListener('click', () => {
        const column = header.getAttribute('data-column');
        let newOrder = 'asc';

        if (currentSort.column === column) {
          newOrder = currentSort.order === 'asc' ? 'desc' : 'asc';
        }

        currentSort = { column, order: newOrder };

        sortTable(column, newOrder);
      });
    });
  }

  function sortTable(column, order) {
    filteredData.sort((a, b) => {
      let valA = a[column];
      let valB = b[column];

      // Check if the column is class_fees and convert to numbers for sorting
      if (column === 'class_fees') {
        valA = parseInt(valA);
        valB = parseInt(valB);
      }

      if (valA < valB) return order === 'asc' ? -1 : 1;
      if (valA > valB) return order === 'asc' ? 1 : -1;
      return 0;
    });
    renderTable(filteredData, columnNames, currentPage);
    updatePaginationControls(filteredData);
  }

  function addSearch() {
    searchButton.addEventListener('click', performSearch);
    searchInput.addEventListener('keydown', (event) => {
      if (event.key === 'Enter') {
        performSearch();
      }
    });
  }

  function performSearch() {
    const searchTerm = searchInput.value.toLowerCase();
    filteredData = tableData.filter((row) => {
      return Object.keys(row).some((key) => {
        if (['creation_date', 'modification_date'].includes(key)) {
          return false;
        }
        return row[key].toString().toLowerCase().includes(searchTerm);
      });
    });
    currentPage = 1;
    renderTable(filteredData, columnNames, currentPage);
    updatePaginationControls(filteredData);
  }

  function addDeleteEventListeners() {
    const deleteButtons = document.querySelectorAll('.delete-btn');
    deleteButtons.forEach((button) => {
      button.addEventListener('click', (event) => {
        const id = event.target.dataset.id;
        const table = event.target.dataset.table;

        if (!id || !table) {
          console.error('ID or table not specified');
          return;
        }

        deleteId = id;
        deleteTable = table;

        // Show the modal
        const deleteModal = document.getElementById('delete-modal');
        deleteModal.style.display = 'block';

        // Add event listener for confirm delete button
        const confirmDeleteButton = document.getElementById('confirm-delete');
        confirmDeleteButton.removeEventListener('click', deleteRecord); // Remove previous event listener
        confirmDeleteButton.addEventListener('click', deleteRecord);
      });
    });
  }

  function deleteRecord() {
    fetch('./delete_row.php?id=' + deleteId + '&table=' + deleteTable, {
      method: 'GET',
    })
      .then((response) => response.json())
      .then((data) => {
        if (data.error) {
          console.error('Error:', data.error);
          alert(data.error); // Display error message to the user
          return;
        }

        // Hide the modal
        document.getElementById('delete-modal').style.display = 'none';

        // Remove the deleted record from tableData and filteredData
        tableData = tableData.filter((row) => row.id !== deleteId);
        filteredData = filteredData.filter((row) => row.id !== deleteId);

        // Re-render the table
        renderTable(filteredData, columnNames, currentPage);
        updatePaginationControls(filteredData);
      })
      .catch((error) => console.error('Error deleting record:', error));
  }

  function addPaginationEventListeners() {
    const pageButtonsTop = document.querySelectorAll(
      '#page-buttons-top .page-btn',
    );
    const pageButtonsBottom = document.querySelectorAll(
      '#page-buttons-bottom .page-btn',
    );

    pageButtonsTop.forEach((button) => {
      button.addEventListener('click', () => {
        currentPage = parseInt(button.dataset.page);
        renderTable(filteredData, columnNames, currentPage);
        updatePaginationControls(filteredData);
      });
    });

    pageButtonsBottom.forEach((button) => {
      button.addEventListener('click', () => {
        currentPage = parseInt(button.dataset.page);
        renderTable(filteredData, columnNames, currentPage);
        updatePaginationControls(filteredData);
      });
    });

    prevPageButtonTop.addEventListener('click', () => {
      if (currentPage > 1) {
        currentPage--;
        renderTable(filteredData, columnNames, currentPage);
        updatePaginationControls(filteredData);
      }
    });

    nextPageButtonTop.addEventListener('click', () => {
      const totalPages = Math.ceil(filteredData.length / rowsPerPage);
      if (currentPage < totalPages) {
        currentPage++;
        renderTable(filteredData, columnNames, currentPage);
        updatePaginationControls(filteredData);
      }
    });

    prevPageButtonBottom.addEventListener('click', () => {
      if (currentPage > 1) {
        currentPage--;
        renderTable(filteredData, columnNames, currentPage);
        updatePaginationControls(filteredData);
      }
    });

    nextPageButtonBottom.addEventListener('click', () => {
      const totalPages = Math.ceil(filteredData.length / rowsPerPage);
      if (currentPage < totalPages) {
        currentPage++;
        renderTable(filteredData, columnNames, currentPage);
        updatePaginationControls(filteredData);
      }
    });
  }

  function capitalizeFirstLetter(string) {
    return string.charAt(0).toUpperCase() + string.slice(1);
  }

  exportCSVButton.addEventListener('click', () => {
    exportToCSV(filteredData, columnNames);
  });

  function exportToCSV(data, columnNames) {
    let csvContent = 'data:text/csv;charset=utf-8,';

    // Add the header row
    const headerRow = columnNames
      .map((col) => headerMapping[col] || capitalizeFirstLetter(col))
      .join(',');
    csvContent += headerRow + '\n';

    // Add the data rows
    data.forEach((row) => {
      const rowData = columnNames.map((col) => `"${row[col]}"`).join(',');
      csvContent += rowData + '\n';
    });

    // Create a download link and trigger the download
    const encodedUri = encodeURI(csvContent);
    const link = document.createElement('a');
    link.setAttribute('href', encodedUri);
    link.setAttribute('download', 'table_data.csv');
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
  }

  addSearch();
  addPaginationEventListeners();
});
