function confirmAction(action, event) {
    event.preventDefault(); // Prevent the default link behavior
    const confirmed = confirm(`Are you sure you want to ${action} this item?`);
    if (confirmed) {
        window.location.href = event.target.href; // Redirect to the link if confirmed
    }
}

document.addEventListener('DOMContentLoaded', function() {
    // Search functionality
    const searchInput = document.getElementById('searchInput');
    const tableRows = document.querySelectorAll('tbody tr');

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

    // Restore job modal
    const restoreJobModal = new bootstrap.Modal(document.getElementById('restoreJobModal'));
    const confirmRestoreLink = document.getElementById('confirmRestore');
    
    document.querySelectorAll('.restore-button').forEach(function(button) {
        button.addEventListener('click', function(event) {
            event.preventDefault();
            const jobId = this.getAttribute('data-id');
            confirmRestoreLink.setAttribute('href', 'PHP_Connections/restore_job.php?id=' + jobId);
            restoreJobModal.show();
        });
    });

    // Delete job modal
    let deleteJobId = null;

    document.querySelectorAll('.delete-button').forEach(function(button) {
        button.addEventListener('click', function(event) {
            event.preventDefault();
            deleteJobId = this.getAttribute('data-id');
            $('#passwordModalJob').modal('show');
        });
    });

    document.getElementById('deleteForm').addEventListener('submit', function(event) {
        event.preventDefault();
        const password = document.getElementById('adminPassword').value;

        fetch('PHP_Connections/delete_job.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    id: deleteJobId,
                    password: password
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    $('#passwordModalJob').modal('hide');
                    $('#successModalJob').modal('show');
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
    document.addEventListener("DOMContentLoaded", function(event) { 
        var scrollpos = localStorage.getItem('scrollpos');
        if (scrollpos) window.scrollTo(0, scrollpos);
    });

    window.onbeforeunload = function(e) {
        localStorage.setItem('scrollpos', window.scrollY);
    };
});
    