// Ensure DOM is fully loaded before executing JavaScript
document.addEventListener('DOMContentLoaded', function() {
    // Select the textarea element
    const descriptionTextarea = document.getElementById('description');
    // Select the span element for character count
    const descriptionCount = document.getElementById('description-count');

    // Update character count on input event
    descriptionTextarea.addEventListener('input', function() {
        const currentLength = descriptionTextarea.value.length;
        descriptionCount.textContent = currentLength;

        // Optionally limit the textarea length to 300 characters
        if (currentLength > 300) {
            descriptionTextarea.value = descriptionTextarea.value.substring(0, 300);
            descriptionCount.textContent = 300;
        }
    });

    // Initialize character count on page load
    descriptionCount.textContent = descriptionTextarea.value.length;
});

$(document).ready(function() {
    $('#confirmUpdate').click(function() {
        $('#announcementForm').submit();
    });
});