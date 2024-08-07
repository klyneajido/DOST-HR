// Ensure DOM is fully loaded before attaching event listeners
document.addEventListener('DOMContentLoaded', function() {
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
    var form = document.getElementById('profileForm');
    var formData = new FormData(form);

    fetch('PHP_Connections/update_profile.php', { // Adjust path as needed
        method: 'POST',
        body: formData
    })
    .then(response => response.json()) // Parse JSON response
    .then(result => {
        if (result.success) {
            window.location.href = 'view_profile.php'; // Redirect to view_profile.php after update
        } else {
            // Handle errors or display a message
            alert('Update failed: ' + result.error); // Display the error message
        }
    })
    .catch(error => {
        console.error('Error:', error);
    });
});

});
