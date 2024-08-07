document.addEventListener('DOMContentLoaded', function() {
    // Get the clear button
    var clearButton = document.getElementById('clearSearch');
    
    // Add click event listener
    clearButton.addEventListener('click', function() {
        // Get the search input field
        var searchInput = document.getElementById('searchInput');

        // Clear the input field
        searchInput.value = '';

        // Submit the form to refresh the page
        document.getElementById('searchForm').submit();
    });
});
$(document).ready(function() {
    $('#confirmDeleteModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget);
        var adminId = button.data('admin-id');
        var modal = $(this);
        modal.find('#admin_id').val(adminId);
    });

    $('#deleteBtn').on('click', function() {
        var adminId = $('#admin_id').val();
        var currentPassword = $('#currentPassword').val();

        $.ajax({
            url: 'PHP_Connections/delete_account.php',
            type: 'POST',
            contentType: 'application/json',
            data: JSON.stringify({
                admin_id: adminId,
                currentPassword: currentPassword
            }),
            success: function(response) {
                var result = JSON.parse(response);
                if (result.success) {
                    $('#confirmDeleteModal').modal('hide');
                    $('#successModal').modal('show');
                } else {
                    alert(result.message);
                }
            },
            error: function() {
                alert('An error occurred.');
            }
        });
    });
});