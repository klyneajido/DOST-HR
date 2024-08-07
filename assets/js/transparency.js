    // Display selected file name in the custom file input
    document.querySelector('.custom-file-input').addEventListener('change', function(e) {
        var fileName = document.getElementById("customFile").files[0].name;
        var nextSibling = e.target.nextElementSibling;
        nextSibling.innerText = fileName;
    });

    // Filter documents based on search input
    document.getElementById('searchBar').addEventListener('keyup', function() {
        var searchValue = this.value.toLowerCase();
        var documentItems = document.querySelectorAll('.document-item');
        var noDocumentsFound = true;
        
        documentItems.forEach(function(item) {
            var itemName = item.textContent.toLowerCase();
            if (itemName.includes(searchValue)) {
                item.style.display = 'block';
                noDocumentsFound = false;
            } else {
                item.style.display = 'none';
            }
        });

        if (noDocumentsFound) {
            document.getElementById('noDocumentsFound').style.display = 'block';
        } else {
            document.getElementById('noDocumentsFound').style.display = 'none';
        }
    });