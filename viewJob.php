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

// Check if search query is set
$search = isset($_GET['search']) ? $mysqli->real_escape_string($_GET['search']) : '';

// Prepare SQL query
$sql = "SELECT j.job_id, j.job_title ,j.position_or_unit, j.description, j.education_requirement, j.experience_or_training, j.duties_and_responsibilities, d.name as department_name,j.place_of_assignment, d.abbrev, j.salary, j.status, j.created_at, j.updated_at, j.deadline 
        FROM job j
        INNER JOIN department d ON j.department_id = d.department_id";

if (!empty($search)) {
	$sql .= " WHERE j.job_title LIKE '%$search%' OR d.name LIKE '%$search%' OR d.abbrev LIKE '%$search%'";
}

$result = $mysqli->query($sql);

// Initialize an empty array to store jobs data
$jobs = [];

if ($result && $result->num_rows > 0) {
	while ($row = $result->fetch_assoc()) {
		$jobs[] = $row;
	}
} else {
	$errors['database'] = "No jobs found.";
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
	<!-- [if lt IE 9]>
			<script src="assets/js/html5shiv.min.js"></script>
			<script src="assets/js/respond.min.js"></script>
		<![endif] -->
</head>

<style>
	#style-5::-webkit-scrollbar-track
	{
		-webkit-box-shadow: inset 0 0 6px rgba(0,0,0,0.3);
		background-color: #F5F5F5;
	}

	#style-5::-webkit-scrollbar
	{
		width: 10px;
		background-color: #F5F5F5;
	}

	#style-5::-webkit-scrollbar-thumb
	{
		background-color: #0ae;
		
		background-image: -webkit-gradient(linear, 0 0, 0 100%,
											color-stop(.5, rgba(255, 255, 255, .2)),
							color-stop(.5, transparent), to(transparent));
	}
</style>

<body class="scrollbar" id="style-5">
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
				<form method="GET" action="viewJob.php">
					<input type="text" class="form-control" name="search" placeholder="Search...">
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
				</li>

			</ul>
			<div class="dropdown mobile-user-menu show">
				<a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-ellipsis-v"></i></a>
				<div class="dropdown-menu dropdown-menu-right ">
					<a class="dropdown-item" href="profile.php">My Profile</a>
					<a class="dropdown-item" href="settings.html">Settings</a>
					<a class="dropdown-item" href="PHP_Connections/logout.php">Logout</a>
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
										<form method="GET" action="viewJob.php">
											<input type="text" class="form-control" name="search" placeholder="Search...">
											<button class="btn" type="submit"><i class="fas fa-search"></i></button>
										</form>
									</form>
								</div>
							</div>
						</div>
						<ul>
						<li>
							<a href="index.php"><img src="assets/img/home.svg" alt="sidebar_img">
									<span>Dashboard</span></a>
							</li>
							<li>
								<a href="applicants.php"><img src="assets/img/employee.svg" alt="sidebar_img"><span>
										Applicants</span></a>
							</li>
							<li class="active">
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
			<div class="container-fluid">
				<div class="breadcrumb-path mb-4 my-4" >
					<ul class="breadcrumb">
						<li class="breadcrumb-item">
							<a href=""><img src="assets/img/dash.png" class="mr-2" alt="breadcrumb" />Jobs</a>
						</li>
						<li class="breadcrumb-item active">Position</li>
					</ul>
					
				</div>

				<?php if (!empty($errors)) : ?>
					<div class="alert alert-danger">
						<?php foreach ($errors as $error) : ?>
							<p><?php echo htmlspecialchars($error); ?></p>
						<?php endforeach; ?>
					</div>
				<?php endif; ?>

				<div class="row">
					<?php foreach ($jobs as $job) : ?>
						<div class="col-md-12">
							<div class="card">
								<div class="card-body shadow p-3">
									<div class="d-flex justify-content-between align-items-center">
										<h5 class="card-header mb-0"><strong>
											<?php if (empty($job['position_or_unit'])) : $position = ' '; else : $position = $job['position_or_unit']; endif;
											echo htmlspecialchars($job['job_title'] . " " . $position); ?>
										</strong></h5>
										<a href="archiveJobs.php?job_id=<?php echo $job['job_id']; ?>" class="btn btn-secondary archive-btn">Archive</a>
									</div>
									<div class="mx-3 py-2">
										<p class="card-text pt-3"><strong>Description:</strong> <?php echo htmlspecialchars($job['description']); ?></p>
										
										<div class="row ">
											<div class="col-md-6">
												<p class="card-text"><strong>Department:</strong> <?php echo htmlspecialchars($job['department_name']); ?></p>
												<?php if (!empty($job['place_of_assignment'])) : ?>
												<p class="card-text"><strong>Place of Assignment:</strong> <?php echo htmlspecialchars($job['place_of_assignment']); ?></p>
												<?php endif; ?>
												<?php if ("COS" == ($job['status'])) : ?>
												<p class="card-text"><strong>Daily Salary:</strong> Php <?php echo htmlspecialchars(number_format($job['salary'], 2)); ?></p>
												<?php else : ?>
												<p class="card-text"><strong>Monthly Salary:</strong> Php <?php echo htmlspecialchars(number_format($job['salary'], 2)); ?></p>
												<?php endif; ?>
												<p class="card-text"><strong>Status:</strong> <?php echo htmlspecialchars($job['status']); ?></p>
											</div>

											<div class="col-md-6 pt-3">
												<p class="card-text"><strong>Created At:</strong> <?php echo htmlspecialchars($job['created_at']); ?></p>
												<p class="card-text"><strong>Updated At:</strong> <?php echo htmlspecialchars($job['updated_at']); ?></p>
												<p class="card-text"><strong>Deadline:</strong> <?php echo htmlspecialchars($job['deadline']); ?></p>
											</div>
										</div>

										<br>
										<a href="editJob.php?job_id=<?php echo $job['job_id']; ?>" class="btn btn-primary py-3 px-5">Edit</a>
										<a href="detailJob.php?job_id=<?php echo $job['job_id']; ?>" class="btn btn-success py-3 px-5">Details</a>
									</div>
								</div>
							</div>
						</div>
					<?php endforeach; ?>
				</div>

				<script>
					document.querySelectorAll('.archive-btn').forEach(function(button) {
						button.addEventListener('click', function(event) {
							event.preventDefault();
							var result = confirm('Are you sure you want to archive this job?');
							if (result) {
								window.location.href = this.href;
							}
						});
					});
				</script>

				<div class="user-menu">
					<a href="addJob.php" class="btn btn-info btn-lg float-add-btn" title="Add Job">
							<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="white" class="bi bi-plus-circle-fill mb-1" viewBox="0 0 16 16">
									<path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0M8.5 4.5a.5.5 0 0 0-1 0v3h-3a.5.5 0 0 0 0 1h3v3a.5.5 0 0 0 1 0v-3h3a.5.5 0 0 0 0-1h-3z"/>
							</svg>
							Add Job
					</a>
				</div>

				<div class="mobile-user-menu show">
					<a href="addJob.php" class="btn btn-info btn-lg float-add-btn px-3 py-2" title="Add Job">
							<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="white" class="bi bi-plus-circle-fill mb-1" viewBox="0 0 16 16">
									<path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0M8.5 4.5a.5.5 0 0 0-1 0v3h-3a.5.5 0 0 0 0 1h3v3a.5.5 0 0 0 1 0v-3h3a.5.5 0 0 0 0-1h-3z"/>
							</svg>
						
					</a>
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
	<!-- sdsadasdasd -->

</body>

</html>