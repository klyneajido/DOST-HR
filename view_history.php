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
    <link rel="stylesheet" href="assets/css/history.css">
</head>

<body class="scrollbar" id="style-5">
    <?php include("modal_logout.php") ?>
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
    <div class="modal fade" id="clearHistoryModal" tabindex="-1" aria-labelledby="clearHistoryModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="clearHistoryForm">
                    <div class="modal-header">
                        <h5 class="modal-title" id="clearHistoryModalLabel">Confirm Clear History</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">X</button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="adminPasswordClear" class="form-label">Admin Password</label>
                            <input type="password" class="form-control" id="adminPasswordClear" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-danger">Clear History</button>
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
    <div class="modal fade" id="clearHistorySuccessModal" tabindex="-1" aria-labelledby="clearHistorySuccessModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="clearHistorySuccessModalLabel">History Cleared</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">X</button>
            </div>
            <div class="modal-body">
                The history has been successfully cleared.
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
                            <a href="view_history.php"><img src="assets/img/dash.png" class="mr-2"
                                    alt="breadcrumb" />History</a>
                        </li>
                        <li class="breadcrumb-item active">Recent Activities</li>
                    </ul>

                </div>

                <!-- Card Section -->
                <div class="card mb-5">
                    <div class="card-header">
                        <strong>
                        History Records
                        </strong>
                    </div>
                    <div class="card-body">
                        <div class="filter-container d-flex">
                            <div class="filters d-flex col-md-6">
                                <div class="filter-group mr-2">
                                    <select id="filterAction" class="form-control">
                                        <option value="" disabled selected>Filter by Action</option>
                                        <!-- Add options dynamically or manually as needed -->
                                        <option value="Archived Job"
                                            <?php echo $action_filter === 'Archived Job' ? 'selected' : ''; ?>>Archived
                                            Job</option>
                                        <option value="Archived Announcement"
                                            <?php echo $action_filter === 'Archived Announcement' ? 'selected' : ''; ?>>
                                            Archived Announcement</option>
                                        <option value="Added New Job"
                                            <?php echo $action_filter === 'Added New Job' ? 'selected' : ''; ?>>Added
                                            New Job</option>
                                        <option value="Added Announcement"
                                            <?php echo $action_filter === 'Added Announcement' ? 'selected' : ''; ?>>
                                            Added Announcement</option>
                                        <option value="Updated Announcement"
                                            <?php echo $action_filter === 'Updated Announcement' ? 'selected' : ''; ?>>
                                            Updated Announcement</option>
                                        <option value="Updated Job"
                                            <?php echo $action_filter === 'Updated Job' ? 'selected' : ''; ?>>
                                            Updated Job</option>
                                        <option value="Archived Applicant"
                                            <?php echo $action_filter === 'Archived Applicant' ? 'selected' : ''; ?>>
                                            Archived Applicant</option>
                                        <!-- Add other actions here -->
                                    </select>
                                </div>
                                <div class="filter-group mr-2">
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
                                <div class="sort d-flex">
                                    <button type="button" id="sortAsc" data-toggle="tooltip" data-placement="top"
                                        title="Oldest First">
                                        <i class="fas fa-arrow-up"></i>
                                    </button>
                                    <button type="button" id="sortDesc" data-toggle="tooltip" data-placement="top"
                                        title="Newest First">
                                        <i class="fas fa-arrow-down"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="reset-btn col-md-6 d-flex justify-content-end align-items-center">
                                <button id="resetFilters" class="button mr-4">
                                    <svg class="svg-icon" fill="none" height="20" viewBox="0 0 20 20" width="20"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <g stroke="#ff342b" stroke-linecap="round" stroke-width="1.5">
                                            <path
                                                d="m3.33337 10.8333c0 3.6819 2.98477 6.6667 6.66663 6.6667 3.682 0 6.6667-2.9848 6.6667-6.6667 0-3.68188-2.9847-6.66664-6.6667-6.66664-1.29938 0-2.51191.37174-3.5371 1.01468">
                                            </path>
                                            <path
                                                d="m7.69867 1.58163-1.44987 3.28435c-.18587.42104.00478.91303.42582 1.0989l3.28438 1.44986">
                                            </path>
                                        </g>
                                    </svg>
                                </button>
                                <div class="dropdown">
                                <button class=" dropdown-toggle" type="button" id="dropdownMenuButton"
                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="fas fa-ellipsis-v"></i>
                                </button>
                                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                    <button class="dropdown-item" id="clearHistoryBtn" type="button" data-bs-toggle="modal" data-bs-target="#clearHistoryModal">
                                        Clear History
                                    </button>
                                </div>
                            </div>
                            </div>






                        </div>
                    </div>
                    <!-- Table Section -->
                    <div class="table-responsive">
                        <table class="table custom-table no-footer text-center">
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
                                        <button data-id="<?php echo $row['id']; ?>"
                                            class="btn btn-danger btn-sm delete-history-btn">
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

                    <div class="card-footer">
                        <!-- Pagination controls -->
                        <nav aria-label="Page navigation">
                            <ul class="pagination justify-content-center">
                                <?php if ($page > 1) : ?>
                                <li class="page-item"><a class="page-link"
                                        href="view_history.php?page=1&sort=<?php echo htmlspecialchars($sort_order); ?>&search=<?php echo htmlspecialchars($search_term); ?>&admin_id=<?php echo htmlspecialchars($admin_id); ?>&action=<?php echo htmlspecialchars($action_filter); ?>">First</a>
                                </li>
                                <li class="page-item"><a class="page-link"
                                        href="view_history.php?page=<?php echo $page - 1; ?>&sort=<?php echo htmlspecialchars($sort_order); ?>&search=<?php echo htmlspecialchars($search_term); ?>&admin_id=<?php echo htmlspecialchars($admin_id); ?>&action=<?php echo htmlspecialchars($action_filter); ?>">Previous</a>
                                </li>
                                <?php endif; ?>

                                <?php
                                // Display page numbers with a limit of 3 numbers around the current page
                                $start_page = max(1, $page - 1);
$end_page = min($total_pages, $page + 1);

if ($start_page > 1) {
    echo '<li class="page-item"><a class="page-link" href="view_history.php?page=1&sort=' . htmlspecialchars($sort_order) . '&search=' . htmlspecialchars($search_term) . '&admin_id=' . htmlspecialchars($admin_id) . '&action=' . htmlspecialchars($action_filter) . '">1</a></li>';
    if ($start_page > 2) {
        echo '<li class="page-item disabled"><span class="page-link">...</span></li>';
    }
}

for ($i = $start_page; $i <= $end_page; $i++) {
    echo '<li class="page-item' . ($i === $page ? ' active' : '') . '"><a class="page-link" href="view_history.php?page=' . $i . '&sort=' . htmlspecialchars($sort_order) . '&search=' . htmlspecialchars($search_term) . '&admin_id=' . htmlspecialchars($admin_id) . '&action=' . htmlspecialchars($action_filter) . '">' . $i . '</a></li>';
}

if ($end_page < $total_pages) {
    if ($end_page < $total_pages - 1) {
        echo '<li class="page-item disabled"><span class="page-link">...</span></li>';
    }
    echo '<li class="page-item"><a class="page-link" href="view_history.php?page=' . $total_pages . '&sort=' . htmlspecialchars($sort_order) . '&search=' . htmlspecialchars($search_term) . '&admin_id=' . htmlspecialchars($admin_id) . '&action=' . htmlspecialchars($action_filter) . '">' . $total_pages . '</a></li>';
}

if ($page < $total_pages) {
    echo '<li class="page-item"><a class="page-link" href="view_history.php?page=' . ($page + 1) . '&sort=' . htmlspecialchars($sort_order) . '&search=' . htmlspecialchars($search_term) . '&admin_id=' . htmlspecialchars($admin_id) . '&action=' . htmlspecialchars($action_filter) . '">Next</a></li>';
    echo '<li class="page-item"><a class="page-link" href="view_history.php?page=' . $total_pages . '&sort=' . htmlspecialchars($sort_order) . '&search=' . htmlspecialchars($search_term) . '&admin_id=' . htmlspecialchars($admin_id) . '&action=' . htmlspecialchars($action_filter) . '">Last</a></li>';
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

    </script>
    <script src="assets/js/jquery-3.6.0.min.js"></script>
    <script src="assets/js/popper.min.js"></script>
    <script src="assets/js/bootstrap.min.js"></script>
    <script src="assets/js/feather.min.js"></script>
    <script src="assets/plugins/slimscroll/jquery.slimscroll.min.js"></script>
    <script src="assets/js/script.js"></script>
    <script src="assets/js/history.js"></script>
     <script>
</body>

</html>