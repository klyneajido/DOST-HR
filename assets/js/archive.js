function confirmAction(action, event) {
    event.preventDefault(); // Prevent the default link behavior
    const confirmed = confirm(`Are you sure you want to ${action} this item?`);
    if (confirmed) {
        window.location.href = event.target.href; // Redirect to the link if confirmed
    }
}

document.addEventListener('DOMContentLoaded', function() {
    var restoreJobModal = new bootstrap.Modal(document.getElementById('restoreJobModal'));
    var confirmRestoreLink = document.getElementById('confirmRestore');
    
    document.querySelectorAll('.restore-button').forEach(function(button) {
        button.addEventListener('click', function(event) {
            event.preventDefault();
            var jobId = this.getAttribute('data-id');
            confirmRestoreLink.setAttribute('href', 'PHP_Connections/restoreJob.php?id=' + jobId);
            restoreJobModal.show();
        });
    });
});

document.addEventListener('DOMContentLoaded', function() {
    var restoreAnnouncementModal = new bootstrap.Modal(document.getElementById('restoreAnnouncementModal'));
    var confirmAnnouncementRestoreLink = document.getElementById('confirmAnnouncementRestore');
    
    document.querySelectorAll('.restore-announcement-button').forEach(function(button) {
        button.addEventListener('click', function(event) {
            event.preventDefault();
            var announcementId = this.getAttribute('data-id');
            confirmAnnouncementRestoreLink.setAttribute('href', 'PHP_Connections/restoreAnnouncement.php?id=' + announcementId);
            restoreAnnouncementModal.show();
        });
    });
});

document.addEventListener('DOMContentLoaded', function() {
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

        fetch('PHP_Connections/deleteJob.php', {
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
                        location
                    .reload(); // Refresh the page after showing the success message
                    }, 2000);
                } else {
                    alert('Error: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
            });
    });

    let deleteAnnouncementId = null;

    document.querySelectorAll('.delete-announcement-button').forEach(function(button) {
        button.addEventListener('click', function(event) {
            event.preventDefault();
            deleteAnnouncementId = this.getAttribute('data-id');
            $('#passwordModalAnnouncement').modal('show');
        });
    });

    document.getElementById('deleteAnnouncementForm').addEventListener('submit', function(event) {
        event.preventDefault();
        const adminPassword = document.getElementById('adminPasswordAnnouncement').value;

        fetch('PHP_Connections/deleteAnnouncement.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    id: deleteAnnouncementId,
                    password: adminPassword
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    $('#passwordModalAnnouncement').modal('hide');
                    $('#successModalAnnouncement').modal('show');
                    setTimeout(function() {
                        location
                    .reload(); // Refresh the page after showing the success message
                    }, 2000);
                } else {
                    alert('Error: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
            });
    });
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
        const adminPassword = document.getElementById('adminPasswordApplicant').value;

        fetch('PHP_Connections/deleteApplicant.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    id: deleteApplicantId,
                    password: adminPassword
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    $('#passwordModalApplicant').modal('hide');
                    $('#successModalApplicant').modal('show');
                    setTimeout(function() {
                        location
                    .reload(); // Refresh the page after showing the success message
                    }, 2000);
                } else {
                    alert('Error: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
            });
    });
});

document.addEventListener("DOMContentLoaded", function(event) { 
    var scrollpos = localStorage.getItem('scrollpos');
    if (scrollpos) window.scrollTo(0, scrollpos);
});

window.onbeforeunload = function(e) {
    localStorage.setItem('scrollpos', window.scrollY);
};