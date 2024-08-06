document.addEventListener('DOMContentLoaded', function() {
    // TOGGLE INDIV JOB
    // Toggle dropdown on button click
    document.querySelectorAll('.dropdown-toggle').forEach(function(button) {
        button.addEventListener('click', function(event) {
            event.stopPropagation(); // Prevent Bootstrap from closing the dropdown
            var dropdownContent = this.nextElementSibling;
            dropdownContent.style.display = dropdownContent.style.display === 'block' ? 'none' : 'block';
        });
    });

    // Close the dropdown if the user clicks outside of it
    window.addEventListener('click', function(event) {
        var dropdowns = document.querySelectorAll('.dropdown-menu');
        dropdowns.forEach(function(dropdown) {
            if (dropdown.style.display === 'block' && !dropdown.contains(event.target)) {
                dropdown.style.display = 'none';
            }
        });
    });

    // TOGGLE STATUS
    // Toggle dropdown on button click
    document.querySelectorAll('.dropdown-filter-toggle').forEach(function(button) {
        button.addEventListener('click', function(event) {
            event.stopPropagation(); // Prevent Bootstrap from closing the dropdown
            var dropdownContent = event.currentTarget.nextElementSibling;
            dropdownContent.style.display = dropdownContent.style.display === 'block' ? 'none' : 'block';
        });
    });

    // Close the dropdown if the user clicks outside of it
    window.addEventListener('click', function(event) {
        var dropdowns = document.querySelectorAll('.dropdown-filter-menu');
        dropdowns.forEach(function(dropdown) {
            if (dropdown.style.display === 'block' && !dropdown.contains(event.target)) {
                dropdown.style.display = 'none';
            }
        });
    });

    // Reset filters
    document.getElementById('reset-filters').addEventListener('click', function() {
        window.location.href = 'view_jobs.php';
    });

    // Archive button click event
    document.querySelectorAll('.dropdown-menu .dropdown-item[data-target="#confirmArchiveModal"]').forEach(function(button) {
        button.addEventListener('click', function(event) {
            event.preventDefault(); // Prevent default action
            var jobId = this.getAttribute('data-job-id');
            $('#confirmArchiveButton').attr('data-job-id', jobId); // Set job ID in the confirm button
            $('#confirmArchiveModal').modal('show'); // Show the modal for confirmation
        });
    });

    // Handle confirm archive button click
    $('#confirmArchiveButton').on('click', function() {
        var jobId = $(this).attr('data-job-id'); // Get job ID from the button's data attribute
        if (jobId) {
            $.ajax({
                url: 'PHP_Connections/archive_job.php',
                type: 'POST',
                data: { job_id: jobId },
                success: function(response) {
                    console.log('Server Response:', response); // Log the raw response
                    try {
                        var jsonResponse;
                        if (typeof response === 'string') {
                            jsonResponse = JSON.parse(response);
                        } else {
                            jsonResponse = response; // Directly use the object if it's already parsed
                        }

                        if (jsonResponse.success) {
                            
                            window.location.reload();
                        } else if (jsonResponse.error) {
                            
                        }
                    } catch (e) {
                        console.error('Error parsing JSON response:', e);
                        alert('An unexpected error occurred.');
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Archive failed:', error);
                    alert('An error occurred while archiving the job.');
                }
            });
            $('#confirmArchiveModal').modal('hide'); // Hide the modal after archiving
        } else {
            console.error('No job ID found for archiving.');
            alert('No job ID found.');
        }
    });
});
