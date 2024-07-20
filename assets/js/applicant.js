$(document).ready(function() {
    // Handle filter clicks
    $('.dropdown-item').click(function(e) {
        e.preventDefault(); // Prevent default anchor click behavior

        var filterType = $(this).data('filter');
        var filterValue = $(this).data('value');
        var url = new URL(window.location.href);
        
        // Update URL parameters
        if (filterType === 'job_title') {
            url.searchParams.set('job_title', filterValue);
        } else if (filterType === 'position') {
            url.searchParams.set('position', filterValue);
        }

        // Redirect to updated URL
        window.location.href = url.toString();
    });

    // Handle search input
    $('#search-input').on('input', function() {
        var searchValue = $(this).val();

        $.ajax({
            url: 'PHP_Connections/search.php', // Your search endpoint
            type: 'GET',
            data: {
                search: searchValue,
                job_title: getUrlParameter('job_title'),
                position: getUrlParameter('position')
            },
            success: function(response) {
                $('#table-container').html(response); // Update the table container with new data
            },
            error: function(xhr, status, error) {
                console.error('Search request failed:', error);
            }
        });
    });

    // Utility function to get URL parameters
    function getUrlParameter(name) {
        var url = new URL(window.location.href);
        return url.searchParams.get(name) || '';
    }

    // Event handler for status dropdown change
    $('.status-dropdown').change(function() {
        var status = $(this).val();
        var applicantId = $(this).data('applicant-id');

        $.ajax({
            url: 'PHP_Connections/update_status.php',
            type: 'POST',
            data: {
                id: applicantId,
                status: status
            },
            success: function(response) {
                console.log('Status updated successfully:', response);
            },
            error: function(xhr, status, error) {
                console.error('Failed to update status:', error);
                console.log('Response:', xhr.responseText);
            }
        });
    });

// Handle export button click
$('#export-button').click(function() {
    var sortColumn = getUrlParameter('sort_column') || 'id';
    var sortDirection = getUrlParameter('sort_direction') || 'ASC';
    var searchValue = getUrlParameter('search') || '';
    var jobTitle = getUrlParameter('job_title') || '';
    var position = getUrlParameter('position') || '';

    window.location.href = `PHP_Connections/export_to_csv.php?sort_column=${sortColumn}&sort_direction=${sortDirection}&search=${searchValue}&job_title=${jobTitle}&position=${position}`;
});

    // Change rows per page
    $('#rows_per_page').change(function() {
        changeRowsPerPage();
    });

    // Utility function to change rows per page
    function changeRowsPerPage() {
        var rowsPerPage = document.getElementById('rows_per_page').value;
        var url = new URL(window.location.href);
        url.searchParams.set('rows_per_page', rowsPerPage);
        window.location.href = url.toString();
    }

    // Reset filters
    $('#reset-filters').click(function() {
        var url = new URL(window.location.href);
        url.searchParams.delete('search');
        url.searchParams.delete('job_title');
        url.searchParams.delete('position');
        url.searchParams.delete('rows_per_page');
        
        window.location.href = url.toString();
        $('#search-input').val('');
    });
});
