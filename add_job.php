<?php include("PHP_Connections/insert_job.php");?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0" />
    <title>Add Job</title>
    <link rel="shortcut icon" href="assets/img/dost_logo.png">
    <link rel="stylesheet" href="assets/css/bootstrap.min.css" />
    <link rel="stylesheet" href="assets/plugins/select2/css/select2.min.css" />
    <link rel="stylesheet" href="assets/plugins/fontawesome/css/fontawesome.min.css" />
    <link rel="stylesheet" href="assets/plugins/fontawesome/css/all.min.css" />
    <link rel="stylesheet" href="assets/css/jobs.css" />
</head>
<body class="scrollbar" id="style-5">
    <div class="main-wrapper">
        <?php include("navbar.php") ?>
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


                            <div class="container">
                                <!-- START FORM -->
                                <form id="addJobForm" method="POST" action="add_job.php" class="needs-validation" novalidate>
                                <?php if (!empty($errors)) : ?>
                            <div class=" col-md-12 alert alert-danger">
                                <ul>
                                    <?php foreach ($errors as $error) : ?>
                                    <li><?php echo htmlspecialchars($error); ?></li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                            <?php endif; ?>
                                    <div class="row py-2">
                                        <div class="form-group col-md-6">
                                            <label for="job_title">Job Title</label>
                                            <input type="text" name="job_title" id="job_title" class="form-control"
                                                value="" autocomplete="off" required>
                                            <div class="invalid-feedback">
                                                Please Enter a Job Title.
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
                                                <input type="text" name="educationrequirement[]" class="form-control"
                                                    placeholder="Enter Education Requirement" autocomplete="off" required>
                                                <button type="button" class="btn btn-outline-secondary ml-2"
                                                    onclick="addField('educationrequirement')">+</button>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Experience Requirements -->
                                    <div class="form-group py-2">
                                        <label for="experience">Experience</label>
                                        <div id="experience-container" class="d-flex flex-column">
                                            <div class="d-flex mb-2">
                                                <input type="text" name="experience[]" class="form-control"
                                                    placeholder="Enter Experience Requirement" autocomplete="off" required>
                                                <button type="button" class="btn btn-outline-secondary ml-2"
                                                    onclick="addField('experience')">+</button>
                                            </div>
                                        </div>
                                    </div>

                                    <!--Training Requirements -->
                                    <div class="form-group py-2">
                                        <label for="training">Training</label>
                                        <div id="training-container" class="d-flex flex-column">
                                            <div class="d-flex mb-2">
                                                <input type="text" name="training[]" class="form-control"
                                                    placeholder="Enter Training Requirement" autocomplete="off" required>
                                                <button type="button" class="btn btn-outline-secondary ml-2"
                                                    onclick="addField('training')">+</button>
                                            </div>
                                        </div>
                                    </div>

                                    
                                    <!--Eligibility Requirements -->
                                    <div class="form-group py-2">
                                        <label for="eligibility">Eligibility</label>
                                        <div id="eligibility-container" class="d-flex flex-column">
                                            <div class="d-flex mb-2">
                                                <input type="text" name="eligibility[]" class="form-control"
                                                    placeholder="Enter Eligibility Requirement" autocomplete="off" required>
                                                <button type="button" class="btn btn-outline-secondary ml-2"
                                                    onclick="addField('eligibility')">+</button>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Duties and Responsibilities -->
                                    <div class="form-group py-2">
                                        <label for="dutiesandresponsibilities">Duties and Responsibilities</label>
                                        <div id="dutiesandresponsibilities-container" class="d-flex flex-column">
                                            <div class="d-flex mb-2">
                                                <input type="text" name="dutiesandresponsibilities[]" class="form-control"
                                                    placeholder="Enter Duty or Responsibility" autocomplete="off" required>
                                                <button type="button" class="btn btn-outline-secondary ml-2"
                                                    onclick="addField('dutiesandresponsibilities')">+</button>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group py-2">
                                        <label for="comptencies">Preffered Competencies</label>
                                        <div id="competencies-container" class="d-flex flex-column">
                                            <div class="d-flex mb-2">
                                                <input type="text" name="competencies[]" class="form-control"
                                                    placeholder="Enter Preffered Competencies Requirement" autocomplete="off" required>
                                                <button type="button" class="btn btn-outline-secondary ml-2"
                                                    onclick="addField('competencies')">+</button>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group py-2">
                                        <label for="department_id">Department</label>
                                        <select name="department_id" id="department_id" class="form-control form-select" required>
                                            <?php foreach ($departments as $department) : ?>
                                            <option value="<?php echo htmlspecialchars($department['department_id']); ?>">
                                                <?php echo htmlspecialchars($department['name']); ?>
                                            </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    
                                    <div class="row py-2">
                                        <div class="form-group col-md-6">
                                            <label for="poa">Place of Assignment</label>
                                            <input type="text" name="poa" id="poa" class="form-control" value=""
                                                autocomplete="off">
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="salary">Salary</label>
                                            <input type="number" step="0.01" name="salary" id="salary"
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
                                        <button class="col-md-5 btn btn-info" type="button" data-toggle="modal" data-target="#confirmModal">Add Job</button>
                                        <a href="view_jobs.php" class="col-md-5 btn btn-danger">Cancel</a>
                                    </div>
                                </form>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Confirmation Modal -->
    <div class="modal fade" id="confirmModal" tabindex="-1" aria-labelledby="confirmModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="confirmModalLabel">Confirm Add Job</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    Are you sure you want to add this job?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" id="confirmAddJob">Add Job</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
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
    <script src="assets/js/add_job.js"></script>

    <script>
        // // Function to add field dynamically
        // function addField(section) {
        //     const container = document.getElementById(`${section}-container`);
        //     const div = document.createElement('div');
        //     div.className = 'd-flex mb-2';

        //     const input = document.createElement('input');
        //     input.type = 'text';
        //     input.name = `${section}[]`;
        //     input.className = 'form-control';
        //     input.placeholder = `Enter ${section.replace(/([A-Z])/g, ' $1').toLowerCase()} requirement`;
        //     input.autocomplete = 'off';
        //     input.required = true;

        //     const button = document.createElement('button');
        //     button.type = 'button';
        //     button.className = 'btn btn-outline-secondary ml-2';
        //     button.textContent = '-';
        //     button.onclick = () => container.removeChild(div);

        //     div.appendChild(input);
        //     div.appendChild(button);
        //     container.appendChild(div);
        // }

        // // Handle confirmation modal
        // document.getElementById('confirmAddJob').addEventListener('click', function () {
        //     document.getElementById('addJobForm').submit();
        // });
    </script>
</body>

</html>
