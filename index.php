<?php
// Start session
session_start();
include_once 'PHP_Connections\db_connection.php';
// Check if user is logged in
if (!isset($_SESSION['username'])) {
	// Redirect to login page if not logged in
	header('Location: login.php');
	exit();
}

// Get user's name from session
$user_name = isset($_SESSION['username']) ? $_SESSION['username'] : 'Guest';
$profile_image_path = isset($_SESSION['profile_image']) ? $_SESSION['profile_image'] : 'assets/img/profiles/default-profile.png';

$sql = "SELECT COUNT(*) as count FROM applicants";
$result = $mysqli->query($sql);
$applicant_count = 0;

if ($result) {
	$row = $result->fetch_assoc();
	$applicant_count = $row['count'];
} else {
	echo "Error retrieving applicant count: " . $mysqli->error;
}

$sql = "SELECT COUNT(*) as count FROM job";
$result = $mysqli->query($sql);
$job_count = 0;

if ($result) {
	$row = $result->fetch_assoc();
	$job_count = $row['count'];
} else {
	echo "Error retrieving job count: " . $mysqli->error;
}

$sql = "SELECT COUNT(*) as count FROM announcements";
$result = $mysqli->query($sql);
$announcement_count = 0;

if ($result) {
	$row = $result->fetch_assoc();
	$announcement_count = $row['count'];
} else {
	echo "Error retrieving announcement count: " . $mysqli->error;
}

//APPLICATION FILTER 
// Fetch job titles and their specific positions/units along with applicant counts
$query = "SELECT 
            j.job_id,
            j.job_title,
            j.position_or_unit,
            COUNT(a.id) as count
          FROM job j
          LEFT JOIN applicants a ON j.job_id = a.job_id
          GROUP BY j.job_id, j.job_title, j.position_or_unit";

$result = $mysqli->query($query);

// Initialize an array to store positions and counts
$positions = [];

// Process data into a structured array
while ($row = $result->fetch_assoc()) {
    $general_title = $row['job_title'];
    $general_id = $row['job_id'];
    $specific_position = $row['position_or_unit'];
    $count = $row['count'];

    // Check if the general_title already exists in $positions
    if (!isset($positions[$general_title])) {
        $positions[$general_title] = [
            'general_title' => $general_title,
            'total_count' => 0,
            'specific_positions' => []
        ];
    }

    // Increment total_count for the existing general_title
    $positions[$general_title]['total_count'] += $count;

    // Add specific_position under the general_title
    $positions[$general_title]['specific_positions'][] = [
        'specific_position' => $specific_position,
        'specific_count' => $count
    ];
}



// Now $positions array contains combined job titles with aggregated specific positions and counts



?>
<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
	<title>HRMO Admin Dashboard</title>

	<link rel="shortcut icon" href="assets/img/dost_logo.png">

	<link rel="stylesheet" href="assets/css/bootstrap.min.css">

	<link rel="stylesheet" href="assets/plugins/fontawesome/css/fontawesome.min.css">
	<link rel="stylesheet" href="assets/plugins/fontawesome/css/all.min.css">
	<link rel="stylesheet" href="assets/css/style.css">

</head>
<style>
	.modal-backdrop {
		z-index: 1040 !important;
	}

	.modal-dialog {
		z-index: 1050 !important;
	}

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
</style>

<body class="scrollbar" id="style-5">
	<!-- Logout Modal -->
	<div class="modal fade" id="logoutModal" tabindex="-1" aria-labelledby="logoutModalLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="logoutModalLabel">Confirm Logout</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					Are you sure you want to logout?
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
					<button type="button" class="btn btn-danger" id="confirmLogout">Logout</button>
				</div>
			</div>
		</div>
	</div>
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
						<span><?php echo htmlspecialchars($user_name); ?></span>
					</a>
					<div class="dropdown-menu">
						<a class="dropdown-item" href="profile.html"><i data-feather="user" class="mr-1"></i> Profile</a>
						<a class="dropdown-item" href="settings.html"><i data-feather="settings" class="mr-1"></i> Settings</a>
						<a class="dropdown-item" href="#" id="logoutLink"><i data-feather="log-out" class="mr-1"></i> Logout</a>
					</div>
				</li>


			</ul>
			<div class="dropdown mobile-user-menu show">
				<a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-ellipsis-v"></i></a>
				<div class="dropdown-menu dropdown-menu-right ">
					<a class="dropdown-item" href="profile.php">My Profile</a>
					<a class="dropdown-item" href="settings.html">Settings</a>
					<a class="dropdown-item" href="#" id="logoutLink"><i data-feather="log-out" class="mr-1"></i> Logout</a>
				</div>
			</div>

		</div>


		<div class="sidebar" id="sidebar">
			<div class="sidebar-inner slimscroll">
				<div class="sidebar-contents">
					<div id="sidebar-menu" class="sidebar-menu">
						<div class="mobile-show">
							<div class="offcanvas-menu">
								<div class="user-info align-center bg-theme text-center">
									<span class="lnr lnr-cross  text-white" id="mobile_btn_close">X</span>
									<a href="javascript:void(0)" class="d-block menu-style text-white">
										<div class="user-avatar d-inline-block mr-3">
											<img src="<?php echo htmlspecialchars($profile_image_path); ?>" alt="user avatar" class="rounded-circle" width="50">
										</div>
									</a>
								</div>
							</div>
							<div class="sidebar-input">
								<div class="top-nav-search">
									<form>
										<input type="text" class="form-control" placeholder="Search here">
										<button class="btn" type="submit"><i class="fas fa-search"></i></button>
									</form>
								</div>
							</div>
						</div>
						<ul>
							<li class="active">
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
							<li>
								<a href="archive.php"><img src="assets/img/report.svg" alt="sidebar_img">
										<span>Archive</span></a>
              </li>
							<li>
								<a href="history.php"><img src="assets/img/review.svg" alt="sidebar_img">
										<span>History</span></a>
              </li>
							<li>
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
			<div class="content container-fluid">
				<div class="page-name 	mb-4">
					<h4 class="m-0"><img src="<?php echo htmlspecialchars($profile_image_path); ?>" class="mr-1" alt="profile" /> Welcome <span><?php echo htmlspecialchars($user_name); ?></span></h4>
					<label id="current-date"></label>
				</div>
				<div class="row mb-4">
					<div class="col-xl-12 col-sm-12 col-12">
						<div class="breadcrumb-path ">
							<ul class="breadcrumb">
								<li class="breadcrumb-item"><a href="index.php"><img src="assets/img/dash.png" class="mr-3" alt="breadcrumb" />Home</a>
								</li>
								<li class="breadcrumb-item active">Dashboard</li>
							</ul>
							<h3>Admin Dashboard</h3>
						</div>
					</div>
				</div>
				<div class="row mb-4">

					<div class="col-xl-4 col-sm-6 col-12">
						<div class="card board1 fill1 ">
							<div class="card-body">
								<div class="card_widget_header">
									<label>Announcements</label>
									<h4><?php echo $announcement_count; ?></h4>
								</div>
								<div class="card_widget_img">
									<img src="assets/img/dash1.png" alt="card-img" />
								</div>
							</div>
						</div>
					</div>
					<div class="col-xl-4 col-sm-6 col-12">
						<div class="card board1 fill2 ">
							<div class="card-body">
								<div class="card_widget_header">
									<label>Job Listed</label>
									<h4><?php echo $job_count; ?></h4>
								</div>
								<div class="card_widget_img">
									<img src="assets/img/dash2.png" alt="card-img" />
								</div>
							</div>
						</div>
					</div>
					<div class="col-xl-4 col-sm-6 col-12">
						<div class="card board1 fill3 ">
							<div class="card-body">
								<div class="card_widget_header">
									<label>Applicants</label>
									<h4><?php echo $applicant_count; ?></h4>
								</div>
								<div class="card_widget_img">
									<img src="assets/img/dash3.png" alt="card-img" />
								</div>
							</div>
						</div>
					</div>
				</div>


				<!-- Applicant filter -->
				<div class="row" >
					<div class="col-xl-12 col-sm-12 col-12">
						<div class="card card-list py-3">
							<div class="card-header">
								<h4 class="card-title">Applicant by Position/Unit</h4>
							</div>
							<div class="card-body" >

							<?php foreach ($positions as $general): ?>
    <div>
        <div class="team-list border-filter">
            <div class="team-view justify-content-between">
                <div class="row col-md-auto">
                    <div class="team-img px-2 btn-warning rounded-circle mx-2 my-2 disabled">
                        <h4><?php echo $general['total_count']; ?></h4>
                    </div>
                    <div class="team-content">
                        <label><?php echo $general['general_title']; ?></label>
                        <span>PHP</span>
                    </div>
                </div>

                <div class="team-action" style="">
                    <ul>
                        <li><a href="#" class="toggle-positions" data-id="<?php echo $general['general_title']; ?>"><i data-feather="chevron-down"></i></a></li>
                        <li><a href="applicants.php"><i data-feather="chevrons-right"></i></a></li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="">
            <div class="specific-positions" id="positions-<?php echo $general['general_title']; ?>" style="display: none; margin-left: 5%;">
                <?php foreach ($general['specific_positions'] as $specific): ?>
                    <div class="mb-3 filter-content-indiv">
                        <div class="team-view">
                            <div class="team-img px-2 btn-warning rounded-circle mx-2 disabled">
                                <h4><?php echo $specific['specific_count']; ?></h4>
                            </div>
                            <div class="team-content">
                                <label><?php echo $specific['specific_position']; ?></label>
                                <span>PHP</span>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
<?php endforeach; ?>



							</div>
						</div>
					</div>
				</div>

				<script>
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
			</script>

				<!-- history and interview cards -->
				<div class="row">
					<div class="col-xl-8 col-sm-12 col-12 d-flex">
						<div class="card card-list flex-fill">
							<div class="card-header">
								<div class="">
									<h4 class="card-title">Recent Activities</h3>
								</div>
							</div>
							<div class="card-body dash-activity">
								<div class="slimscroll activity_scroll">
									<div class="activity-set">
										<div class="activity-img">
											<img src="assets/img/profiles/avatar-02.jpg" alt="avatar">
										</div>
										<div class="activity-content">
											<label>Lorem ipsum dolor sit amet,</label>
											<span>2 hours ago</span>
										</div>
									</div>
									<div class="activity-set">
										<div class="activity-img">
											<img src="assets/img/profiles/avatar-05.jpg" alt="avatar">
										</div>
										<div class="activity-content">
											<label>Lorem ipsum dolor sit amet,</label>
											<span>3 hours ago</span>
										</div>
									</div>
									<div class="activity-set">
										<div class="activity-img">
											<img src="assets/img/profiles/avatar-07.jpg" alt="avatar">
										</div>
										<div class="activity-content">
											<label>Lorem ipsum dolor sit amet,</label>
											<span>4 hours ago</span>
										</div>
									</div>
									<div class="activity-set">
										<div class="activity-img">
											<img src="assets/img/profiles/avatar-08.jpg" alt="avatar">
										</div>
										<div class="activity-content">
											<label>Lorem ipsum dolor sit amet,</label>
											<span>5 hours ago</span>
										</div>
									</div>
									<div class="activity-set">
										<div class="activity-img">
											<img src="assets/img/profiles/avatar-09.jpg" alt="avatar">
										</div>
										<div class="activity-content">
											<label>Lorem ipsum dolor sit amet,</label>
											<span>6 hours ago</span>
										</div>
									</div>
									<div class="activity-set">
										<div class="activity-img">
											<img src="assets/img/profiles/avatar-10.jpg" alt="avatar">
										</div>
										<div class="activity-content">
											<label>Lorem ipsum dolor sit amet,</label>
											<span>2 hours ago</span>
										</div>
									</div>
									<div class="activity-set">
										<div class="activity-img">
											<img src="assets/img/profiles/avatar-12.jpg" alt="avatar">
										</div>
										<div class="activity-content">
											<label>Lorem ipsum dolor sit amet,</label>
											<span>3 hours ago</span>
										</div>
									</div>
									<div class="activity-set">
										<div class="activity-img">
											<img src="assets/img/profiles/avatar-13.jpg" alt="avatar">
										</div>
										<div class="activity-content">
											<label>Lorem ipsum dolor sit amet,</label>
											<span>4 hours ago</span>
										</div>
									</div>
								</div>
								<div class="leave-viewall">
									<a href="history.php">View all <img src="assets/img/right-arrow.png" class="ml-2" alt="arrow"></a>
								</div>
							</div>
						</div>
					</div>

					<!-- UPCOMING INTERVIEW CARD -->
					<div class="col-xl-4 col-sm-12 col-12 d-flex">
						<div class="card card-list flex-fill">
							<div class="card-header ">
								<h4 class="card-title-dash">Your Upcoming Interview</h4>
								<div class="dropdown">
									<button class="btn btn-action " type="button" id="roomsBtn" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
										<i class="fas fa-ellipsis-h"></i>
									</button>
									<div class="dropdown-menu" aria-labelledby="roomsBtn">
										<a class="dropdown-item" href="#">Action</a>
									</div>
								</div>
							</div>

							<div class="card-body p-0">
								<div class="leave-set">
									<span class="leave-inactive">
										<i class="fas fa-briefcase"></i>
									</span>
									<label>Mon, 16 Dec 2021</label>
								</div>
								<div class="leave-set">
									<span class="leave-active">
										<i class="fas fa-briefcase"></i>
									</span>
									<label>Fri, 20 Dec 2021</label>
								</div>
								<div class="leave-set">
									<span class="leave-active">
										<i class="fas fa-briefcase"></i>
									</span>
									<label>Wed, 25 Dec 2021</label>
								</div>
								<div class="leave-set">
									<span class="leave-active">
										<i class="fas fa-briefcase"></i>
									</span>
									<label>Fri, 27 Dec 2021</label>
								</div>
								<div class="leave-set">
									<span class="leave-active">
										<i class="fas fa-briefcase"></i>
									</span>
									<label>Tue, 31 Dec 2021</label>
								</div>
								
							</div>
							<div class="leave-viewall">
									<a href="applicants.php">View all <img src="assets/img/right-arrow.png" class="ml-2" alt="arrow" /></a>
								</div>
						</div>
					</div>
				</div>

				<div>
					
				</div>
			</div>
			
		</div>
	</div>

	</div>
	<script src="assets/js/date.js"></script>
	<script src="assets/js/jquery-3.6.0.min.js"></script>
	<script>
		document.getElementById('logoutLink').addEventListener('click', function(event) {
			event.preventDefault();
			$('#logoutModal').modal('show');
		});

		document.getElementById('confirmLogout').addEventListener('click', function() {
			window.location.href = 'PHP_Connections/logout.php';
		});
	</script>
	<script src="assets/js/popper.min.js"></script>
	<script src="assets/js/bootstrap.min.js"></script>

	<script src="assets/js/feather.min.js"></script>

	<script src="assets/plugins/slimscroll/jquery.slimscroll.min.js"></script>

	<script src="assets/plugins/apexchart/apexcharts.min.js"></script>
	<script src="assets/plugins/apexchart/chart-data.js"></script>
	<script src="assets/js/script.js"></script>


</body>

</html>