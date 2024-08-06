// Get the modal
var modal = document.getElementById('deleteModal');

// Get the <span> element that closes the modal
var span = document.getElementsByClassName('close')[0];

// Get the buttons
var confirmDeleteBtn = document.getElementById('confirmDelete');
var cancelDeleteBtn = document.getElementById('cancelDelete');

// Get the element to display the student's name
var studentNameElement = document.getElementById('studentName');

// When the user clicks the delete button, open the modal
function deleteStudent(id, name) {
  modal.style.display = 'block';
  studentNameElement.textContent = name;
  confirmDeleteBtn.onclick = function () {
    window.location.href = './deleteStudent.php?id=' + id;
  };
}

// When the user clicks on <span> (x), close the modal
span.onclick = function () {
  modal.style.display = 'none';
};

// When the user clicks the cancel button, close the modal
cancelDeleteBtn.onclick = function () {
  modal.style.display = 'none';
};

// When the user clicks anywhere outside of the modal, close it
window.onclick = function (event) {
  if (event.target == modal) {
    modal.style.display = 'none';
  }
};

document.onkeydown = function (event) {
  if (event.key === 'Escape') {
    modal.style.display = 'none';
  }
};
