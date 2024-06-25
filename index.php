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
$user_name = isset($_SESSION['name']) ? $_SESSION['name'] : 'Guest';
$profile_image_path = isset($_SESSION['profile_image']) ? $_SESSION['profile_image'] : 'assets/img/profiles/default-profile.png';

$sql = "SELECT COUNT(*) as count FROM employee";
$result = $mysqli->query($sql);
$employee_count = 0;

if ($result) {
    $row = $result->fetch_assoc();
    $employee_count = $row['count'];
} else {
    echo "Error retrieving employee count: " . $mysqli->error;
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



?>
<?php
	
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

			<div class="top-nav-search">
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
								<a href="employee.php"><img src="assets/img/employee.svg" alt="sidebar_img"><span>
										Employees</span></a>
							</li>
							<li>
								<a href="company.html"><img src="assets/img/company.svg" alt="sidebar_img"> <span>
										Applicants</span></a>
							</li>
							<li>
								<a href="job.php"><img src="assets/img/calendar.svg" alt="sidebar_img">
									<span>Jobs</span></a>
							</li>
							<li>
								<a href="leave.html"><img src="assets/img/leave.svg" alt="sidebar_img">
									<span>Departments</span></a>
							</li>
							<li>
								<a href="review.html"><img src="assets/img/review.svg" alt="sidebar_img"><span>Review</span></a>
							</li>
							<li>
								<a href="report.html"><img src="assets/img/report.svg" alt="sidebar_img"><span>Report</span></a>
							</li>
							<li>
								<a href="manage.html"><img src="assets/img/manage.svg" alt="sidebar_img">
									<span>Manage</span></a>
							</li>
							<li>
								<a href="settings.html"><img src="assets/img/settings.svg" alt="sidebar_img"><span>Settings</span></a>
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
			<div class="content container-fluid">
				<div class="page-name 	mb-4">
					<h4 class="m-0"><img src="<?php echo htmlspecialchars($profile_image_path); ?>" class="mr-1" alt="profile" /> Welcome <span><?php echo htmlspecialchars($user_name); ?></span></h4>
					<label id="current-date"></label>
				</div>
				<div class="row mb-4">
					<div class="col-xl-6 col-sm-12 col-12">
						<div class="breadcrumb-path ">
							<ul class="breadcrumb">
								<li class="breadcrumb-item"><a href="index.php"><img src="assets/img/dash.png" class="mr-3" alt="breadcrumb" />Home</a>
								</li>
								<li class="breadcrumb-item active">Dashboard</li>
							</ul>
							<h3>Admin Dashboard</h3>
						</div>
					</div>
					<div class="col-xl-6 col-sm-12 col-12">
						<div class="row">
							<div class="col-xl-6 col-sm-6 col-12">
								<a class="btn-dash" href="#"> Admin Dashboard</a>
							</div>
							<div class="col-xl-6 col-sm-6 col-12">
								<a class="btn-emp" href="index-employee.php">Employee Dashboard</a>
							</div>
						</div>
					</div>
				</div>
				<div class="row mb-4">

					<div class="col-xl-3 col-sm-6 col-12">
						<div class="card board1 fill1 ">
							<div class="card-body">
								<div class="card_widget_header">
									<label>Employees</label>
									<h4><?php echo $employee_count; ?></h4>
								</div>
								<div class="card_widget_img">
									<img src="assets/img/dash1.png" alt="card-img" />
								</div>
							</div>
						</div>
					</div>
					<div class="col-xl-3 col-sm-6 col-12">
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
					<div class="col-xl-3 col-sm-6 col-12">
						<div class="card board1 fill3 ">
							<div class="card-body">
								<div class="card_widget_header">
									<label>Applicants</label>
									<h4>9</h4>
								</div>
								<div class="card_widget_img">
									<img src="assets/img/dash3.png" alt="card-img" />
								</div>
							</div>
						</div>
					</div>
					<div class="col-xl-3 col-sm-6 col-12">
						<div class="card board1 fill4 ">
							<div class="card-body">
								<div class="card_widget_header">
									<label>Salary</label>
									<h4>$5.8M</h4>
								</div>
								<div class="card_widget_img">
									<img src="assets/img/dash4.png" alt="card-img" />
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-xl-6 d-flex mobile-h">
						<div class="card flex-fill">
							<div class="card-header">
								<div class="d-flex justify-content-between align-items-center">
									<h5 class="card-title">Total Employees</h5>
								</div>
							</div>
							<div class="card-body">
								<div id="invoice_chart"></div>
								<div class="text-center text-muted">
									<div class="row">
										<div class="col-3">
											<div class="mt-3">
												<p class="mb-2 text-truncate"><i class="fas fa-circle" style="color:#071952;"></i> Cashier</p>
											</div>
										</div>
										<div class="col-3">
											<div class="mt-3">
												<p class="mb-2 text-truncate"><i class="fas fa-circle" style="color:#088395"></i> ITSM</p>
											</div>
										</div>
										<div class="col-3">
											<div class="mt-3">
												<p class="mb-2 text-truncate"><i class="fas fa-circle" style="color:#37B7C3"></i> RSTL</p>
											</div>
										</div>
										<div class="col-3">
											<div class="mt-3">
												<p class="mb-2 text-truncate"><i class="fas fa-circle" style="color:black"></i> Supply Office</p>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="col-xl-6 d-flex">
						<div class="card flex-fill">
							<div class="card-header">
								<div class="d-flex justify-content-between align-items-center">
									<h5 class="card-title">Total Salary By Unit</h5>
								</div>
							</div>
							<div class="card-body">
								<div id="sales_chart"></div>
							</div>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-xl-6 col-sm-12 col-12 d-flex">
						<div class="card card-list flex-fill">
							<div class="card-header ">
								<h4 class="card-title">Total Salary By Unit</h4>
							</div>
							<div class="card-body">
								<div class="team-list">
									<div class="team-view">
										<div class="team-img">
											<img src="assets/img/profiles/avatar-03.jpg" alt="avatar" />
										</div>
										<div class="team-content">
											<label>Maria Cotton</label>
											<span>PHP</span>
										</div>
									</div>
									<div class="team-action">
										<ul>
											<li><a><i data-feather="trash-2"></i></a></li>
											<li><a><i data-feather="edit-2"></i></a></li>
										</ul>
									</div>
								</div>
								<div class="team-list">
									<div class="team-view">
										<div class="team-img">
											<img src="assets/img/profiles/avatar-04.jpg" alt="avatar" />
										</div>
										<div class="team-content">
											<label>Linda Craver</label>
											<span>IOS</span>
										</div>
									</div>
									<div class="team-action">
										<ul>
											<li><a><i data-feather="trash-2"></i></a></li>
											<li><a><i data-feather="edit-2"></i></a></li>
										</ul>
									</div>
								</div>
								<div class="team-list">
									<div class="team-view">
										<div class="team-img">
											<img src="assets/img/profiles/avatar-06.jpg" alt="avatar" />
										</div>
										<div class="team-content">
											<label>Jenni Sims</label>
											<span>Android</span>
										</div>
									</div>
									<div class="team-action">
										<ul>
											<li><a><i data-feather="trash-2"></i></a></li>
											<li><a><i data-feather="edit-2"></i></a></li>
										</ul>
									</div>
								</div>
								<div class="team-list">
									<div class="team-view">
										<div class="team-img">
											<img src="assets/img/profiles/avatar-11.jpg" alt="avatar" />
										</div>
										<div class="team-content">
											<label>Danny</label>
											<span>Design</span>
										</div>
									</div>
									<div class="team-action">
										<ul>
											<li><a><i data-feather="trash-2"></i></a></li>
											<li><a><i data-feather="edit-2"></i></a></li>
										</ul>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="col-xl-3 col-sm-12 col-12 d-flex">
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
								<div class="leave-viewall activit">
									<a>View all <img src="assets/img/right-arrow.png" class="ml-2" alt="arrow"></a>
								</div>
							</div>
						</div>
					</div>
					<div class="col-xl-3 col-sm-12 col-12 d-flex">
						<div class="card card-list flex-fill">
							<div class="card-header ">
								<h4 class="card-title-dash">Your Upcoming Leave</h4>
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
								<div class="leave-viewall">
									<a href="leave.html">View all <img src="assets/img/right-arrow.png" class="ml-2" alt="arrow" /></a>
								</div>
							</div>
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