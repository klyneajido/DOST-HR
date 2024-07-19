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

// Get user's username from session
$username = $_SESSION['username'];

// Fetch admin details from the database
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

// Pagination parameters for Jobs
$jobs_limit = 10; 
$jobs_page = isset($_GET['jobs_page']) ? intval($_GET['jobs_page']) : 1;
$jobs_offset = ($jobs_page - 1) * $jobs_limit;

// Pagination parameters for Announcements
$announcements_limit = 6; 
$announcements_page = isset($_GET['announcements_page']) ? intval($_GET['announcements_page']) : 1;
$announcements_offset = ($announcements_page - 1) * $announcements_limit;

// Modified query to join job_archive with department to get department name and paginate results
$query_archive = "
    SELECT ja.*, d.name 
    FROM job_archive ja
    LEFT JOIN department d ON ja.department_id = d.department_id
    LIMIT ?, ?
";
$stmt_archive = $mysqli->prepare($query_archive);
$stmt_archive->bind_param('ii', $jobs_offset, $jobs_limit);
$stmt_archive->execute();
$result_archive = $stmt_archive->get_result();

// Get total number of archived jobs for pagination
$query_archive_count = "SELECT COUNT(*) AS total FROM job_archive";
$result_archive_count = $mysqli->query($query_archive_count);
$total_jobs = $result_archive_count->fetch_assoc()['total'];
$total_pages_jobs = ceil($total_jobs / $jobs_limit);

// Fetch paginated archived announcements
$query_announcement_archive = "
    SELECT * FROM announcement_archive
    LIMIT ?, ?
";
$stmt_announcement = $mysqli->prepare($query_announcement_archive);
$stmt_announcement->bind_param('ii', $announcements_offset, $announcements_limit);
$stmt_announcement->execute();
$result_announcement_archive = $stmt_announcement->get_result();

// Get total number of archived announcements for pagination
$query_announcement_count = "SELECT COUNT(*) AS total FROM announcement_archive";
$result_announcement_count = $mysqli->query($query_announcement_count);
$total_announcements = $result_announcement_count->fetch_assoc()['total'];
$total_pages_announcements = ceil($total_announcements / $announcements_limit);

// If the form is submitted, update the profile details
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
    <link rel="stylesheet" href="assets/css/style.css">
    
</head>
<style>
    #style-5::-webkit-scrollbar-track {
		-webkit-box-shadow: inset 0 0 6px rgba(0, 0, 0, 0.3);
		background-color: #F5F5F5;
	}

	#style-5::-webkit-scrollbar {
		width: 10px;
		background-color: #F5F5F5;
	}

	#style-5::-webkit-scrollbar-thumb {
		background-color: #0ae;

		background-image: -webkit-gradient(linear, 0 0, 0 100%,
				color-stop(.5, rgba(255, 255, 255, .2)),
				color-stop(.5, transparent), to(transparent));
	}
	.filter-content-indiv{
  margin-left:5%; 
  border: 1px solid #ccc; 
  border-radius: 15px
}

.border-filter{
  border: 1px solid #ccc;
}
        tr td {
        max-width: 400px; /* Set the maximum width */
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
</style>
<body class="scrollbar" id="style-5">
    <div class="main-wrapper">
        <div class="header">
            <div class="header-left">
                <div class="logo-wrapper">
                    <a href="index.php" class="logo">
                        <img src="assets/img/DOST.png" alt="Logo">
                    </a>
                </div>
                <a href="index.php" class="logo logo-small">
                    <img src="assets/img/dost_logo.png" alt="Logo" width="30" height="30">
                </a>
                <a href="javascript:void(0);" id="toggle_btn">
                    <span class="bar-icon">
                        <span></span>
                        <span></span>
                        <span></span>
                    </span>
                </a>
            </div>
            <div class="top-nav-search" style="width:46.5%; margin-left:8%; min-width:20%;">
                <form>
                    <input type="text" class="form-control" placeholder="">
                    <button class="btn" type="submit"><i class="fas fa-search"></i></button>
                </form>
            </div>
            <a class="mobile_btn" id="mobile_btn">
                <i class="fas fa-bars"></i>
            </a>
            <ul class="nav user-menu">
                <li class="nav-item dropdown has-arrow main-drop">
                    <a href="#" class="dropdown-toggle nav-link" data-toggle="dropdown">
                        <span class="user-img">
                            <img src="<?php echo htmlspecialchars($profile_image_path); ?>" alt="Avatar" style="border-radius: 50%; width: 45px; height: 45px;">
                            <span class="status online"></span>
                        </span>
                        <span><?php echo htmlspecialchars($username); ?></span>
                    </a>
                    <div class="dropdown-menu">
                        <a class="dropdown-item" href="profile.php"><i data-feather="user" class="mr-1"></i> Profile</a>
                        <a class="dropdown-item" href="settings.php"><i data-feather="settings" class="mr-1"></i> Settings</a>
                        <a class="dropdown-item" href="#" id="logoutLink"><i data-feather="log-out" class="mr-1"></i> Logout</a>
                    </div>
                    <script>
                        document.getElementById('logoutLink').addEventListener('click', function(event) {
                            event.preventDefault();
                            $('#logoutModal').modal('show');
                        });
                        document.getElementById('confirmLogout').addEventListener('click', function() {
                            window.location.href = 'PHP_Connections/logout.php';
                        });
                    </script>
                </li>
            </ul>
            <div class="dropdown mobile-user-menu show">
                <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-ellipsis-v"></i></a>
                <div class="dropdown-menu dropdown-menu-right ">
                    <a class="dropdown-item" href="profile.php">My Profile</a>
                    <a class="dropdown-item" href="settings.php">Settings</a>
                    <a class="dropdown-item" href="PHP_Connections/logout.php">Logout</a>
                </div>
            </div>
        </div>
        <div class="sidebar" id="sidebar">
            <div class="sidebar-inner slimscroll">
                <div class="sidebar-contents">
                    <div id="sidebar-menu" class="sidebar-menu">
                        <ul>
                            <li>
                                <a href="index.php"><img src="assets/img/home.svg" alt="sidebar_img">
                                    <span>Dashboard</span></a>
                            </li>
                            <li>
                                <a href="applicants.php"><img src="assets/img/employee.svg" alt="sidebar_img"><span>
                                        Applicants</span></a>
                            </li>
                            <li>
                                <a href="viewJob.php"><img src="assets/img/company.svg" alt="sidebar_img"> <span>
                                        View Job</span></a>
                            </li>
                            <li>
                                <a href="announcements.php"><img src="assets/img/manage.svg" alt="sidebar_img">
                                    <span>Announcements</span></a>
                            </li>
                            <li>
                                <a href="transparency.php"><img src="assets/img/employee.svg" alt="sidebar_img"><span>
                                        Transparency</span></a>
                            </li>
                            <li class="active">
                              <a href="archive.php"><img src="assets/img/report.svg" alt="sidebar_img">
                                  <span>Archive</span></a>
                                          </li>
                            <li>
                              <a href="history.php"><img src="assets/img/review.svg" alt="sidebar_img">
                                  <span>History</span></a>
                            </li>
                            <li >
                                <a href="profile.php"><img src="assets/img/profile.svg" alt="sidebar_img">
                                    <span>Profile</span></a>
                            </li>
                        </ul>
                        <ul class="logout">
                            <li>
                                <a href="#" id="sidebarLogoutLink"><img src="assets/img/logout.svg" alt="sidebar_img"><span>Log out</span></a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <script>
            document.getElementById('sidebarLogoutLink').addEventListener('click', function(event) {
                event.preventDefault();
                $('#logoutModal').modal('show');
            });
            document.getElementById('confirmLogout').addEventListener('click', function() {
                window.location.href = 'PHP_Connections/logout.php';
            });
        </script>
        <div class="page-wrapper">
            <div class="container-fluid">
                <div class="breadcrumb-path mb-4 my-4">
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="archive.php"><img src="assets/img/dash.png" class="mr-2" alt="breadcrumb" />Archive</a>
                        </li>
                        <li class="breadcrumb-item active">Files</li>
                    </ul>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">Archived Jobs</h4>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <thead class="text-center">
                                            <tr>
                                                <!-- <th>ID</th> -->
                                                <th>Title</th>
                                                <th>Position/Unit</th>
                                                <th>Description</th>
                                                <th class="w-25">Education Requirement</th>
                                                <th>Experience or Training</th>
                                                <th>Duties and Responsibilities</th>
                                                <th>Salary</th>
                                                <th>Department</th>
                                                <th>Place of Assignment</th>
                                                <th>Status</th>
                                                <th>Proof</th>
                                                <th>Created At</th>
                                                <th>Updated At</th>
                                                <th>Deadline</th>
                                                <th>Archived By</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            if ($result_archive->num_rows > 0) {
                                                while ($job = $result_archive->fetch_assoc()) {
                                                    echo "<tr class='text-center'>";
                                                    // echo "<td>" . htmlspecialchars($job['jobarchive_id']) . "</td>";
                                                    echo "<td>" . htmlspecialchars($job['job_title']) . "</td>";
                                                    echo "<td>" . htmlspecialchars($job['position_or_unit']) . "</td>";
                                                    echo "<td class='description-column'>" . htmlspecialchars($job['description']) . "</td>";
                                                    echo "<td>" . htmlspecialchars($job['education_requirement']) . "</td>";
                                                    echo "<td>" . htmlspecialchars($job['experience_or_training']) . "</td>";
                                                    echo "<td>" . htmlspecialchars($job['duties_and_responsibilities']) . "</td>";
                                                    echo "<td>₱" . htmlspecialchars($job['salary']) . "</td>";
                                                    echo "<td>" . htmlspecialchars($job['name']) . "</td>";
                                                    echo "<td>" . htmlspecialchars($job['place_of_assignment']) . "</td>";
                                                    echo "<td>" . htmlspecialchars($job['status']) . "</td>";
                                                    echo "<td>" . htmlspecialchars($job['proof']) . "</td>";
                                                    echo "<td>" . htmlspecialchars($job['created_at']) . "</td>";
                                                    echo "<td>" . htmlspecialchars($job['updated_at']) . "</td>";
                                                    echo "<td>" . htmlspecialchars($job['deadline']) . "</td>";
                                                    echo "<td>" . htmlspecialchars($job['archived_by']) . "</td>";
                                                    echo "</tr>";
                                                }
                                            } else {
                                                echo "<tr><td colspan='10'>No archived jobs found.</td></tr>";
                                            }
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                                <nav aria-label="Page navigation">
                                    <ul class="pagination justify-content-center mt-3">
                                        <li class="page-item <?php if ($jobs_page <= 1) echo 'disabled'; ?>">
                                            <a class="page-link" href="?jobs_page=<?php echo $jobs_page - 1; ?>" aria-label="Previous">
                                                <span aria-hidden="true">&laquo;</span>
                                                <span class="sr-only">Previous</span>
                                            </a>
                                        </li>

                                        <?php
                                        $jobs_start = max(1, $jobs_page - 1);
                                        $jobs_end = min($total_pages_jobs, $jobs_page + 1);

                                        if ($jobs_start > 1) {
                                            echo '<li class="page-item"><a class="page-link" href="?jobs_page=1">1</a></li>';
                                            if ($jobs_start > 2) {
                                                echo '<li class="page-item"><span class="page-link">...</span></li>';
                                            }
                                        }

                                        for ($i = $jobs_start; $i <= $jobs_end; $i++) : ?>
                                            <li class="page-item <?php if ($jobs_page == $i) echo 'active'; ?>"><a class="page-link" href="?jobs_page=<?php echo $i; ?>"><?php echo $i; ?></a></li>
                                        <?php endfor;

                                        if ($jobs_end < $total_pages_jobs) {
                                            if ($jobs_end < $total_pages_jobs - 1) {
                                                echo '<li class="page-item"><span class="page-link">...</span></li>';
                                            }
                                            echo '<li class="page-item"><a class="page-link" href="?jobs_page=' . $total_pages_jobs . '">' . $total_pages_jobs . '</a></li>';
                                        }
                                        ?>

                                        <li class="page-item <?php if ($jobs_page >= $total_pages_jobs) echo 'disabled'; ?>">
                                            <a class="page-link" href="?jobs_page=<?php echo $jobs_page + 1; ?>" aria-label="Next">
                                                <span aria-hidden="true">&raquo;</span>
                                                <span class="sr-only">Next</span>
                                            </a>
                                        </li>
                                    </ul>
                                </nav>

                            </div>
                        </div>
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">Archived Announcements</h4>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <thead class="text-center">
                                            <tr>
                                                <!-- <th>ID</th> -->
                                                <th>Title</th>
                                                <th>Description</th>
                                                <th>Link</th>
                                                <th>Image</th>
                                                <th>Created At</th>
                                                <th>Updated At</th>
                                                <th>Archived By</th>
                                            </tr>
                                        </thead>
                                          <tbody class="text-center">
                                            <?php
                                            if ($result_announcement_archive->num_rows > 0) {
                                                while ($archive = $result_announcement_archive->fetch_assoc()) {
                                                    // Encode the image_announcement BLOB data to base64
                                                    $image_data = base64_encode($archive['image_announcement']);
                                                    echo "<tr>";
                                                    echo "<td>" . htmlspecialchars($archive['title']) . "</td>";
                                                    echo "<td>" . htmlspecialchars($archive['description_announcement']) . "</td>";
                                                    echo "<td><a href='" . htmlspecialchars($archive['link']) . "'>" . htmlspecialchars($archive['link']) . "</a></td>";
                                                    // Use base64-encoded string as the src attribute of the img tag
                                                    echo "<td><img src='data:image/jpeg;base64," . $image_data . "' alt='Image' width='100' height='100'></td>";
                                                    echo "<td>" . htmlspecialchars($archive['created_at']) . "</td>";
                                                    echo "<td>" . htmlspecialchars($archive['updated_at']) . "</td>";
                                                    echo "<td>" . htmlspecialchars($archive['archived_by']) . "</td>";
                                                    echo "</tr>";
                                                }
                                            } else {
                                                echo "<tr><td colspan='7'>No archived announcements found.</td></tr>";
                                            }
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                                <nav aria-label="Page navigation">
                                    <ul class="pagination justify-content-center mt-3">
                                        <li class="page-item <?php if ($announcements_page <= 1) echo 'disabled'; ?>">
                                            <a class="page-link" href="?announcements_page=<?php echo $announcements_page - 1; ?>" aria-label="Previous">
                                                <span aria-hidden="true">&laquo;</span>
                                                <span class="sr-only">Previous</span>
                                            </a>
                                        </li>

                                        <?php
                                        $announcements_start = max(1, $announcements_page - 1);
                                        $announcements_end = min($total_pages_announcements, $announcements_page + 1);

                                        if ($announcements_start > 1) {
                                            echo '<li class="page-item"><a class="page-link" href="?announcements_page=1">1</a></li>';
                                            if ($announcements_start > 2) {
                                                echo '<li class="page-item"><span class="page-link">...</span></li>';
                                            }
                                        }

                                        for ($i = $announcements_start; $i <= $announcements_end; $i++) : ?>
                                            <li class="page-item <?php if ($announcements_page == $i) echo 'active'; ?>"><a class="page-link" href="?announcements_page=<?php echo $i; ?>"><?php echo $i; ?></a></li>
                                        <?php endfor;

                                        if ($announcements_end < $total_pages_announcements) {
                                            if ($announcements_end < $total_pages_announcements - 1) {
                                                echo '<li class="page-item"><span class="page-link">...</span></li>';
                                            }
                                            echo '<li class="page-item"><a class="page-link" href="?announcements_page=' . $total_pages_announcements . '">' . $total_pages_announcements . '</a></li>';
                                        }
                                        ?>

                                        <li class="page-item <?php if ($announcements_page >= $total_pages_announcements) echo 'disabled'; ?>">
                                            <a class="page-link" href="?announcements_page=<?php echo $announcements_page + 1; ?>" aria-label="Next">
                                                <span aria-hidden="true">&raquo;</span>
                                                <span class="sr-only">Next</span>
                                            </a>
                                        </li>
                                    </ul>
                                </nav>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
    </div>

    <script src="assets/js/date.js"></script>
    <script src="assets/js/jquery-3.6.0.min.js"></script>
    <script src="assets/js/popper.min.js"></script>
    <script src="assets/js/bootstrap.min.js"></script>
    <script src="assets/js/feather.min.js"></script>
    <script src="assets/plugins/slimscroll/jquery.slimscroll.min.js"></script>
    <script src="assets/plugins/apexchart/apexcharts.min.js"></script>
    <script src="assets/plugins/apexchart/chart-data.js"></script>
    <script src="assets/js/script.js"></script>
</body>
</html>
