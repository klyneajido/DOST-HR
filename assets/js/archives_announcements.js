document.addEventListener('DOMContentLoaded', function() {
    // Real-time search functionality
    const searchInput = document.getElementById('searchInput');
    const tableRows = document.querySelectorAll('#announcementsTable tbody tr');

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

    // Delete announcement modal handling
    let deleteAnnouncementId = null;
    const deleteButtons = document.querySelectorAll('.delete-announcement-button');
    deleteButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            deleteAnnouncementId = this.getAttribute('data-id');
            document.getElementById('deleteAnnouncementId').value = deleteAnnouncementId;
            $('#deleteAnnouncementModal').modal('show');
        });
    });

    // Form submission for deleting announcement
    const deleteAnnouncementForm = document.getElementById('deleteAnnouncementForm');
    deleteAnnouncementForm.addEventListener('submit', function(e) {
        e.preventDefault();

        const announcementId = document.getElementById('deleteAnnouncementId').value;
        const password = document.getElementById('adminPasswordAnnouncement').value;

        fetch('PHP_Connections/delete_announcement.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                id: announcementId,
                password: password
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
        // Refresh the page
        location.reload();
            } else {
                alert('Error: ' + data.message);
            }
        })
        .catch(error => console.error('Error:', error));
    });

    // Restore announcement modal handling
    let restoreAnnouncementId = null;
    const restoreButtons = document.querySelectorAll('.restore-announcement-button');
    restoreButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            restoreAnnouncementId = this.getAttribute('data-id');
            document.getElementById('confirmAnnouncementRestore').href = `PHP_Connections/restore_announcement.php?id=${restoreAnnouncementId}`;
            $('#restoreAnnouncementModal').modal('show');
        });
    });
});
