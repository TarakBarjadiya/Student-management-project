function validateMobileNumber() {
  const fields = ['mobile', 'father_contact', 'mother_contact'];
  fields.forEach((fieldId) => {
    const input = document.getElementById(fieldId);
    input.addEventListener('input', function () {
      this.value = this.value.replace(/[^0-9]/g, ''); // Allow only digits
      if (this.value.length > 10) {
        this.value = this.value.slice(0, 10); // Trim to 10 digits
      }
      // Ensure the value is at least 10 digits
      if (this.value.length < 10) {
        this.setCustomValidity('Mobile number must be at least 10 digits.');
      } else {
        this.setCustomValidity('');
      }
    });
  });
}

function validatePostalCode() {
  const postalCodeInput = document.getElementById('postal_code');
  postalCodeInput.addEventListener('input', function () {
    this.value = this.value.replace(/[^0-9]/g, ''); // Allow only digits
    if (this.value.length > 6) {
      this.value = this.value.slice(0, 6); // Trim to 6 digits
    }
    // Ensure the value is at least 6 digits
    if (this.value.length < 6) {
      this.setCustomValidity('Postal code must be at least 6 digits.');
    } else {
      this.setCustomValidity('');
    }
  });
}

document.addEventListener('DOMContentLoaded', () => {
  validateMobileNumber();
  validatePostalCode();
});
