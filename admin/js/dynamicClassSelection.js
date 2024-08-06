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

// function updateClassesStud() {
//   const classType = document.getElementById('class_type').value;
//   const xhr = new XMLHttpRequest();
//   xhr.open('GET', `editStudent.php?class_type=${classType}`, true);
//   xhr.onload = function () {
//     if (this.status === 200) {
//       const classNames = JSON.parse(this.responseText);
//       let options = '<option value="">Select Class Name</option>';
//       classNames.forEach(function (classItem) {
//         options += `<option value="${classItem.id}">${classItem.class_name} (${classItem.batch_year})</option>`;
//       });
//       document.getElementById('class_name').innerHTML = options;

//       // Set the previously selected class_name if available
//       const preselectedClassName =
//         '<?php echo htmlspecialchars($selectedClassNameId); ?>';
//       if (preselectedClassName) {
//         document.getElementById('class_name').value = preselectedClassName;
//       }
//     }
//   };
//   xhr.send();
// }

// document.addEventListener('DOMContentLoaded', function () {
//   const classTypeDropdown = document.getElementById('class_type');
//   const classNameDropdown = document.getElementById('class_name');
//   const selectedClassNameId = '<?php echo $selectedClassNameId; ?>'; // Get the selected class name ID from PHP

//   function updateClasses() {
//     const classType = classTypeDropdown.value;
//     if (!classType) {
//       classNameDropdown.innerHTML =
//         '<option value="">Select Class Name</option>';
//       return;
//     }

//     fetch(`editStudent.php?class_type=${classType}`)
//       .then((response) => response.json())
//       .then((data) => {
//         classNameDropdown.innerHTML =
//           '<option value="">Select Class Name</option>';
//         data.forEach((classObj) => {
//           const option = document.createElement('option');
//           option.value = classObj.id;
//           option.textContent = classObj.class_name;
//           classNameDropdown.appendChild(option);
//         });

//         // Set the selected class name
//         if (selectedClassNameId) {
//           classNameDropdown.value = selectedClassNameId;
//         }
//       })
//       .catch((error) => console.error('Error:', error));
//   }

//   // Initialize the class name dropdown based on the selected class type
//   if (classTypeDropdown.value) {
//     updateClasses();
//   }

//   classTypeDropdown.addEventListener('change', updateClasses);
// });
