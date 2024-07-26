$(function () {
    $('[data-toggle="tooltip"]').tooltip()
});

document.getElementById('sortAsc').addEventListener('click', function() {
    window.location.href =
        'history.php?sort=asc&page=<?php echo $page; ?>&search=<?php echo htmlspecialchars($search_term); ?>&admin_id=<?php echo htmlspecialchars($admin_id); ?>&action=<?php echo htmlspecialchars($action_filter); ?>';
});

document.getElementById('sortDesc').addEventListener('click', function() {
    window.location.href =
        'history.php?sort=desc&page=<?php echo $page; ?>&search=<?php echo htmlspecialchars($search_term); ?>&admin_id=<?php echo htmlspecialchars($admin_id); ?>&action=<?php echo htmlspecialchars($action_filter); ?>';
});

document.getElementById('filterAction').addEventListener('change', function() {
    const action = this.value;
    const adminId = document.getElementById('filterAdmin').value;
    window.location.href =
        'history.php?page=1&sort=<?php echo htmlspecialchars($sort_order); ?>&search=<?php echo htmlspecialchars($search_term); ?>&admin_id=' +
        encodeURIComponent(adminId) + '&action=' + encodeURIComponent(action);
});

document.getElementById('filterAdmin').addEventListener('change', function() {
    const action = document.getElementById('filterAction').value;
    const adminId = this.value;
    window.location.href =
        'history.php?page=1&sort=<?php echo htmlspecialchars($sort_order); ?>&search=<?php echo htmlspecialchars($search_term); ?>&admin_id=' +
        encodeURIComponent(adminId) + '&action=' + encodeURIComponent(action);
});

document.getElementById('resetFilters').addEventListener('click', function() {
    window.location.href = 'history.php?page=1&sort=<?php echo htmlspecialchars($sort_order); ?>';
});

document.addEventListener('DOMContentLoaded', () => {
    // Initialize Bootstrap modals
    const passwordModal = new bootstrap.Modal(document.getElementById('passwordModal'));
    const successModal = new bootstrap.Modal(document.getElementById('successModal'));

    // Attach click event listener to all delete buttons
    document.querySelectorAll('.delete-history-btn').forEach(button => {
        button.addEventListener('click', function(event) {
            event.preventDefault();
            const historyId = this.dataset.id;
            document.getElementById('deleteHistoryId').value = historyId;
            passwordModal.show(); // Show the password modal
        });
    });

    // Handle form submission for password verification and deletion
    document.getElementById('passwordForm').addEventListener('submit', function(event) {
        event.preventDefault();
        const historyId = document.getElementById('deleteHistoryId').value;
        const adminPassword = document.getElementById('adminPassword').value;

        fetch('PHP_Connections/deleteHistory.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    id: historyId,
                    password: adminPassword
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Show success modal
                    successModal.show();
                    // Optional: Reload the page after a delay to let user see the success message
                    setTimeout(() => window.location.reload(), 2000);
                } else {
                    alert('Invalid password.');
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
