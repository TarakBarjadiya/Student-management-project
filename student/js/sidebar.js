console.log('script loaded');

const mobileScreen = window.matchMedia('(max-width: 990px)');

document.addEventListener('DOMContentLoaded', function () {
  // Dropdown toggle
  document
    .querySelectorAll('.dashboard-nav-dropdown-toggle')
    .forEach(function (toggle) {
      toggle.addEventListener('click', function () {
        let closestDropdown = this.closest('.dashboard-nav-dropdown');
        if (closestDropdown) {
          closestDropdown.classList.toggle('show');
          closestDropdown
            .querySelectorAll('.dashboard-nav-dropdown')
            .forEach(function (dropdown) {
              dropdown.classList.remove('show');
            });
          closestDropdown.parentNode
            .querySelectorAll('.dashboard-nav-dropdown')
            .forEach(function (sibling) {
              if (sibling !== closestDropdown) {
                sibling.classList.remove('show');
              }
            });
        }
      });
    });

  // Menu toggle for mobile and desktop
  document.querySelectorAll('.menu-toggle').forEach(function (toggle) {
    toggle.addEventListener('click', function () {
      if (mobileScreen.matches) {
        document
          .querySelector('.dashboard-nav')
          .classList.toggle('mobile-show');
      } else {
        document
          .querySelector('.dashboard')
          .classList.toggle('dashboard-compact');
      }
    });
  });
});
