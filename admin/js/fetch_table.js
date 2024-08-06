document.addEventListener('DOMContentLoaded', function () {
  const headerMapping = {
    class_type: 'Class Type',
    class_name: 'Class Name',
    batch_year: 'Batch Year',
    creation_date: 'Creation Date',
    modification_date: 'Last Edit',
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
  const columns = tableContainer.getAttribute('data-columns');

  const deleteModal = document.getElementById('deleteModal');
  const closeModal = document.querySelector('.close');
  const confirmDeleteButton = document.getElementById('confirmDelete');
  const cancelDeleteButton = document.getElementById('cancelDelete');

  if (!tableName || !columns) {
    console.error('Table name or columns not specified');
    return;
  }

  fetch('./includes/fetch_data.php', {
    method: 'POST',
    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
    body: new URLSearchParams({
      table: tableName,
      columns: columns,
    }),
  })
    .then((response) => response.json())
    .then((data) => {
      if (data.error) {
        console.error(data.error);
        return;
      }
      tableData = data.data;
      columnNames = data.columns.filter((col) => col !== 'id');
      filteredData = tableData;
      renderTable(filteredData, columnNames, currentPage);
      updatePaginationControls(filteredData);
    })
    .catch((error) => console.error('Error fetching data:', error));

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
      tableHtml += `
        <td>
          <a href="editClass.php?id=${row.id}&table=${tableName}" class="edit-link">Edit</a>
          <button class="delete-btn" data-id="${row.id}" data-table="${tableName}">Delete</button>
        </td>`;
      tableHtml += '</tr>';
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
      if (a[column] < b[column]) return order === 'asc' ? -1 : 1;
      if (a[column] > b[column]) return order === 'asc' ? 1 : -1;
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

  function capitalizeFirstLetter(string) {
    return string.charAt(0).toUpperCase() + string.slice(1);
  }

  function addDeleteEventListeners() {
    const deleteButtons = document.querySelectorAll('.delete-btn');
    deleteButtons.forEach((button) => {
      button.addEventListener('click', (event) => {
        deleteId = button.getAttribute('data-id');
        deleteTable = button.getAttribute('data-table');
        openDeleteModal();
      });
    });
  }

  function openDeleteModal() {
    deleteModal.style.display = 'block';
  }

  function closeDeleteModal() {
    deleteModal.style.display = 'none';
  }

  closeModal.addEventListener('click', closeDeleteModal);
  cancelDeleteButton.addEventListener('click', closeDeleteModal);
  window.addEventListener('click', (event) => {
    if (event.target == deleteModal) {
      closeDeleteModal();
    }
  });
  window.addEventListener('keydown', (event) => {
    if (event.key === 'Escape') {
      closeDeleteModal();
    }
  });

  confirmDeleteButton.addEventListener('click', () => {
    deleteRow(deleteId, deleteTable);
    closeDeleteModal();
  });

  function deleteRow(id, table) {
    fetch(`delete_row.php?id=${id}&table=${table}`)
      .then((response) => response.json())
      .then((data) => {
        if (data.success) {
          location.reload();
        } else {
          alert(`Error deleting row: ${data.error}`);
        }
      })
      .catch((error) => console.error('Error deleting row:', error));
  }

  function addPaginationEventListeners() {
    const pageButtons = document.querySelectorAll('.page-btn');
    pageButtons.forEach((button) => {
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
});
