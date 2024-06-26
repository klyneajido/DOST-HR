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

$sql = "SELECT COUNT(*) as count FROM applicant";
$result = $mysqli->query($sql);
$employee_count = 0;

if ($result) {
	$row = $result->fetch_assoc();
	$employee_count = $row['count'];
} else {
	echo "Error retrieving applicant count: " . $mysqli->error;
}

$sql = "SELECT department_id, name FROM department";
$result = $mysqli->query($sql);

$departments = [];
if ($result) {
	while ($row = $result->fetch_assoc()) {
		$departments[] = $row;
	}
} else {
	echo "Error retrieving departments: " . $mysqli->error;
}
$errors = [];

// Check if form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
	// Get form data
	$position = $_POST['position'];
	$department_id = $_POST['department_id'];
	$monthly_salary = $_POST['monthlysalary'];
	$status = $_POST['status'];

	// Validate form data
	if (empty($position)) {
		$errors['position'] = "Position is required";
	}
	if (empty($department_id)) {
		$errors['department_id'] = "Department is required";
	}
	if (empty($monthly_salary)) {
		$errors['monthlysalary'] = "Monthly Salary is required";
	}
	if (empty($status)) {
		$errors['status'] = "Status is required";
	}

	// If no errors, proceed with data insertion
	if (empty($errors)) {
		// Send data to insert_job.php
		header('Location: insertJob.php?' . http_build_query($_POST));
		exit();
	}
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0" />
	<title>DOST-HRMO</title>

	<link rel="shortcut icon" href="assets/img/dost_logo.png" />

	<link rel="stylesheet" href="assets/css/bootstrap.min.css" />

	<link rel="stylesheet" href="assets/plugins/select2/css/select2.min.css" />

	<link rel="stylesheet" href="assets/plugins/fontawesome/css/fontawesome.min.css" />
	<link rel="stylesheet" href="assets/plugins/fontawesome/css/all.min.css" />

	<link rel="stylesheet" href="assets/css/style.css" />
	<!--[if lt IE 9]>
      <script src="assets/js/html5shiv.min.js"></script>
      <script src="assets/js/respond.min.js"></script>
    <![endif]-->

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
							<li class="active">
								<a href="addJob.php"><img src="assets/img/calendar.svg" alt="sidebar_img">
									<span>Add Jobs</span></a>
							</li>
							<li>
								<a href="transparency.php"><img src="assets/img/employee.svg" alt="sidebar_img">
									<span>Transparency</span></a>
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
			<div class="row">
				<div class="col-md-9 mx-auto my-5">
					<div class="card">
						<div class="card-header">
							<h4 class="card-title">Add New Job</h4>
						</div>
						<div class="card-body">
							<?php if (!empty($success)) : ?>
								<div class="alert alert-success">
									<?php echo htmlspecialchars($success); ?>
								</div>
							<?php endif; ?>

							<?php if (!empty($errors)) : ?>
								<div class="alert alert-danger">
									<ul>
										<?php foreach ($errors as $error) : ?>
											<li><?php echo htmlspecialchars($error); ?></li>
										<?php endforeach; ?>
									</ul>
								</div>
							<?php endif; ?>

							<form method="POST" action="addJob.php" onsubmit="return confirm('Are you sure you want to add this job?');">
								<div class="form-group">
									<label for="position">Position</label>
									<input type="text" name="position" id="position" class="form-control" value="<?php echo isset($_POST['position']) ? htmlspecialchars($_POST['position']) : ''; ?>">
									<?php if (isset($errors['position'])) : ?>
										<small class="text-danger"><?php echo $errors['position']; ?></small>
									<?php endif; ?>
								</div>
								<div class="form-group">
									<label for="department_id">Department</label>
									<select name="department_id" id="department_id" class="form-control">
										<?php foreach ($departments as $department) : ?>
											<option value="<?php echo htmlspecialchars($department['department_id']); ?>" <?php echo (isset($_POST['department_id']) && $_POST['department_id'] == $department['department_id']) ? 'selected' : ''; ?>>
												<?php echo htmlspecialchars($department['name']); ?>
											</option>
										<?php endforeach; ?>
									</select>
									<?php if (isset($errors['department_id'])) : ?>
										<small class="text-danger"><?php echo $errors['department_id']; ?></small>
									<?php endif; ?>
								</div>
								<div class="form-group">
									<label for="monthly_salary">Monthly Salary</label>
									<input type="number" step="0.01" name="monthlysalary" id="monthly_salary" class="form-control" value="<?php echo isset($_POST['monthlysalary']) ? htmlspecialchars($_POST['monthlysalary']) : ''; ?>" min="0" max="9999999.99" oninput="validateSalaryInput(this)">
									<?php if (isset($errors['monthlysalary'])) : ?>
										<small class="text-danger"><?php echo $errors['monthlysalary']; ?></small>
									<?php endif; ?>
								</div>
								<script>
									function validateSalaryInput(input) {
										const maxDigits = 7;
										const maxDecimalPlaces = 2;

										let value = input.value;
										let parts = value.split('.');

										if (parts[0].length > maxDigits) {
											input.value = parts[0].slice(0, maxDigits) + (parts[1] ? '.' + parts[1] : '');
										}

										if (parts[1] && parts[1].length > maxDecimalPlaces) {
											input.value = parts[0] + '.' + parts[1].slice(0, maxDecimalPlaces);
										}
									}
								</script>
								<div class="form-group">
									<label for="status">Status</label>
									<select name="status" id="status" class="form-control">
										<option value="Permanent" <?php echo (isset($_POST['status']) && $_POST['status'] == 'Permanent') ? 'selected' : ''; ?>>Permanent</option>
										<option value="COS" <?php echo (isset($_POST['status']) && $_POST['status'] == 'COS') ? 'selected' : ''; ?>>COS</option>
									</select>
									<?php if (isset($errors['status'])) : ?>
										<small class="text-danger"><?php echo $errors['status']; ?></small>
									<?php endif; ?>
								</div>
								<button type="submit" class="btn btn-primary w-25">Add Job</button>
							</form>
						</div>
					</div>
				</div>
			</div>

			</form>
		</div>
	</div>
	<script>

	</script>
	<script src="assets/js/jquery-3.6.0.min.js"></script>

	<script src="assets/js/popper.min.js"></script>
	<script src="assets/js/bootstrap.min.js"></script>

	<script src="assets/js/feather.min.js"></script>

	<script src="assets/plugins/slimscroll/jquery.slimscroll.min.js"></script>

	<script src="assets/plugins/select2/js/select2.min.js"></script>

	<script src="assets/js/script.js"></script>
</body>

</html>