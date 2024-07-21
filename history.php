<?php
// Start session
session_start();
include_once 'PHP_Connections/db_connection.php';

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit();
}

$user_name = isset($_SESSION['username']) ? $_SESSION['username'] : 'Guest';
$profile_image_path = isset($_SESSION['profile_image']) ? $_SESSION['profile_image'] : 'assets/img/profiles/default-profile.png';
$username = $_SESSION['username'];

// Fetch admin details
$query = "SELECT name, username, email, profile_image FROM admins WHERE username = ?";
$stmt = $mysqli->prepare($query);
$stmt->bind_param('s', $username);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 1) {
    $admin = $result->fetch_assoc();
} else {
    echo "User not found.";
    exit();
}

// Handle form submission for profile update
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];
    if (!empty($_FILES['profile_image']['name'])) {
        $profile_image = addslashes(file_get_contents($_FILES['profile_image']['tmp_name']));
    } else {
        $profile_image = $admin['profile_image'];
    }

    $update_query = "UPDATE admins SET name = ?, email = ?, profile_image = ? WHERE username = ?";
    $update_stmt = $mysqli->prepare($update_query);
    $update_stmt->bind_param('ssss', $name, $email, $profile_image, $username);
    if ($update_stmt->execute()) {
        $_SESSION['name'] = $name;
        $_SESSION['email'] = $email;
        $_SESSION['profile_image'] = $profile_image;
        echo "<script>window.addEventListener('load', function() { $('#successModal').modal('show'); });</script>";
    } else {
        echo "Error updating profile.";
    }
}

// Pagination setup
$items_per_page = 10;
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
$offset = ($page - 1) * $items_per_page;

// Get sort order
$sort_order = isset($_GET['sort']) && $_GET['sort'] === 'asc' ? 'ASC' : 'DESC';

// Handle filters
$search_term = isset($_GET['search']) ? $mysqli->real_escape_string($_GET['search']) : '';
$admin_id = isset($_GET['admin_id']) ? intval($_GET['admin_id']) : 0;
$action_filter = isset($_GET['action']) ? $mysqli->real_escape_string($_GET['action']) : '';

// Prepare SQL query with filters
$history_query = "SELECT h.*, a.name AS admin_name 
                  FROM history h 
                  JOIN admins a ON h.user_id = a.admin_id 
                  WHERE h.action LIKE ? ";
if ($admin_id > 0) {
    $history_query .= "AND h.user_id = ? ";
}
if (!empty($action_filter)) {
    $history_query .= "AND h.action = ? ";
}
$history_query .= "ORDER BY h.date $sort_order 
                  LIMIT $items_per_page OFFSET $offset";

$stmt = $mysqli->prepare($history_query);
$search_like = '%' . $search_term . '%';
$params = [$search_like];
if ($admin_id > 0) {
    $params[] = $admin_id;
}
if (!empty($action_filter)) {
    $params[] = $action_filter;
}
$stmt->bind_param(str_repeat('s', count($params)), ...$params);
$stmt->execute();
$history_result = $stmt->get_result();

// Count total records for pagination
$count_query = "SELECT COUNT(*) AS total FROM history WHERE action LIKE ? ";
if ($admin_id > 0) {
    $count_query .= "AND user_id = ? ";
}
if (!empty($action_filter)) {
    $count_query .= "AND action = ? ";
}
$stmt = $mysqli->prepare($count_query);
$params = [$search_like];
if ($admin_id > 0) {
    $params[] = $admin_id;
}
if (!empty($action_filter)) {
    $params[] = $action_filter;
}
$stmt->bind_param(str_repeat('s', count($params)), ...$params);
$stmt->execute();
$count_result = $stmt->get_result();
$total_rows = $count_result->fetch_assoc()['total'];
$total_pages = ceil($total_rows / $items_per_page);
?>

<?php include("logout_modal.php")?>
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
<body>
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
                                    <option value="Archived Announcements" <?php echo $action_filter === 'Archived Announcements' ? 'selected' : ''; ?>>Archived Announcement</option>
                                    <option value="Added New Job" <?php echo $action_filter === 'Added New Job' ? 'selected' : ''; ?>>Added New Job</option>
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
                                    <?php if ($history_result->num_rows > 0): ?>
                                        <?php while ($row = $history_result->fetch_assoc()): ?>
                                            <tr>
                                                <td><?php echo htmlspecialchars($row['action']); ?></td>
                                                <td><?php echo htmlspecialchars($row['details']); ?></td>
                                                <td><?php echo htmlspecialchars($row['admin_name']); ?></td>
                                                <td><?php echo htmlspecialchars($row['date']); ?></td>
                                                <td>
                                                    <a href="deleteHistory.php?id=<?php echo $row['id']; ?>" class="btn btn-danger btn-sm">
                                                        <i class="fas fa-trash"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        <?php endwhile; ?>
                                    <?php else: ?>
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
                                <?php if ($page > 1): ?>
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
    <script src="assets/js/jquery-3.6.0.min.js"></script>
    <script src="assets/js/popper.min.js"></script>
    <script src="assets/js/bootstrap.min.js"></script>
    <script src="assets/js/script.js"></script>
</body>
</html>
