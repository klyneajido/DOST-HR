<?php 
include("PHP_Connections/checkUser.php"); 
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
    <link rel="stylesheet" href="assets/css/announcement.css">
    <style>
        .password-cover {
            -webkit-text-security: disc;
        }
        .form-control-rounded {
            border-radius: 50px;
        }
        .filter-dropdown {
            width: 150px; /* Adjust the width as needed */
        }
        .btn-reset {
            border-radius: 50px;
        }
        .btn-search {
            border-radius: 0;
            background-color: transparent; /* No background color */
            color: #007bff; /* Icon color */
        }
        .input-group {
            width: 50%; /* Adjust the width as needed */
        }
        .input-group .form-control {
            border-top-right-radius: 0;
            border-bottom-right-radius: 0;
        }
        .input-group .btn {
            border-top-left-radius: 0;
            border-bottom-left-radius: 0;
        }
        .btn-reset {
            border-radius: 50px;
        }
    </style>
</head>

<body class="scrollbar" id="style-5">
    <?php include("logout_modal.php") ?>
    <div class="main-wrapper">
        <?php include("navbar.php") ?>
        <div class="page-wrapper">
            <div class="container-fluid">
                <div class="breadcrumb-path mb-4 my-4">
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href=""><img src="assets/img/dash.png" class="mr-2" alt="breadcrumb" />Accounts</a>
                        </li>
                        <li class="breadcrumb-item active">Admin</li>
                    </ul>
                </div>
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Admin Accounts</h3>
                    </div>
                    <div class="card-body">
                        <!-- Filter Dropdown and Reset Button -->
                        <form method="GET" action="">
                            <div class="d-flex justify-content-end mb-3">
                                <?php
                                // Initialize filters with default values
                                $searchTerm = isset($_GET['search']) ? $_GET['search'] : '';
                                $filterAuthority = isset($_GET['authority']) ? $_GET['authority'] : '';
                                ?>
                                <div class="input-group">
                                    <input type="text" id="searchInput" name="search" value="<?php echo htmlspecialchars($searchTerm); ?>" class="form-control form-control-rounded" placeholder="Search by name or email">
                                    <div class="input-group-append">
                                        <button type="submit" class="btn btn-search"><i class="fas fa-search"></i></button>
                                    </div>
                                </div>
                                <div class="d-flex flex-column mr-2">
                                    <select id="filterAuthority" name="authority" class="form-control filter-dropdown">
                                        <option value="">Authority</option>
                                        <option value="admin" <?php echo $filterAuthority == 'admin' ? 'selected' : ''; ?>>Admin</option>
                                        <option value="superadmin" <?php echo $filterAuthority == 'superadmin' ? 'selected' : ''; ?>>Superadmin</option>
                                    </select>
                                </div>
                                <button type="button" id="resetFilters" class="btn btn-secondary btn-reset"><i class="fas fa-undo"></i></button>
                            </div>
                        </form>

                        <!-- Table -->
                        <table class="table table-striped" id="adminTable">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Username</th>
                                    <th>Email</th>
                                    <th>Authority</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                // Build query with filters
                                $query = "SELECT admin_id, name, username, email, authority FROM admins WHERE 1";
                                if ($searchTerm) {
                                    $searchTerm = '%' . $mysqli->real_escape_string($searchTerm) . '%';
                                    $query .= " AND (name LIKE ? OR email LIKE ?)";
                                }
                                if ($filterAuthority) {
                                    $query .= " AND authority = ?";
                                }

                                // Prepare and execute statement
                                $stmt = $mysqli->prepare($query);
                                if ($searchTerm && $filterAuthority) {
                                    $stmt->bind_param("sss", $searchTerm, $searchTerm, $filterAuthority);
                                } elseif ($searchTerm) {
                                    $stmt->bind_param("ss", $searchTerm, $searchTerm);
                                } elseif ($filterAuthority) {
                                    $stmt->bind_param("s", $filterAuthority);
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
                                                <a href='editAccount.php?id=" . $row['admin_id'] . "' class='btn btn-sm btn-warning'><i class='fas fa-edit'></i></a>
                                                <a href='deleteAdmin.php?id=" . $row['admin_id'] . "' class='btn btn-sm btn-danger'><i class='fas fa-trash-alt'></i></a>
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
                <div class="user-menu">
                    <a href="addAccount.php" class="btn btn-info btn-lg float-add-btn" title="Add Account">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="white"
                            class="bi bi-plus-circle-fill mb-1" viewBox="0 0 16 16">
                            <path
                                d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0M8.5 4.5a.5.5 0 0 0-1 0v3h-3a.5.5 0 0 0 0 1h3v3a.5.5 0 0 0 1 0v-3h3a.5.5 0 0 0 0-1h-3z" />
                        </svg>
                        Add Account
                    </a>
                </div>

                <div class="mobile-user-menu show">
                    <a href="addAccount.php" class="btn btn-info btn-lg float-add-btn px-3 py-2"
                        title="Add Account">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="white"
                            class="bi bi-plus-circle-fill mb-1" viewBox="0 0 16 16">
                            <path
                                d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0M8.5 4.5a.5.5 0 0 0-1 0v3h-3a.5.5 0 0 0 0 1h3v3a.5.5 0 0 0 1 0v-3h3a.5.5 0 0 0 0-1h-3z" />
                        </svg>

                    </a>
                </div>
                <!-- Pop-up notification -->
                <?php if (!empty($success_message)) : ?>
                    <script>
                        alert('<?php echo addslashes($success_message); ?>');
                    </script>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <script src="assets/js/jquery-3.6.0.min.js"></script>
    <script src="assets/js/bootstrap.min.js"></script>
    <script src="assets/js/feather.min.js"></script>
    <script src="assets/plugins/slimscroll/jquery.slimscroll.min.js"></script>
    <script src="assets/plugins/apexchart/apexcharts.min.js"></script>
    <script src="assets/plugins/apexchart/chart-data.js"></script>
    <script src="assets/js/script.js"></script>
    <script src="assets/js/announcements.js"></script>
    <script>
        $(document).ready(function() {
            // Function to apply filters
            function applyFilters() {
                const authority = $('#filterAuthority').val();
                window.location.href = `?search=${encodeURIComponent($('#searchInput').val())}&authority=${encodeURIComponent(authority)}`;
            }

            // Event listeners
            $('#filterAuthority').on('change', applyFilters);
            $('#resetFilters').on('click', function() {
                $('#filterAuthority').val('');
                $('#searchInput').val('');
                applyFilters();
            });
        });
    </script>
</body>

</html>
