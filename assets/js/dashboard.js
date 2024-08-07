document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.toggle-positions').forEach(function(toggle) {
        toggle.addEventListener('click', function(event) {
            event.preventDefault();
            var id = toggle.getAttribute('data-id');
            var positionsDiv = document.getElementById('positions-' + id);
            if (positionsDiv.style.display === 'none') {
                positionsDiv.style.display = 'block';
                toggle.querySelector('i').setAttribute('data-feather', 'chevron-up');
            } else {
                positionsDiv.style.display = 'none';
                toggle.querySelector('i').setAttribute('data-feather', 'chevron-down');
            }
            feather.replace();
        });
    });
});