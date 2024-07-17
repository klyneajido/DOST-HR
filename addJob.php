<?php
// Start session
session_start();
include_once 'PHP_Connections/db_connection.php';

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    // Redirect to login page if not logged in
    header('Location: login.php');
    exit();
}

// Get user's name from session
$user_name = isset($_SESSION['username']) ? $_SESSION['username'] : 'Guest';
$profile_image_path = isset($_SESSION['profile_image']) ? $_SESSION['profile_image'] : 'assets/img/profiles/default-profile.png';

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
$success = "";

// Check if form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $job_title = $_POST['job_title'];
    $position = $_POST['position'];
    $department_id = $_POST['department_id'];
    $experienceortraining = $_POST['experienceortraining'];
    $dutiesandresponsibilities = $_POST['dutiesandresponsibilities'];
    $educationrequirement = $_POST['educreq'];
    $placeofassignment = $_POST['poa'];
    $monthly_salary = $_POST['monthlysalary'];
    $status = $_POST['status'];
    $deadline = $_POST['deadline'];
    $description = $_POST['description'];

    // Validate inputs
    if (empty($job_title)) $errors['job_title'] = "Position is required";
    if (empty($description)) $errors['description'] = "Description is required";
    if (empty($department_id)) $errors['department_id'] = "Department is required";
    if (empty($monthly_salary)) $errors['monthlysalary'] = "Monthly Salary is required";
    if (empty($status)) $errors['status'] = "Status is required";
    if (empty($deadline)) $errors['deadline'] = "Deadline is required";
    if (empty($educationrequirement)) $errors['educreq'] = "Educational Requirement is required";
    if (empty($experienceortraining)) $errors['experienceortraining'] = "Experience or Training is required";

    // If no errors, insert data into job table
    if (empty($errors)) {
        $stmt = $mysqli->prepare("INSERT INTO job (job_title, position_or_unit, description, education_requirement, experience_or_training, duties_and_responsibilities, department_id, salary, place_of_assignment, status, deadline, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())");
        $stmt->bind_param("sssssisdsss", $job_title, $position, $description, $educationrequirement, $experienceortraining, $dutiesandresponsibilities, $department_id, $monthly_salary, $placeofassignment, $status, $deadline);

        if ($stmt->execute()) {
			header('Location: viewJob.php');
            $success = "Job added successfully";
        } else {
            $errors['database'] = "Error adding job: " . $stmt->error;
        }

        $stmt->close();
    }
}

$mysqli->close();
?>


<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0" />
	<title>DOST-HRMO</title>

	<link rel="shortcut icon" href="assets/img/dost_logo.png">

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
							<li class="active">
								<a href="viewJob.php"><img src="assets/img/company.svg" alt="sidebar_img"> <span>
										View Job</span></a>
							</li>
							<li>
								<a href="announcements.php"><img src="assets/img/manage.svg" alt="sidebar_img">
									<span>Announcements</span></a>
							</li>
							<li>
								<a href="transparency.php"><img src="assets/img/employee.svg" alt="sidebar_img">
									<span>Transparency</span></a>
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
									<div class="row col-md-12">
											<div class="form-group col-md-6">
													<label for="job_title">Job Title</label>
													<input type="text" name="job_title" id="job_title" class="form-control" value="">
													<?php if (isset($errors['job_title'])) : ?>
															<small class="text-danger"><?php echo $errors['job_title']; ?></small>
													<?php endif; ?>
											</div>            
											<div class="form-group col-md-6">
													<label for="position">Position</label>
													<input type="text" name="position" id="position" class="form-control" value="">
													<?php if (isset($errors['position'])) : ?>
															<small class="text-danger"><?php echo $errors['position_or_unit']; ?></small>
													<?php endif; ?>
											</div>
									</div>
									
									<div class="form-group">
											<label for="description">Description</label>
											<textarea name="description" id="description" class="form-control" rows="5"></textarea>
											<?php if (isset($errors['description'])) : ?>
													<small class="text-danger"><?php echo $errors['description']; ?></small>
											<?php endif; ?>
									</div>
									<div class="form-group">
											<label for="educreq">Educational Requirement</label>
											<textarea name="educreq" id="educreq" class="form-control" rows="5"></textarea>
											<?php if (isset($errors['education_requirement'])) : ?>
													<small class="text-danger"><?php echo $errors['education_requirement']; ?></small>
											<?php endif; ?>
									</div>
									<div class="form-group">
											<label for="experienceortraining">Experience or Training</label>
											<textarea name="experienceortraining" id="experienceortraining" class="form-control" rows="5"></textarea>
											<?php if (isset($errors['experience_or_training'])) : ?>
													<small class="text-danger"><?php echo $errors['experience_or_training']; ?></small>
											<?php endif; ?>
									</div>
									<div class="form-group">
											<label for="dutiesandresponsibilities">Duties and Responsibilities</label>
											<textarea name="dutiesandresponsibilities" id="dutiesandresponsibilities" class="form-control" rows="5"></textarea>
											<?php if (isset($errors['duties_and_responsibilities'])) : ?>
													<small class="text-danger"><?php echo $errors['duties_and_responsibilities']; ?></small>
											<?php endif; ?>
									</div>
									<div class="form-group">
											<label for="department_id">Department</label>
											<select name="department_id" id="department_id" class="form-control">
													<?php foreach ($departments as $department) : ?>
															<option value="<?php echo htmlspecialchars($department['department_id']); ?>">
																	<?php echo htmlspecialchars($department['name']); ?>
															</option>
													<?php endforeach; ?>
											</select>
											<?php if (isset($errors['department_id'])) : ?>
													<small class="text-danger"><?php echo $errors['department_id']; ?></small>
											<?php endif; ?>
									</div>
									<div class="form-group">
											<label for="poa">Place of Assignment</label>
											<input type="text" name="poa" id="poa" class="form-control" value="">
											<?php if (isset($errors['place_of_assignment'])) : ?>
													<small class="text-danger"><?php echo $errors['place_of_assignment']; ?></small>
											<?php endif; ?>
									</div>
									<div class="form-group">
											<label for="monthlysalary">Salary</label>
											<input type="number" step="0.01" name="monthlysalary" id="monthly_salary" class="form-control" value="" min="0" max="9999999.99" oninput="validateSalaryInput(this)">
											<?php if (isset($errors['monthlysalary'])) : ?>
													<small class="text-danger"></small>
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
											<label for="deadline">Deadline</label>
											<input type="date" name="deadline" id="deadline" class="form-control"  value=""/>
											<?php if (isset($errors['deadline'])) : ?>
													<small class="text-danger"><?php echo $errors['deadline']; ?></small>
											<?php endif; ?>
									</div>
									<div class="form-group">
											<label for="status">Status</label>
											<select name="status" id="status" class="form-control">
													<option value="Permanent" >Permanent</option>
													<option value="COS">COS</option>
											</select>
											<?php if (isset($errors['status'])) : ?>
													<small class="text-danger"><?php echo $errors['status']; ?></small>
											<?php endif; ?>
									</div>
								<button type="submit" class="btn btn-primary w-25">Add Job</button>
								<a href="viewJob.php" class="btn btn-danger py-3 w-25">Cancel</a>
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