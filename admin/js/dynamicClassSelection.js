function updateClasses() {
  const classType = document.getElementById('class_type').value;
  const xhr = new XMLHttpRequest();
  xhr.open('GET', `?class_type=${classType}`, true);
  xhr.onload = function () {
    if (this.status === 200) {
      const classNames = JSON.parse(this.responseText);
      let options = '<option value="">Select Class Name</option>';
      classNames.forEach(function (classItem) {
        options += `<option value="${classItem.id}" data-fees="${classItem.class_fees}">${classItem.class_name} (${classItem.batch_year})</option>`;
      });
      document.getElementById('class_name').innerHTML = options;
    }
  };
  xhr.send();
}

document.getElementById('class_name').addEventListener('change', function () {
  const selectedOption = this.options[this.selectedIndex];
  const classFees = selectedOption.getAttribute('data-fees');
  document.getElementById('class_fees').value = classFees;
});
