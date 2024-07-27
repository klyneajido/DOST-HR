<?php include("PHP_Connections/insert_job.php");
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

    <link rel="stylesheet" href="assets/css/jobs.css" />
    <!--[if lt IE 9]>
      <script src="assets/js/html5shiv.min.js"></script>
      <script src="assets/js/respond.min.js"></script>
    <![endif]-->

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
            <div class="row">
                <div class="col-md-9 mx-auto my-5">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Add New Job</h4>
                        </div>
                        <div class="card-body d-flex justify-content-center">
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
                            <div class="container">
                                <!-- START FORM -->
                                <form method="POST" action="addJob.php"
                                    onsubmit="return confirm('Are you sure you want to add this job?');"
                                    class="needs-validation" novalidate>
                                    <div class="row py-2">
                                        <div class="form-group col-md-6 ">
                                            <label for="job_title">Job Title</label>
                                            <input type="text" name="job_title" id="job_title" class="form-control"
                                                value="" autocomplete="off" required>
                                            <div class="invalid-feedback">
                                                Please Enter a Job.
                                            </div>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="position">Position</label>
                                            <input type="text" name="position" id="position" class="form-control"
                                                value="" autocomplete="off" required>
                                        </div>
                                    </div>

                                    <div class="form-group py-1">
                                        <label for="description">Description</label>
                                        <textarea name="description" id="description" class="form-control" rows="5"
                                            autocomplete="off" required></textarea>
                                    </div>
                                    <div class="form-group py-2">
                                        <label for="educationrequirement">Education Requirement/s</label>
                                        <div id="educationrequirement-container" class="d-flex flex-column">
                                            <div class="d-flex mb-2">
                                                <input type="text" name="educationrequirement[]"
                                                    class="form-control" placeholder="Enter Education Requirement"
                                                    autocomplete="off" required>
                                                <button type="button" class="btn btn-outline-secondary ml-2"
                                                    onclick="addField('educationrequirement')">+</button>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Experience or Training Requirements -->
                                    <div class="form-group py-2">
                                        <label for="experienceortraining">Experience or Training</label>
                                        <div id="experienceortraining-container" class="d-flex flex-column">
                                            <div class="d-flex mb-2">
                                                <input type="text" name="experienceortraining[]"
                                                    class="form-control" placeholder="Enter experience or training requirement"
                                                    autocomplete="off" required>
                                                <button type="button" class="btn btn-outline-secondary ml-2"
                                                    onclick="addField('experienceortraining')">+</button>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Duties and Responsibilities -->
                                    <div class="form-group">
                                        <label for="dutiesandresponsibilities">Duties and Responsibilities</label>
                                        <div id="dutiesandresponsibilities-container" class="d-flex flex-column">
                                            <div class="d-flex mb-2">
                                                <input type="text" name="dutiesandresponsibilities[]"
                                                    class="form-control" placeholder="Enter duty or responsibility"
                                                    autocomplete="off" required>
                                                <button type="button" class="btn btn-outline-secondary ml-2"
                                                    onclick="addField('dutiesandresponsibilities')">+</button>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group py-2">
                                        <label for="department_id">Department</label>
                                        <select name="department_id" id="department_id" class="form-control  form-select" required>
                                            <?php foreach ($departments as $department) : ?>
                                            <option
                                                value="<?php echo htmlspecialchars($department['department_id']); ?>">
                                                <?php echo htmlspecialchars($department['name']); ?>
                                            </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <div class="row py-2">
                                        <div class="form-group col-md-6">
                                            <label for="poa">Place of Assignment</label>
                                            <input type="text" name="poa" id="poa" class="form-control" value=""
                                                autocomplete="off" required>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="monthlysalary">Salary</label>
                                            <input type="number" step="0.01" name="monthlysalary" id="monthly_salary"
                                                class="form-control" value="" min="0" max="9999999.99"
                                                oninput="validateSalaryInput(this)" required>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-md-6">
                                            <label for="deadline">Deadline</label>
                                            <input type="date" name="deadline" id="deadline" class="form-control"
                                                value="" required />
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="status">Status</label>
                                            <select name="status" id="status" class="form-control">
                                                <option value="Permanent">Permanent</option>
                                                <option value="COS">COS</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="d-flex justify-content-between mx-1">
                                        <button class="col-md-5 btn btn-info" type="submit">Add Job</button>
                                        <a href="viewJob.php" class="col-md-5 btn btn-danger">Cancel</a>
                                    </div>
                                </form>
                            </div>

                        </div>
                    </div>
                </div>
            </div>

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
    <script src="assets/js/addJob.js"></script>
</body>

</html>