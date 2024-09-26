console.log('header.js loaded.');

document.addEventListener('DOMContentLoaded', function () {
  var currentPath = window.location.pathname;
  var navLinks = document.querySelectorAll('.nav-bar a');
  var dropbtn = document.querySelector('.dropdown .dropbtn');

  navLinks.forEach(function (link) {
    if (link.href.includes(currentPath)) {
      link.classList.add('active');
    }
  });

  var dropdownLinks = document.querySelectorAll('.dropdown-content a');
  dropdownLinks.forEach(function (link) {
    if (link.href.includes(currentPath)) {
      dropbtn.classList.add('active');
    }
  });

  document.addEventListener('click', function (event) {
    var dropdownContent = document.querySelector('.dropdown .dropdown-content');
    var isClickInsideDropdown = event.target.closest('.dropdown');
    if (!isClickInsideDropdown) {
      dropdownContent.style.display = 'none';
    }

    // Close sidebar if clicking outside of it
    var sidebar = document.getElementById('sidebar');
    var isClickInsideSidebar =
      event.target.closest('#sidebar') || event.target.closest('.menu-toggle');
    if (!isClickInsideSidebar) {
      sidebar.classList.remove('active'); // Close the sidebar
    }
  });
});

function toggleSidebar() {
  var sidebar = document.getElementById('sidebar');
  sidebar.classList.toggle('active');
}

function toggleDropdown() {
  var dropdownContent = document.querySelector('.dropdown .dropdown-content');
  dropdownContent.style.display =
    dropdownContent.style.display === 'block' ? 'none' : 'block';
}
