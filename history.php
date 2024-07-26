<?php
include_once 'PHP_Connections/fetch_history.php';?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
    <title>HRMO Admin</title>
    <link rel="shortcut icon" href="assets/img/dost_logo.png">
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/plugins/fontawesome/css/fontawesome.min.css">
    <link rel="stylesheet" href="assets/plugins/fontawesome/css/all.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        .table thead th {
            background-color: #f8f9fa;
            font-weight: bold;
        }

        .table tbody tr {
            transition: background-color 0.3s;
        }

        .table tbody tr:hover {
            background-color: #f1f1f1;
        }

        .table tbody td {
            vertical-align: middle;
        }

        .filter-container {
            display: flex;
            gap: 1rem;
            align-items: center;
            justify-content: flex-end;
        }

        .card-header {
            font-weight: bold;
        }
    </style>
</head>

<body class="scrollbar" id="style-5">
<?php include("logout_modal.php") ?>
    <div class="modal fade" id="passwordModal" tabindex="-1" aria-labelledby="passwordModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="passwordForm">
                    <div class="modal-header">
                        <h5 class="modal-title" id="passwordModalLabel">Confirm Deletion</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">X</button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="adminPassword" class="form-label">Admin Password</label>
                            <input type="password" class="form-control" id="adminPassword" required>
                            <input type="hidden" id="deleteHistoryId">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-danger">Delete</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal fade" id="successModal" tabindex="-1" aria-labelledby="successModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="successModalLabel">Success</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">X</button>
                </div>
                <div class="modal-body">
                    The record has been successfully deleted.
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    <div class="main-wrapper">
        <?php include("navbar.php") ?>
        <div class="page-wrapper">
            <div class="container-fluid">
                <div class="breadcrumb-path mb-4 my-4">
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="history.php"><img src="assets/img/dash.png" class="mr-2" alt="breadcrumb" />History</a>
                        </li>
                        <li class="breadcrumb-item active"></li>
                    </ul>

                </div>

                <!-- Card Section -->
                <div class="card">
                    <div class="card-header">
                        History Records
                    </div>
                    <div class="card-body">
                        <div class="mb-4 filter-container">
                            <div class="filter-group">
                                <select id="filterAction" class="form-control">
                                    <option value="" disabled selected>Filter by Action</option>
                                    <!-- Add options dynamically or manually as needed -->
                                    <option value="Archived Job" <?php echo $action_filter === 'Archived Job' ? 'selected' : ''; ?>>Archived Job</option>
                                    <option value="Archived Announcement" <?php echo $action_filter === 'Archived Announcement' ? 'selected' : ''; ?>>Archived Announcement</option>
                                    <option value="Added New Job" <?php echo $action_filter === 'Added New Job' ? 'selected' : ''; ?>>Added New Job</option>
                                    <option value="Added Announcement" <?php echo $action_filter === 'Added Announcement' ? 'selected' : ''; ?>>Added Announcement</option>
                                    <option value="Updated Announcement" <?php echo $action_filter === 'Updated Announcement' ? 'selected' : ''; ?>>Updated Announcement</option>
                                    <!-- Add other actions here -->
                                </select>
                            </div>
                            <div class="filter-group">
                                <select id="filterAdmin" class="form-control">
                                    <option value="" disabled selected>Filter by Admin</option>
                                    <?php
                                    // Fetch all admins for filter options
                                    $admins_query = "SELECT admin_id, name FROM admins";
                                    $admins_result = $mysqli->query($admins_query);
                                    while ($admin = $admins_result->fetch_assoc()) {
                                        $selected = isset($_GET['admin_id']) && $_GET['admin_id'] == $admin['admin_id'] ? 'selected' : '';
                                        echo "<option value=\"" . $admin['admin_id'] . "\" $selected>" . htmlspecialchars($admin['name']) . "</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="filter-group">
                                <button class="btn btn-link" id="sortAsc"><i class="fas fa-arrow-up"></i> Oldest First</button>
                                <button class="btn btn-link" id="sortDesc"><i class="fas fa-arrow-down"></i> Newest First</button>
                            </div>
                            <div class="filter-group">
                                <button class="btn btn-danger" id="resetFilters"><i class="fas fa-times"></i> Reset</button>
                            </div>
                        </div>

                        <!-- Table Section -->
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered">
                                <thead class="text-center">
                                    <tr>
                                        <th>Action</th>
                                        <th>Details</th>
                                        <th>Performed by</th>
                                        <th>Date</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="text-center">
                                    <?php if ($history_result->num_rows > 0) : ?>
                                        <?php while ($row = $history_result->fetch_assoc()) : ?>
                                            <tr>
                                                <td><?php echo htmlspecialchars($row['action']); ?></td>
                                                <td><?php echo htmlspecialchars($row['details']); ?></td>
                                                <td><?php echo htmlspecialchars($row['admin_name']); ?></td>
                                                <td><?php echo formatDate($row['date']); ?></td>
                                                <td>
                                                    <button data-id="<?php echo $row['id']; ?>" class="btn btn-danger btn-sm delete-history-btn">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        <?php endwhile; ?>
                                    <?php else : ?>
                                        <tr>
                                            <td colspan="5" class="text-center">No history records found.</td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="card-footer">
                        <!-- Pagination controls -->
                        <nav aria-label="Page navigation">
                            <ul class="pagination justify-content-center">
                                <?php if ($page > 1) : ?>
                                    <li class="page-item"><a class="page-link" href="history.php?page=1&sort=<?php echo htmlspecialchars($sort_order); ?>&search=<?php echo htmlspecialchars($search_term); ?>&admin_id=<?php echo htmlspecialchars($admin_id); ?>&action=<?php echo htmlspecialchars($action_filter); ?>">First</a></li>
                                    <li class="page-item"><a class="page-link" href="history.php?page=<?php echo $page - 1; ?>&sort=<?php echo htmlspecialchars($sort_order); ?>&search=<?php echo htmlspecialchars($search_term); ?>&admin_id=<?php echo htmlspecialchars($admin_id); ?>&action=<?php echo htmlspecialchars($action_filter); ?>">Previous</a></li>
                                <?php endif; ?>

                                <?php
                                // Display page numbers with a limit of 3 numbers around the current page
                                $start_page = max(1, $page - 1);
                                $end_page = min($total_pages, $page + 1);

                                if ($start_page > 1) {
                                    echo '<li class="page-item"><a class="page-link" href="history.php?page=1&sort=' . htmlspecialchars($sort_order) . '&search=' . htmlspecialchars($search_term) . '&admin_id=' . htmlspecialchars($admin_id) . '&action=' . htmlspecialchars($action_filter) . '">1</a></li>';
                                    if ($start_page > 2) {
                                        echo '<li class="page-item disabled"><span class="page-link">...</span></li>';
                                    }
                                }

                                for ($i = $start_page; $i <= $end_page; $i++) {
                                    echo '<li class="page-item' . ($i === $page ? ' active' : '') . '"><a class="page-link" href="history.php?page=' . $i . '&sort=' . htmlspecialchars($sort_order) . '&search=' . htmlspecialchars($search_term) . '&admin_id=' . htmlspecialchars($admin_id) . '&action=' . htmlspecialchars($action_filter) . '">' . $i . '</a></li>';
                                }

                                if ($end_page < $total_pages) {
                                    if ($end_page < $total_pages - 1) {
                                        echo '<li class="page-item disabled"><span class="page-link">...</span></li>';
                                    }
                                    echo '<li class="page-item"><a class="page-link" href="history.php?page=' . $total_pages . '&sort=' . htmlspecialchars($sort_order) . '&search=' . htmlspecialchars($search_term) . '&admin_id=' . htmlspecialchars($admin_id) . '&action=' . htmlspecialchars($action_filter) . '">' . $total_pages . '</a></li>';
                                }

                                if ($page < $total_pages) {
                                    echo '<li class="page-item"><a class="page-link" href="history.php?page=' . ($page + 1) . '&sort=' . htmlspecialchars($sort_order) . '&search=' . htmlspecialchars($search_term) . '&admin_id=' . htmlspecialchars($admin_id) . '&action=' . htmlspecialchars($action_filter) . '">Next</a></li>';
                                    echo '<li class="page-item"><a class="page-link" href="history.php?page=' . $total_pages . '&sort=' . htmlspecialchars($sort_order) . '&search=' . htmlspecialchars($search_term) . '&admin_id=' . htmlspecialchars($admin_id) . '&action=' . htmlspecialchars($action_filter) . '">Last</a></li>';
                                }
                                ?>
                            </ul>
                        </nav>
                    </div>
                </div>

            </div>
        </div>
    </div>
    <script>
        document.getElementById('sortAsc').addEventListener('click', function() {
            window.location.href = 'history.php?sort=asc&page=<?php echo $page; ?>&search=<?php echo htmlspecialchars($search_term); ?>&admin_id=<?php echo htmlspecialchars($admin_id); ?>&action=<?php echo htmlspecialchars($action_filter); ?>';
        });

        document.getElementById('sortDesc').addEventListener('click', function() {
            window.location.href = 'history.php?sort=desc&page=<?php echo $page; ?>&search=<?php echo htmlspecialchars($search_term); ?>&admin_id=<?php echo htmlspecialchars($admin_id); ?>&action=<?php echo htmlspecialchars($action_filter); ?>';
        });

        document.getElementById('filterAction').addEventListener('change', function() {
            const action = this.value;
            const adminId = document.getElementById('filterAdmin').value;
            window.location.href = 'history.php?page=1&sort=<?php echo htmlspecialchars($sort_order); ?>&search=<?php echo htmlspecialchars($search_term); ?>&admin_id=' + encodeURIComponent(adminId) + '&action=' + encodeURIComponent(action);
        });

        document.getElementById('filterAdmin').addEventListener('change', function() {
            const action = document.getElementById('filterAction').value;
            const adminId = this.value;
            window.location.href = 'history.php?page=1&sort=<?php echo htmlspecialchars($sort_order); ?>&search=<?php echo htmlspecialchars($search_term); ?>&admin_id=' + encodeURIComponent(adminId) + '&action=' + encodeURIComponent(action);
        });

        document.getElementById('resetFilters').addEventListener('click', function() {
            window.location.href = 'history.php?page=1&sort=<?php echo htmlspecialchars($sort_order); ?>';
        });
    </script>
    <script>
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
    </script>
     <script>
        document.addEventListener("DOMContentLoaded", function(event) { 
            var scrollpos = localStorage.getItem('scrollpos');
            if (scrollpos) window.scrollTo(0, scrollpos);
        });

        window.onbeforeunload = function(e) {
            localStorage.setItem('scrollpos', window.scrollY);
        };
    </script>

    <script src="assets/js/jquery-3.6.0.min.js"></script>
    <script src="assets/js/popper.min.js"></script>
    <script src="assets/js/bootstrap.min.js"></script>
    <script src="assets/js/feather.min.js"></script>
    <script src="assets/plugins/slimscroll/jquery.slimscroll.min.js"></script>
    <script src="assets/js/script.js"></script>
</body>

</html>