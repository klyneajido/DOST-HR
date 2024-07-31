document.addEventListener('DOMContentLoaded', function() {
    //TOGGLE INDIV JOB
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

    //TOGGLE STATUS
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
            if (dropdown.style.display === 'block') {
                dropdown.style.display = 'none';
            }
        });
    });

});

document.getElementById('reset-filters').addEventListener('click', function() {
    window.location.href = 'viewJob.php';
});

document.querySelectorAll('.archive-btn').forEach(function(button) {
    button.addEventListener('click', function(event) {
        event.preventDefault();
        var result = confirm('Are you sure you want to archive this job?');
        if (result) {
            window.location.href = this.href;
        }
    });
});
