document.addEventListener("DOMContentLoaded", function () {
    const table = document.querySelector(".table");
    const headers = table.querySelectorAll(".sortable");
    let sortDirection = false;

    headers.forEach(header => {
        header.addEventListener("click", () => {
            const column = header.dataset.column;
            sortDirection = !sortDirection;
            const direction = sortDirection ? 1 : -1;
            const rows = Array.from(table.querySelectorAll("tbody tr"));

            rows.sort((a, b) => {
                const aText = a.querySelector(`td:nth-child(${Array.from(headers).indexOf(header) + 1})`).textContent;
                const bText = b.querySelector(`td:nth-child(${Array.from(headers).indexOf(header) + 1})`).textContent;
                return aText.localeCompare(bText) * direction;
            });

            table.querySelector("tbody").append(...rows);
            updateSortIcons(headers, header, sortDirection);
        });
    });

    function updateSortIcons(headers, activeHeader, sortDirection) {
        headers.forEach(header => {
            const icon = header.querySelector(".fas");
            if (header === activeHeader) {
                icon.classList.remove("fa-sort-up", "fa-sort-down");
                icon.classList.add(sortDirection ? "fa-sort-up" : "fa-sort-down");
            } else {
                icon.classList.remove("fa-sort-up", "fa-sort-down");
            }
        });
    }
});
// Function to handle status update
document.addEventListener("DOMContentLoaded", function () {
    const statusDropdowns = document.querySelectorAll(".status-dropdown");

    statusDropdowns.forEach(dropdown => {
        dropdown.addEventListener("change", function () {
            const applicantId = this.dataset.applicantId;
            const newStatus = this.value;

            const formData = new FormData();
            formData.append("applicant_id", applicantId);
            formData.append("status", newStatus);

            fetch("php_connections/update_status.php", {
                method: "POST",
                body: formData,
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert("Status updated successfully!");
                    } else {
                        alert("Failed to update status.");
                    }
                })
                .catch(error => {
                    console.error("Error:", error);
                    alert("An error occurred while updating the status.");
                });
        });
    });
});