$(document).ready(function() {
    // Function to update status color and border based on selected value
    function updateStatusColor(selectElement) {
        // Remove existing status-related classes
        selectElement.removeClass('shortlist interview endorsed');
        
        // Add the class based on the selected value
        if (selectElement.val() === 'Shortlisted') {
            selectElement.addClass('shortlist');
        } else if (selectElement.val() === 'Interview') {
            selectElement.addClass('interview');
        } else if (selectElement.val() === 'Endorsed') {
            selectElement.addClass('endorsed');
        }
    }

    // Initialize the color of the status dropdown on page load
    $('.status-dropdown').each(function() {
        updateStatusColor($(this));
    });

    // Handle status dropdown change
    $('.status-dropdown').change(function() {
        var status = $(this).val();
        var applicantId = $(this).data('applicant-id');
        var dateContainer = $('#dateContainer' + applicantId);

        console.log('Status changed to:', status); // Debugging line
        console.log('Applicant ID:', applicantId); // Debugging line

        // Update the status in the database
        $.ajax({
            url: 'PHP_Connections/update_status.php',
            type: 'POST',
            data: {
                id: applicantId,
                status: status
            },
            success: function(response) {
                console.log('Status updated successfully:', response);

                // Update the status color
                updateStatusColor($('.status-dropdown[data-applicant-id="' + applicantId + '"]'));

                // Show/hide the interview date picker
                if (status === 'Interview') {
                    // Create the date picker form
                    var interviewFormHtml = `
                        <form id="interviewForm${applicantId}" method="POST" action="PHP_Connections/interviewDate.php" class="d-flex align-items-center w-100">
                            <input type="hidden" name="applicant_id" value="${applicantId}">
                            <input type="datetime-local" class="form-control w-100 me-2" name="interview_date" />
                        </form>
                    `;

                    console.log('Appending form to:', '#dateContainer' + applicantId); // Debugging line

                    // Insert or replace the form inside dateContainer
                    dateContainer.html(interviewFormHtml);

                    // Automatically submit the form when the date is picked
                    dateContainer.find('input[name="interview_date"]').change(function() {
                        $(this).closest('form').submit();
                    });
                } else {
                    dateContainer.find('#interviewForm' + applicantId).remove();
                }
            },
            error: function(xhr, status, error) {
                console.error('Failed to update status:', error);
            }
        });
    });

    // Handle dropdown filters
    $('.dropdown-item').click(function(e) {
        e.preventDefault(); // Prevent default anchor click behavior

        var filterType = $(this).data('filter');
        var filterValue = $(this).data('value');
        var url = new URL(window.location.href);

        // Update URL parameters
        if (filterType === 'status') {
            url.searchParams.set('status', filterValue);
        } else if (filterType === 'job_title') {
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

    // Handle export button click
    $('#export-button').click(function() {
        var sortColumn = getUrlParameter('sort_column') || 'id';
        var sortDirection = getUrlParameter('sort_direction') || 'ASC';
        var searchValue = getUrlParameter('search') || '';
        var jobTitle = getUrlParameter('job_title') || '';
        var position = getUrlParameter('position') || '';
        var status = getUrlParameter('status') || '';

        window.location.href = `PHP_Connections/export_to_csv.php?export=true&sort_column=${sortColumn}&sort_direction=${sortDirection}&search=${searchValue}&job_title=${jobTitle}&position=${position}&status=${status}`;
    });

    // Change rows per page
    $('#rows_per_page').change(function() {
        changeRowsPerPage();
    });

    // Reset filters
    $('#reset-filters').click(function() {
        var url = new URL(window.location.href);
        url.searchParams.delete('search');
        url.searchParams.delete('status');
        url.searchParams.delete('job_title');
        url.searchParams.delete('position');
        url.searchParams.delete('rows_per_page');
        
        window.location.href = url.toString();
        $('#search-input').val('');
    });

    // Utility function to get URL parameters
    function getUrlParameter(name) {
        var url = new URL(window.location.href);
        return url.searchParams.get(name) || '';
    }

    // Utility function to change rows per page
    function changeRowsPerPage() {
        var rowsPerPage = document.getElementById('rows_per_page').value;
        var url = new URL(window.location.href);
        url.searchParams.set('rows_per_page', rowsPerPage);
        window.location.href = url.toString();
    }

    // Automatically submit the form when the date is picked
    $(document).on('change', 'input[name="interview_date"]', function() {
        var form = $(this).closest('form');
        $.ajax({
            url: form.attr('action'),
            type: form.attr('method'),
            data: form.serialize(),
            success: function(response) {
                console.log('Date saved successfully:', response);
            },
            error: function(xhr, status, error) {
                console.error('Failed to save date:', error);
            }
        });
    });
});

// Handle dropdown toggle and close
function toggleDropdown(event) {
    event.stopPropagation();
    var dropdownContent = event.currentTarget.nextElementSibling;
    dropdownContent.style.display = dropdownContent.style.display === 'block' ? 'none' : 'block';
}

window.onclick = function(event) {
    var dropdowns = document.getElementsByClassName("dropdown-content");
    for (var i = 0; i < dropdowns.length; i++) {
        var openDropdown = dropdowns[i];
        if (openDropdown.style.display === 'block') {
            openDropdown.style.display = 'none';
        }
    }
};

// Remove applicant function
function removeApplicant(id) {
    if (confirm("Are you sure you want to remove this applicant?")) {
        window.location.href = `PHP_Connections/remove_applicant.php?id=${id}`;
    }
}

// Update interview date function
function updateInterviewDate(applicantId) {
    var form = document.getElementById('interviewForm' + applicantId);
    var formData = new FormData(form);

    var xhr = new XMLHttpRequest();
    xhr.open('POST', 'PHP_Connections/interviewDate.php', true);

    xhr.onload = function() {
        if (xhr.status === 200) {
            var response = JSON.parse(xhr.responseText);
            if (response.success) {
                // Successfully updated, refresh the page
                location.reload();
            } else {
                alert('Error: ' + response.message);
            }
        } else {
            alert('Request failed. Returned status of ' + xhr.status);
        }
    };

    xhr.send(formData);
}

// Handle sortable table columns
document.addEventListener("DOMContentLoaded", function () {
    const table = document.querySelector(".table");
    const headers = table.querySelectorAll(".sortable");
    let sortDirection = false;

    headers.forEach(header => {
        header.addEventListener("click", () => {
            const column = header.dataset.column;
            sortDirection = !sortDirection;
            const direction = sortDirection ? 1 : -1;
            const columnIndex = Array.from(headers).indexOf(header) + 1; // 1-based index
            const rows = Array.from(table.querySelectorAll("tbody tr"));

            rows.sort((a, b) => {
                const aCell = a.querySelector(`td:nth-child(${columnIndex})`);
                const bCell = b.querySelector(`td:nth-child(${columnIndex})`);

                const aText = aCell.textContent.trim();
                const bText = bCell.textContent.trim();

                if (column === 'application_date') {
                    const aDate = parseDate(aText);
                    const bDate = parseDate(bText);
                    return (aDate - bDate) * direction;
                }

                return aText.localeCompare(bText) * direction;
            });

            table.querySelector("tbody").append(...rows);
            updateSortIcons(headers, header, sortDirection);
        });
    });

    function updateSortIcons(headers, activeHeader, sortDirection) {
        headers.forEach(header => {
            const icon = header.querySelector(".fas");
            if (header === activeHeader) {
                icon.classList.remove("fa-sort-up", "fa-sort-down");
                icon.classList.add(sortDirection ? "fa-sort-up" : "fa-sort-down");
            } else {
                icon.classList.remove("fa-sort-up", "fa-sort-down");
            }
        });
    }

    function parseDate(dateString) {
        return new Date(dateString);
    }
});
