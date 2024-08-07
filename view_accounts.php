<?php
include("PHP_Connections/check_user.php");
?>
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
    <link rel="stylesheet" href="assets/css/accounts.css">
</head>

<body class="scrollbar" id="style-5">

    <?php include("modal_logout.php") ?>

    <div class="main-wrapper">
        <?php include("navbar.php") ?>

        <div class="page-wrapper">
            <div class="container-fluid">

                <!-- Breadcrumb Path -->
                <div class="breadcrumb-path mb-4 my-4">
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href=""><img src="assets/img/dash.png" class="mr-2" alt="breadcrumb" />Accounts</a>
                        </li>
                        <li class="breadcrumb-item active">Admin</li>
                    </ul>
                </div>

                <!-- Admin Accounts Card -->
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Admin Accounts</h3>
                    </div>
                    <div>
                        <!-- Search Form -->
                        <div class="col-md-12  d-flex my-2">
                            <div class="col-md-8 d-flex justify-content-end search-action">
                                <form id="searchForm" method="GET" action="" class="col-md-12 d-flex">
                                    <?php
                                $searchTerm = isset($_GET['search']) ? $_GET['search'] : '';
                                ?>
                                    <input type="text" id="searchInput" name="search"
                                        value="<?php echo htmlspecialchars($searchTerm); ?>"
                                        class="form-control form-control-rounded" placeholder="Search by name or email">
                                    <button type="submit" class="btn btn-search"><i class="fas fa-search"></i></button>
                                </form>

                            </div>
                            <div class="col-md-4 d-flex justify-content-end">
                                <button id="clearSearch" class="button ">
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
                            </div>
                        </div>


                        <!-- Table -->
                        <div class="table-responsive">
                            <table class="table table-striped" id="adminTable">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Username</th>
                                        <th>Email</th>
                                        <th>User Type</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    // Build query with search filter
                                    $query = "SELECT admin_id, name, username, email, authority FROM admins WHERE authority != 'superadmin'";
                                    if ($searchTerm) {
                                        $searchTerm = '%' . $mysqli->real_escape_string($searchTerm) . '%';
                                        $query .= " AND (name LIKE ? OR email LIKE ?)";
                                    }

                                    // Prepare and execute statement
                                    $stmt = $mysqli->prepare($query);
                                    if ($searchTerm) {
                                        $stmt->bind_param("ss", $searchTerm, $searchTerm);
                                    }
                                    $stmt->execute();
                                    $result = $stmt->get_result();

                                    // Display results
                                    if ($result->num_rows > 0) {
                                        while ($row = $result->fetch_assoc()) {
                                            echo "<tr>";
                                            echo "<td>" . htmlspecialchars($row['name']) . "</td>";
                                            echo "<td>" . htmlspecialchars($row['username']) . "</td>";
                                            echo "<td>" . htmlspecialchars($row['email']) . "</td>";
                                            echo "<td>" . htmlspecialchars($row['authority']) . "</td>";
                                            echo "<td>
                                                <a href='edit_account.php?id=" . $row['admin_id'] . "' class='btn btn-sm btn-warning'><i class='fas fa-edit'></i></a>
                                                <button type='button' class='btn btn-sm btn-danger' data-toggle='modal' data-target='#confirmDeleteModal' data-admin-id='" . $row['admin_id'] . "'><i class='fas fa-trash-alt'></i></button>
                                            </td>";
                                            echo "</tr>";
                                        }
                                    } else {
                                        echo "<tr><td colspan='5' class='text-center'>No accounts found.</td></tr>";
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Add Account Button -->
                <div class="user-menu">
                    <a href="add_account.php" class="btn btn-info btn-lg float-add-btn" title="Add Account">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="white"
                            class="bi bi-plus-circle-fill mb-1" viewBox="0 0 16 16">
                            <path
                                d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0M8.5 4.5a.5.5 0 0 0-1 0v3h-3a.5.5 0 0 0 0 1h3v3a.5.5 0 0 0 1 0v-3h3a.5.5 0 0 0 0-1h-3z" />
                        </svg>
                        Add Account
                    </a>
                </div>

                <!-- Mobile Add Account Button -->
                <div class="mobile-user-menu show">
                    <a href="add_account.php" class="btn btn-info btn-lg float-add-btn px-3 py-2" title="Add Account">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="white"
                            class="bi bi-plus-circle-fill mb-1" viewBox="0 0 16 16">
                            <path
                                d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0M8.5 4.5a.5.5 0 0 0-1 0v3h-3a.5.5 0 0 0 0 1h3v3a.5.5 0 0 0 1 0v-3h3a.5.5 0 0 0 0-1h-3z" />
                        </svg>
                    </a>
                </div>

                <!-- Pop-up Notification -->
                <?php if (!empty($success_message)) : ?>
                <script>
                alert('<?php echo addslashes($success_message); ?>');
                </script>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Confirm Delete Modal -->
    <div class="modal fade" id="confirmDeleteModal" tabindex="-1" role="dialog"
        aria-labelledby="confirmDeleteModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="confirmDeleteModalLabel">Confirm Deletion</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="admin_id">
                    <div class="form-group">
                        <label for="currentPassword">Enter your password to confirm:</label>
                        <input type="password" class="form-control" id="currentPassword" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" id="deleteBtn" class="btn btn-danger">Delete</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Success Modal -->
    <div class="modal fade" id="successModal" tabindex="-1" role="dialog" aria-labelledby="successModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="successModalLabel">Success</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    The account has been deleted successfully.
                </div>
                <div class="modal-footer">
                    <a href="view_accounts.php" class="btn btn-primary">OK</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="assets/js/jquery-3.6.0.min.js"></script>
    <script src="assets/js/bootstrap.min.js"></script>
    <script src="assets/js/feather.min.js"></script>
    <script src="assets/plugins/slimscroll/jquery.slimscroll.min.js"></script>
    <script src="assets/js/script.js"></script>
    <script src="assets/js/accounts.js"></script>
</body>

</html>