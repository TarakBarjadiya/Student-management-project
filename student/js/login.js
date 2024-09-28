document.addEventListener('DOMContentLoaded', () => {
    // Password match validation
    const form = document.querySelector('form');
    const passwordField = document.querySelector('input[name="new_password"]');
    const confirmPasswordField = document.querySelector('input[name="confirm_password"]');
  
    form.addEventListener('submit', (event) => {
      if (passwordField.value !== confirmPasswordField.value) {
        alert('New Password and Confirm Password do not match!');
        event.preventDefault(); // Prevent form submission
      }
    });
  
    // Show/hide password functionality
    const showPassCheckbox = document.getElementById('showpass');
    showPassCheckbox.addEventListener('change', () => {
      const type = showPassCheckbox.checked ? 'text' : 'password';
      passwordField.type = type;
      confirmPasswordField.type = type;
    });
  });
  