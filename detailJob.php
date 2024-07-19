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
$sql = "SELECT j.job_id, j.job_title, j.position_or_unit, j.description, j.education_requirement, j.experience_or_training, j.duties_and_responsibilities, d.name as department_name,j.place_of_assignment, d.abbrev, j.salary, j.status, j.created_at, j.updated_at, j.deadline 
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
// Get job ID from query string
$job_id = isset($_GET['job_id']) ? intval($_GET['job_id']) : 0;

if ($job_id <= 0) {
    die('Invalid job ID.');
}

// Prepare SQL query to fetch job details
$sql = "SELECT j.job_id, j.job_title, j.position_or_unit, j.description, j.education_requirement, j.experience_or_training, j.duties_and_responsibilities, d.name as department_name,j.place_of_assignment, d.abbrev, j.salary, j.status, j.created_at, j.updated_at, j.deadline 
        FROM job j
        INNER JOIN department d ON j.department_id = d.department_id
        WHERE j.job_id = ?";

$stmt = $mysqli->prepare($sql);
$stmt->bind_param('i', $job_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die('Job not found.');
}

$job = $result->fetch_assoc();

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
	<?php include("navbar.php")?>
		<div class="page-wrapper">
			<div class="container-fluid">
			
				<div class="breadcrumb-path mb-4 my-4">
					<ul class="breadcrumb">
						<li class="breadcrumb-item">
							<a href="viewJob.php"><img src="assets/img/dash.png" class="mr-2" alt="breadcrumb" />Jobs</a>
						</li>
						<li class="breadcrumb-item active">Details</li>
					</ul>
					<h3 class="card-title"><?php echo htmlspecialchars($job['job_title']." ". $job['position_or_unit']); ?></h3>
				</div>

				<div class="container-fluid">
					<div class="row">
						<div class="col-md-12">
							<div class="card mb-4">
								<div class="card-header d-flex">
									<h4 class="col-md-8 pt-2">	<strong>
										<?php echo htmlspecialchars($job['job_title'] ." ". $job['position_or_unit']); ?></strong>
									</h4>
									<div class="col-md-4 user-menu justify-content-end align-items-center z-4">
										<a href="viewJob.php" class=" btn btn-secondary float-right">Back to Jobs</a>									
									</div>
									
								</div>
								<div class="card-body mx-3">
									<div class="mb-5">
										<h5><strong>Department</strong></h5>
										<p><?php echo htmlspecialchars($job['department_name']); ?></p>
									</div>
									<div class="mb-5">
										<h5><strong>Description</strong></h5>
										<p><?php echo nl2br(htmlspecialchars($job['description'])); ?></p>
									</div>
									<div class="mb-5">
										<h5><strong>Educational Requirement</strong></h5>
										<p><?php echo nl2br(htmlspecialchars($job['education_requirement'])); ?></p>
									</div>
									<div class="mb-5">
										<h5><strong>Experience or Training</strong></h5>
										<p><?php echo nl2br(htmlspecialchars($job['experience_or_training'])); ?></p>
									</div>

									<div class="row">
										<div class="col-md-6">
											<div class="mb-5">
												<h5><strong>Department</strong></h5>
												<p><?php echo nl2br(htmlspecialchars($job['department_name'])); ?></p>
											</div>
											<?php if(!empty($job['place_of_assignment'])) : ?>
											<div class="mb-5">
												<h5><strong>Place of Assignment</strong></h5>
												<p><?php echo nl2br(htmlspecialchars($job['place_of_assignment'])); ?></p>
											</div>
											<?php endif;?>
											<?php if("COS"== ($job['status'])) : ?>
												<div class="mb-5">
													<h5><strong>Daily Salary</strong></h5>
													<p>₱<?php echo htmlspecialchars($job['salary']); ?></p>
												</div>
											
											<?php else : ?>
												<div class="mb-5">
													<h5><strong>Monthly Salary</strong></h5>
													<p>₱<?php echo htmlspecialchars($job['salary']); ?></p>
												</div>
											<?php endif;?>
											<div class="mb-5">
												<h5><strong>Status</strong></h5>
												<p><?php echo htmlspecialchars($job['status']); ?></p>
											</div>
										</div>
										<div class="col-md-6 pt-3">
											<div class="mb-5">
												<h5><strong>Created at</strong></h5>
												<p><?php echo htmlspecialchars($job['created_at']); ?></p>
											</div>
											<div class="mb-5">
												<h5><strong>Updated at</strong></h5>
												<p><?php echo htmlspecialchars($job['updated_at']); ?></p>
											</div>
											<div class="mb-5">
												<h5><strong>Deadline</strong></h5>
												<p><?php echo htmlspecialchars($job['deadline']); ?></p>
											</div>										
										</div>
									</div>			

								</div>
							</div>
						</div>
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

