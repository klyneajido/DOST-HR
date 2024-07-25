    // Bootstrap custom validation
    (function() {
      'use strict';
      window.addEventListener('load', function() {
          var forms = document.getElementsByClassName('needs-validation');
          var validation = Array.prototype.filter.call(forms, function(form) {
              form.addEventListener('submit', function(event) {
                  if (form.checkValidity() === false) {
                      event.preventDefault();
                      event.stopPropagation();
                  }
                  form.classList.add('was-validated');
              }, false);
          });
      }, false);
    })();

    // Handle form submission
    function confirmSubmission(event) {
        var form = document.getElementById('profileForm');
        if (form.checkValidity()) {
            event.preventDefault();
            $('#confirmationModal').modal('show');
        } else {
            form.reportValidity();
        }
    }

    document.getElementById('profileForm').addEventListener('submit', confirmSubmission);

    // Handle confirmation
    document.getElementById('confirmUpdate').addEventListener('click', function() {
        document.getElementById('profileForm').submit();
    });

    // Success Modal OK button
    document.querySelector('#successModal .btn-primary').addEventListener('click', function() {
        window.location.href = 'profile.php';
    });