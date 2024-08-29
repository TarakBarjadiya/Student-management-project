console.log('dynamic content called');
function updateClassFields() {
  console.log('function called');
  var classTypeElement = document.getElementById('class-type');
  var classType = classTypeElement.value;
  var standardDegreeField = document.getElementById('standard-degree');
  var otherClassNameField = document.getElementById('other-class-name');

  // Clear previous options
  standardDegreeField.innerHTML =
    '<option value="">--Select Standard/Degree--</option>';

  // Populate options based on class type
  if (classType === 'college') {
    var degrees = ['BCA', 'BCom', 'BBA', 'BA', 'BSc', 'BTech'];
    degrees.forEach(function (degree) {
      var option = document.createElement('option');
      option.value = degree;
      option.text = degree;
      standardDegreeField.appendChild(option);
    });
    standardDegreeField.style.display = 'block';
    standardDegreeField.setAttribute('required', 'required');
    otherClassNameField.style.display = 'none';
    otherClassNameField.removeAttribute('required');
  } else if (classType === 'other') {
    standardDegreeField.style.display = 'none';
    standardDegreeField.removeAttribute('required');
    otherClassNameField.style.display = 'block';
    otherClassNameField.setAttribute('required', 'required');
  }
}
