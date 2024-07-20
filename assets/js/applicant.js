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
        var url = new URL(window.location.href);
        url.searchParams.set('search', searchValue);
        window.location.href = url.toString();
    });
});
document.getElementById('reset-filters').addEventListener('click', function() {
    // Clear URL parameters
    var url = new URL(window.location.href);
    url.searchParams.delete('search');
    url.searchParams.delete('job_title');
    url.searchParams.delete('position');
    url.searchParams.delete('rows_per_page');
    
    // Redirect to the new URL with reset parameters
    window.location.href = url.toString();
    
    // Optional: Reset search input field
    document.getElementById('search-input').value = '';
});

function changeRowsPerPage() {
    var rowsPerPage = document.getElementById('rows_per_page').value;
    var url = new URL(window.location.href);
    url.searchParams.set('rows_per_page', rowsPerPage);
    window.location.href = url.toString();
}
