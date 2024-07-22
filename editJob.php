<?php include("PHP_Connections/edit_job.php")?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0" />
    <title>Edit Job</title>
    <link rel="shortcut icon" href="assets/img/dost_logo.png" />
    <link rel="stylesheet" href="assets/css/bootstrap.min.css" />
    <link rel="stylesheet" href="assets/plugins/select2/css/select2.min.css" />
    <link rel="stylesheet" href="assets/plugins/fontawesome/css/fontawesome.min.css" />
    <link rel="stylesheet" href="assets/plugins/fontawesome/css/all.min.css" />
    <link rel="stylesheet" href="assets/css/jobs.css" />
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
    <?php include("navbar.php")?>
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
                                <div class="row col-md-12">
                                    <div class="form-group col-md-6">
                                        <label for="job_title">Job Title</label>
                                        <input type="text" name="job_title" id="job_title" class="form-control" value="<?php echo htmlspecialchars($job['job_title']); ?>">
                                        <?php if (isset($errors['job_title'])) : ?>
                                            <small class="text-danger"><?php echo $errors['job_title']; ?></small>
                                        <?php endif; ?>
                                    </div>            
                                    <div class="form-group col-md-6">
                                        <label for="position">Position</label>
                                        <input type="text" name="position" id="position" class="form-control" value="<?php echo htmlspecialchars($job['position_or_unit']); ?>">
                                        <?php if (isset($errors['position'])) : ?>
                                            <small class="text-danger"><?php echo $errors['position_or_unit']; ?></small>
                                        <?php endif; ?>
                                    </div>
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
                                <button type="submit" class="btn btn-primary py-3 w-25">Update Job</button>
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

