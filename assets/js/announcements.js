document.getElementById('sortAsc').addEventListener('click', function() {
    window.location.href = 'announcements.php?order=asc';
});

document.getElementById('sortDesc').addEventListener('click', function() {
    window.location.href = 'announcements.php?order=desc';
});

document.addEventListener('DOMContentLoaded', function() {
    const archiveButtons = document.querySelectorAll('.archive-button');
    archiveButtons.forEach(button => {
        button.addEventListener('click', function(event) {
            const confirmed = confirm(
                'Are you sure you want to archive this announcement?');
            if (!confirmed) {
                event.preventDefault();
            }
        });
    });
});