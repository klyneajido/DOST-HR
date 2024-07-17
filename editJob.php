<?php
session_start();
include_once 'PHP_Connections/db_connection.php';

if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit();
}
$user_name = isset($_SESSION['username']) ? $_SESSION['username'] : 'Guest';
$profile_image_path = isset($_SESSION['profile_image']) ? $_SESSION['profile_image'] : 'assets/img/profiles/default-profile.png';

$job_id = isset($_GET['job_id']) ? (int)$_GET['job_id'] : 0;

if ($job_id === 0) {
    header('Location: viewJob.php');
    exit();
}

$sql = "SELECT * FROM job WHERE job_id = ?";
$stmt = $mysqli->prepare($sql);
$stmt->bind_param('i', $job_id);
$stmt->execute();
$result = $stmt->get_result();
$job = $result->fetch_assoc();

if (!$job) {
    header('Location: viewJob.php');
    exit();
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

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $position = $_POST['position'];
    $department_id = $_POST['department_id'];
    $experienceortraining = $_POST['experienceortraining'];
    $dutiesandresponsibilities = $_POST['dutiesandresponsibilities'];
    $educationrequirement = $_POST['educreq'];
    $placeofassignment = $_POST['poa'];
    $department_id = $_POST['department_id'];
    $monthly_salary = $_POST['monthlysalary'];
    $status = $_POST['status'];
    $deadline = $_POST['deadline'];
    $description= $_POST['description'];

    if (empty($position)) {
        $errors['position'] = "Position is required";
    }
    if(empty($description)) {
        $errors['description'] = "Description is required";
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
    if (empty($deadline)) {
        $errors['deadline'] = "Deadline is required";
    }
    if (empty($educationrequirement)) {
        $errors['educreq'] = "Educational Requirement is required";
    }
    if (empty($experienceortraining)) {
        $errors['experienceortraining'] = "Experience or Training is required";
    }

    if (empty($errors)) {
        $sql = "UPDATE job SET position = ?, department_id = ?, salary = ?, status = ?, description = ?, education_requirement = ?, experience_or_training = ?, duties_and_responsibilities = ?, place_of_assignment = ?, deadline = ?, updated_at = NOW()  WHERE job_id = ?";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param('sidsssssssi', $position, $department_id, $monthly_salary, $status, $description, $educationrequirement, $experienceortraining, $dutiesandresponsibilities, $placeofassignment, $deadline, $job_id);

        if ($stmt->execute()) {
            header('Location: viewJob.php?success=Job updated successfully');
            exit();
        } else {
            $errors['database'] = "Error updating job: " . $mysqli->error;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
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

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0" />
    <title>Edit Job</title>
    <link rel="shortcut icon" href="assets/img/dost_logo.png" />
    <link rel="stylesheet" href="assets/css/bootstrap.min.css" />
    <link rel="stylesheet" href="assets/plugins/select2/css/select2.min.css" />
    <link rel="stylesheet" href="assets/plugins/fontawesome/css/fontawesome.min.css" />
    <link rel="stylesheet" href="assets/plugins/fontawesome/css/all.min.css" />
    <link rel="stylesheet" href="assets/css/style.css" />
</head>

<body  class="scrollbar" id="style-5">
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
                                <a href="addJob.php"><img src="assets/img/calendar.svg" alt="sidebar_img">
                                    <span>Add Jobs</span></a>
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
                            <h4 class="card-title">Edit Job</h4>
                        </div>
                        <div class="card-body">
                            <?php if (!empty($errors)) : ?>
                                <div class="alert alert-danger">
                                    <ul>
                                        <?php foreach ($errors as $error) : ?>
                                            <li><?php echo htmlspecialchars($error); ?></li>
                                        <?php endforeach; ?>
                                    </ul>
                                </div>
                            <?php endif; ?>
                            <form method="POST" action="editJob.php?job_id=<?php echo $job_id; ?>">
                                <div class="form-group">
                                    <label for="position">Position</label>
                                    <input type="text" name="position" id="position" class="form-control" value="<?php echo htmlspecialchars($job['position']); ?>">
                                    <?php if (isset($errors['position'])) : ?>
                                        <small class="text-danger"><?php echo $errors['position']; ?></small>
                                    <?php endif; ?>
                                </div>
                                <div class="form-group">
                                    <label for="description">Description</label>
                                    <textarea name="description" id="description" class="form-control" rows="5"><?php echo htmlspecialchars($job['description']); ?></textarea>
                                    <?php if (isset($errors['description'])) : ?>
                                        <small class="text-danger"><?php echo $errors['description']; ?></small>
                                    <?php endif; ?>
                                </div>
                                <div class="form-group">
                                    <label for="educreq">Educational Requirement</label>
                                    <textarea name="educreq" id="educreq" class="form-control" rows="5"><?php echo htmlspecialchars($job['education_requirement']); ?></textarea>
                                    <?php if (isset($errors['education_requirement'])) : ?>
                                        <small class="text-danger"><?php echo $errors['education_requirement']; ?></small>
                                    <?php endif; ?>
                                </div>
                                <div class="form-group">
                                    <label for="experienceortraining">Experience or Training</label>
                                    <textarea name="experienceortraining" id="experienceortraining" class="form-control" rows="5"><?php echo htmlspecialchars($job['experience_or_training']); ?></textarea>
                                    <?php if (isset($errors['experience_or_training'])) : ?>
                                        <small class="text-danger"><?php echo $errors['experience_or_training']; ?></small>
                                    <?php endif; ?>
                                </div>
                                <div class="form-group">
                                    <label for="dutiesandresponsibilities">Duties and Responsibilities</label>
                                    <textarea name="dutiesandresponsibilities" id="dutiesandresponsibilities" class="form-control" rows="5"><?php echo htmlspecialchars($job['duties_and_responsibilities']); ?></textarea>
                                    <?php if (isset($errors['duties_and_responsibilities'])) : ?>
                                        <small class="text-danger"><?php echo $errors['duties_and_responsibilities']; ?></small>
                                    <?php endif; ?>
                                </div>
                                <div class="form-group">
                                    <label for="department_id">Department</label>
                                    <select name="department_id" id="department_id" class="form-control">
                                        <?php foreach ($departments as $department) : ?>
                                            <option value="<?php echo htmlspecialchars($department['department_id']); ?>" <?php echo ($job['department_id'] == $department['department_id']) ? 'selected' : ''; ?>>
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
                                    <input type="text" name="poa" id="poa" class="form-control" value="<?php echo htmlspecialchars($job['place_of_assignment']); ?>">
                                    <?php if (isset($errors['place_of_assignment'])) : ?>
                                        <small class="text-danger"><?php echo $errors['place_of_assignment']; ?></small>
                                    <?php endif; ?>
                                </div>
                                <div class="form-group">
                                    <?php if("COS"== ($job["status"])) : ?>
                                        <label for="monthly_salary">Daily Salary</label>
                                    <?php else: ?>
                                        <label for="monthly_salary">Monthly Salary</label>
                                    <?php endif;?>
                                    <input type="number" step="0.01" name="monthlysalary" id="monthly_salary" class="form-control" value="<?php echo htmlspecialchars($job['salary']); ?>" min="0" max="9999999.99" oninput="validateSalaryInput(this)">
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
                                    <label for="deadline">Deadline</label>
                                    <input type="date" name="deadline" id="deadline" class="form-control"  value="<?php echo htmlspecialchars($job['deadline']); ?>"/>
                                    <?php if (isset($errors['deadline'])) : ?>
                                        <small class="text-danger"><?php echo $errors['deadline']; ?></small>
                                    <?php endif; ?>
                                </div>
                                <div class="form-group">
                                    <label for="status">Status</label>
                                    <select name="status" id="status" class="form-control">
                                        <option value="Permanent" <?php echo ($job['status'] == 'Permanent') ? 'selected' : ''; ?>>Permanent</option>
                                        <option value="COS" <?php echo ($job['status'] == 'COS') ? 'selected' : ''; ?>>COS</option>
                                    </select>
                                    <?php if (isset($errors['status'])) : ?>
                                        <small class="text-danger"><?php echo $errors['status']; ?></small>
                                    <?php endif; ?>
                                </div>
                                <button type="submit" class="btn btn-primary w-25">Update Job</button>
                                <a href="viewJob.php" class="btn btn-danger py-3 w-25">Cancel</a>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="assets/js/jquery-3.6.0.min.js"></script>
    <script src="assets/js/popper.min.js"></script>
    <script src="assets/js/bootstrap.min.js"></script>
    <script src="assets/js/feather.min.js"></script>
    <script src="assets/plugins/slimscroll/jquery.slimscroll.min.js"></script>
    <script src="assets/plugins/select2/js/select2.min.js"></script>
    <script src="assets/js/script.js"></script>
</body>

</html>