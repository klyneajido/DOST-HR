// Example starter JavaScript for disabling form submissions if there are invalid fields
(function () {
  'use strict';

  // Fetch all the forms we want to apply custom Bootstrap validation styles to
  var forms = document.querySelectorAll('.needs-validation');

  // Loop over them and prevent submission
  Array.prototype.slice.call(forms)
      .forEach(function (form) {
          form.addEventListener('submit', function (event) {
              if (!form.checkValidity()) {
                  event.preventDefault();
                  event.stopPropagation();
              }

              form.classList.add('was-validated');
          }, false);
      });
})();

// Add field dynamically
function addField(section) {
  const container = document.getElementById(`${section}-container`);
  const div = document.createElement('div');
  div.className = 'd-flex mb-2';

  const input = document.createElement('input');
  input.type = 'text';
  input.name = `${section}[]`;
  input.className = 'form-control';
  input.placeholder = `Enter ${section.replace(/([A-Z])/g, ' $1').toLowerCase()}`;
  input.autocomplete = 'off';
  input.required = true;

  const button = document.createElement('button');
  button.type = 'button';
  button.className = 'btn btn-outline-danger ml-2';
  button.textContent = '-';
  button.onclick = function () { removeField(this); };

  div.appendChild(input);
  div.appendChild(button);
  container.appendChild(div);
}

// Remove field dynamically
function removeField(button) {
  const div = button.parentElement;
  div.remove();
}

// Function to validate salary input
function validateSalaryInput(input) {
  const maxDigits = 7;
  const maxDecimalPlaces = 2;

  let value = input.value;
  let parts = value.split('.');

  if (parts[0].length > maxDigits) {
      input.value = parts[0].slice(0, maxDigits) + (parts[1] ? '.' + parts[1].slice(0, maxDecimalPlaces) : '');
  }

  if (parts[1] && parts[1].length > maxDecimalPlaces) {
      input.value = parts[0] + '.' + parts[1].slice(0, maxDecimalPlaces);
  }
}
$(document).ready(function() {
  $('#confirmUpdateJob').on('click', function() {
      $('#editJobForm').submit();
  });
});
