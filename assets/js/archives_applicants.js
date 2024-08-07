document.addEventListener('DOMContentLoaded', function() {
    // Real-time search functionality
    const searchInput = document.getElementById('searchInput');
    const tableRows = document.querySelectorAll('#applicantsTable tbody tr');

    searchInput.addEventListener('input', function() {
        const searchText = searchInput.value.toLowerCase();
        tableRows.forEach(row => {
            const cells = row.querySelectorAll('td');
            let match = false;
            cells.forEach(cell => {
                if (cell.textContent.toLowerCase().includes(searchText)) {
                    match = true;
                }
            });
            row.style.display = match ? '' : 'none';
        });
    });

    // Delete applicant modal handling
    let deleteApplicantId = null;

    document.querySelectorAll('.delete-applicant-button').forEach(function(button) {
        button.addEventListener('click', function(event) {
            event.preventDefault();
            deleteApplicantId = this.getAttribute('data-id');
            $('#passwordModalApplicant').modal('show');
        });
    });

    document.getElementById('deleteApplicantForm').addEventListener('submit', function(event) {
        event.preventDefault();
        const password = document.getElementById('adminPasswordApplicant').value;

        fetch('PHP_Connections/delete_applicant.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    id: deleteApplicantId,
                    password: password
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    $('#passwordModalApplicant').modal('hide');
                    $('#successModalApplicant').modal('show');
                    setTimeout(function() {
                        location.reload(); // Refresh the page after showing the success message
                    }, 2000);
                } else {
                    alert('Error: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
            });
    });

    // Preserve scroll position
    window.addEventListener('scroll', function() {
        localStorage.setItem('scrollpos', window.scrollY);
    });

    window.addEventListener('load', function() {
        var scrollpos = localStorage.getItem('scrollpos');
        if (scrollpos) window.scrollTo(0, scrollpos);
    });
});
