$(function() {
    // Initialize Bootstrap modals
    const passwordModal = new bootstrap.Modal(document.getElementById('passwordModal'));
    const successModal = new bootstrap.Modal(document.getElementById('successModal'));
    const clearHistoryModal = new bootstrap.Modal(document.getElementById('clearHistoryModal'));
    const clearHistorySuccessModal = new bootstrap.Modal(document.getElementById('clearHistorySuccessModal'));

    // Handle delete history button click
    document.querySelectorAll('.delete-history-btn').forEach(button => {
        button.addEventListener('click', function(event) {
            event.preventDefault();
            const historyId = this.dataset.id;
            document.getElementById('deleteHistoryId').value = historyId;
            passwordModal.show();
        });
    });

    // Handle password form submission for delete
    document.getElementById('passwordForm').addEventListener('submit', function(event) {
        event.preventDefault();
        const historyId = document.getElementById('deleteHistoryId').value;
        const adminPassword = document.getElementById('adminPassword').value;

        fetch('PHP_Connections/delete_history.php', {
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
                passwordModal.hide();
                successModal.show();
                setTimeout(() => window.location.reload(), 2000);
            } else {
                alert('Invalid password.');
            }
        })
        .catch(error => {
            console.error('Error:', error);
        });
    });

    // Handle clear history button click
    document.getElementById('clearHistoryBtn').addEventListener('click', function() {
        clearHistoryModal.show();
    });

    // Handle clear history form submission
    document.getElementById('clearHistoryForm').addEventListener('submit', function(event) {
        event.preventDefault();
        const adminPasswordClear = document.getElementById('adminPasswordClear').value;

        fetch('PHP_Connections/clear_history.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                password: adminPasswordClear
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                clearHistoryModal.hide();
                clearHistorySuccessModal.show();
                setTimeout(() => window.location.reload(), 2000);
            } else {
                alert('Invalid password.');
            }
        })
        .catch(error => {
            console.error('Error:', error);
        });
    });

    // Initialize Bootstrap tooltips
    $('[data-toggle="tooltip"]').tooltip();

    // Sorting buttons
    document.getElementById('sortAsc').addEventListener('click', function() {
        updateURL({ sort: 'asc' });
    });

    document.getElementById('sortDesc').addEventListener('click', function() {
        updateURL({ sort: 'desc' });
    });

    // Filter actions
    document.getElementById('filterAction').addEventListener('change', function() {
        updateURL({ action: this.value });
    });

    document.getElementById('filterAdmin').addEventListener('change', function() {
        updateURL({ admin_id: this.value });
    });

    document.getElementById('resetFilters').addEventListener('click', function() {
        window.location.href = 'view_history.php';
    });

    // Scroll position handling
    document.addEventListener("DOMContentLoaded", function() {
        const scrollpos = localStorage.getItem('scrollpos');
        if (scrollpos) window.scrollTo(0, scrollpos);
    });

    window.onbeforeunload = function() {
        localStorage.setItem('scrollpos', window.scrollY);
    };

    // Helper function to update the URL with new parameters
    function updateURL(params) {
        const url = new URL(window.location.href);
        Object.keys(params).forEach(key => url.searchParams.set(key, params[key]));
        url.searchParams.set('page', '1'); // Reset to the first page on filter/sort change
        window.location.href = url.toString();
    }
});
