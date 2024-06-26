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
$sql = "SELECT j.job_id, j.position, d.name as department_name, d.abbrev, j.monthlysalary, j.status 
        FROM job j
        INNER JOIN department d ON j.department_id = d.department_id";

if (!empty($search)) {
	$sql .= " WHERE j.position LIKE '%$search%' OR d.name LIKE '%$search%' OR d.abbrev LIKE '%$search%'";
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

<body>
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
						<a class="dropdown-item" href="profile.html"><i data-feather="user" class="mr-1"></i>
							Profile</a>
						<a class="dropdown-item" href="settings.html"><i data-feather="settings" class="mr-1"></i>
							Settings</a>
						<a class="dropdown-item" href="PHP_Connections/logout.php"><i data-feather="log-out" class="mr-1"></i>
							Logout</a>
					</div>
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
								<a href="addJob.php"><img src="assets/img/calendar.svg" alt="sidebar_img">
									<span>Add Jobs</span></a>
							</li>
							<li>
								<a href="transparency.php"><img src="assets/img/employee.svg" alt="sidebar_img"><span>
										Transparency</span></a>
							</li>

							<li>
								<a href="profile.php"><img src="assets/img/profile.svg" alt="sidebar_img">
									<span>Profile</span></a>
							</li>
						</ul>
						<ul class="logout">
							<li>
								<a href="PHP_Connections/logout.php"><img src="assets/img/logout.svg" alt="sidebar_img"><span>Log
										out</span></a>
							</li>
						</ul>
					</div>
				</div>
			</div>
		</div>
		<div class="page-wrapper">
			<div class="container-fluid">
			
				<div class="breadcrumb-path mb-4 my-4">
					<ul class="breadcrumb">
						<li class="breadcrumb-item">
							<a href="viewJob.php"><img src="assets/img/dash.png" class="mr-2" alt="breadcrumb" />Jobs</a>
						</li>
						<li class="breadcrumb-item active">Details</li>
					</ul>
					<h3>*Spec job post</h3>
				</div>

				<?php if (!empty($errors)) : ?>
					<div class="alert alert-danger">
						<?php foreach ($errors as $error) : ?>
							<p><?php echo htmlspecialchars($error); ?></p>
						<?php endforeach; ?>
					</div>
				<?php endif; ?>

				
			
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