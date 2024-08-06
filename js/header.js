console.log("header.js loaded.")

document.addEventListener('DOMContentLoaded', function () {
  var currentPath = window.location.pathname;
  var navLinks = document.querySelectorAll('.nav-bar a');
  var dropbtn = document.querySelector('.dropdown .dropbtn');

  navLinks.forEach(function (link) {
    if (link.href.includes(currentPath)) {
      link.classList.add('active');
    }
  });

  // Check for dropdown menu items
  var dropdownLinks = document.querySelectorAll('.dropdown-content a');
  dropdownLinks.forEach(function (link) {
    if (link.href.includes(currentPath)) {
      dropbtn.classList.add('active');
    }
  });

  // Add event listener for clicks outside the dropdown
  document.addEventListener('click', function (event) {
    var dropdownContent = document.querySelector('.dropdown .dropdown-content');
    var isClickInsideDropdown = event.target.closest('.dropdown');
    if (!isClickInsideDropdown) {
      dropdownContent.style.display = 'none';
    }
  });
});

function onMenuClick() {
  console.log('clicked');
  var navBar = document.getElementById('navigation-bar');
  if (navBar.className === 'nav-bar') {
    navBar.className += ' responsive';
  } else {
    navBar.className = 'nav-bar';
  }
}

function toggleDropdown() {
  var dropdown = document.querySelector('.dropdown');
  var dropdownContent = document.querySelector('.dropdown .dropdown-content');
  if (dropdownContent.style.display === 'block') {
    dropdownContent.style.display = 'none';
    dropdown.classList.remove('show');
  } else {
    dropdownContent.style.display = 'block';
    dropdown.classList.add('show');
  }
}
