// Example starter JavaScript for disabling form submissions if there are invalid fields
(function () {
    'use strict'
  
    // Fetch all the forms we want to apply custom Bootstrap validation styles to
    var forms = document.querySelectorAll('.needs-validation')
  
    // Loop over them and prevent submission
    Array.prototype.slice.call(forms)
      .forEach(function (form) {
        form.addEventListener('submit', function (event) {
          if (!form.checkValidity()) {
            event.preventDefault()
            event.stopPropagation()
          }
  
          form.classList.add('was-validated')
        }, false)
      })
  })()
  function addField(containerId) {
    const container = document.getElementById(containerId + '-container');
    const input = document.createElement('input');
    input.type = 'text';
    input.name = containerId + '[]';
    input.className = 'form-control mb-2';
    
    // Set placeholder based on containerId
    const placeholders = {
        'educationrequirement': 'Enter Education Requirement',
        'experienceortraining': 'Enter Experience or Training Requirement',
        'dutiesandresponsibilities': 'Enter Duty or Responsibility'
    };
    
    input.placeholder = placeholders[containerId] || 'Enter Requirement';
    
    const removeButton = document.createElement('button');
    removeButton.type = 'button';
    removeButton.className = 'btn btn-outline-danger ml-2';
    removeButton.textContent = '-';
    removeButton.onclick = function() {
        container.removeChild(input.parentNode);
    };
    
    const div = document.createElement('div');
    div.className = 'd-flex mb-2';
    div.appendChild(input);
    div.appendChild(removeButton);
    
    container.appendChild(div);
  }
  
  
  function validateSalaryInput(input) {
    const maxDigits = 7;
    const maxDecimalPlaces = 2;
  
    let value = input.value;
    let parts = value.split('.');
  
    if (parts[0].length > maxDigits) {
        input.value = parts[0].slice(0, maxDigits) + (parts[1] ? '.' + parts[1] :
            '');
    }
  
    if (parts[1] && parts[1].length > maxDecimalPlaces) {
        input.value = parts[0] + '.' + parts[1].slice(0, maxDecimalPlaces);
    }
  }