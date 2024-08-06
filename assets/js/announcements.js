document.addEventListener('DOMContentLoaded', function() {
    // Sorting
    document.getElementById('sortAsc').addEventListener('click', function() {
        window.location.href = 'view_announcements.php?order=asc';
    });

    document.getElementById('sortDesc').addEventListener('click', function() {
        window.location.href = 'view_announcements.php?order=desc';
    });

    // Archive Button Handling
    var archiveAnnouncementModal = new bootstrap.Modal(document.getElementById('archiveAnnouncementModal'));
    var archiveForm = document.getElementById('archiveForm');
    var announcementIdInput = document.getElementById('announcement_id');

    document.querySelectorAll('.archive-button').forEach(function(button) {
        button.addEventListener('click', function(event) {
            event.preventDefault();
            var announcementId = this.getAttribute('data-id');
            announcementIdInput.value = announcementId;
            archiveAnnouncementModal.show(); // Show the modal for confirmation
        });
    });

    // Optional: Handling form submission within the modal
    document.getElementById('confirmArchiveButton').addEventListener('click', function() {
        archiveForm.submit(); // Submit the form when confirmation button is clicked
    });
});
