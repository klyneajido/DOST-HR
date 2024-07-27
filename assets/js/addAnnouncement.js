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


   // Ensure DOM is fully loaded before executing JavaScript
   document.addEventListener('DOMContentLoaded', function() {
    // Select the textarea element
    const descriptionTextarea = document.getElementById('description');
    // Select the span element for character count
    const descriptionCount = document.getElementById('description-count');

    // Update character count on input event
    descriptionTextarea.addEventListener('input', function() {
        const currentLength = descriptionTextarea.value.length;
        descriptionCount.textContent = currentLength;

        // Optionally limit the textarea length to 300 characters
        if (currentLength > 300) {
            descriptionTextarea.value = descriptionTextarea.value.substring(0, 300);
            descriptionCount.textContent = 300;
        }
    });

    // Initialize character count on page load
    descriptionCount.textContent = descriptionTextarea.value.length;
});