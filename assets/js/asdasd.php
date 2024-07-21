<?php include("PHP_Connections/insert_job.php") ?>
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
        <?php include("navbar.php") ?>
        <div class="page-wrapper">
            <div class="row">
                <div class="col-md-9 mx-auto my-5">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Add New Job</h4>
                        </div>
                        <div class="card-body">
                            <form method="POST" action="addJob.php" autocomplete="off"
                                onsubmit="return confirm('Are you sure you want to add this job?');"
                                class="row g-3 needs-validation" enctype="multipart/form-data" novalidate>
                                <div class="col-md-6">
                                    <label for="job_title" class="form-label">Job Title</label>
                                    <input type="text" name="job_title" id="job_title" class="form-control" value=""
                                        required>
                                    <div class="valid-feedback">
                                        Looks good!
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <label for="position_or_unit" class="form-label">Position/Unit</label>
                                    <input type="text" name="position_or_unit" id="position_or_unit"
                                        class="form-control" value="" required>
                                    <div class="valid-feedback">
                                        Looks good!
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <label for="description" class="form-label">Description</label>
                                    <input type="text" name="description" id="description" class="form-control" value=""
                                        required>
                                    <div class="valid-feedback">
                                        Looks good!
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <label for="educational_requirement" class="form-label">Educational Requirement/s</label>
                                    <div id="educational_requirement-container">
                                        <input type="text" name="educational_requirement[]" class="form-control mb-2"
                                            placeholder="Enter an educational requirement">
                                    </div>
                                    <button type="button" class="btn btn-secondary"
                                        onclick="addField('educational_requirement')">Add More</button>
                                    <div class="valid-feedback">
                                        Looks good!
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <label for="experienceortraining" class="form-label">Experience/Training Requirement/s</label>
                                    <div id="experienceortraining-container">
                                        <input type="text" name="experienceortraining[]" class="form-control mb-2"
                                            placeholder="Enter an Experience/Training Requirement">
                                    </div>
                                    <button type="button" class="btn btn-secondary"
                                        onclick="addField('experienceortraining')">Add More</button>
                                    <div class="valid-feedback">
                                        Looks good!
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <label for="dutiesandresponsibilities" class="form-label">Duties and Responsibilities</label>
                                    <div id="dutiesandresponsibilities-container">
                                        <input type="text" name="dutiesandresponsibilities[]" class="form-control mb-2"
                                            placeholder="Enter a Duty/Responsibility Requirement">
                                    </div>
                                    <button type="button" class="btn btn-secondary"
                                        onclick="addField('dutiesandresponsibilities')">Add More</button>
                                    <div class="valid-feedback">
                                        Looks good!
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <label for="department_id" class="form-label">Department <span class="red-asterisk">*</span></label>
                                    <select name="department_id" id="department_id" class="form-select" required>
                                        <option value="" disabled selected>Select</option>
                                        <?php foreach ($departments as $department) : ?>
                                        <option value="<?php echo htmlspecialchars($department['department_id']); ?>">
                                            <?php echo htmlspecialchars($department['name']); ?>
                                        </option>
                                        <?php endforeach; ?>
                                    </select>
                                    <div class="valid-feedback">
                                        Looks good!
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <label for="poa" class="form-label">Place of Assignment</label>
                                    <input type="text" name="poa" id="poa" class="form-control" value="" required>
                                    <div class="valid-feedback">
                                        Looks good!
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <label for="monthlysalary" class="form-label">Salary</label>
                                    <input type="text" name="monthlysalary" id="monthlysalary"
                                        class="form-control" value="" required>
                                    <div class="valid-feedback">
                                        Looks good!
                                    </div>
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

                                <div class="col-md-6">
                                    <label for="deadline" class="form-label">Deadline</label>
                                    <input type="text" name="deadline" id="deadline"
                                        class="form-control" value="" required>
                                    <div class="valid-feedback">
                                        Looks good!
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <label for="status" class="form-label">Status <span class="red-asterisk">*</span></label>
                                    <select name="status" id="status" class="form-select" required>
                                        <option value="" disabled selected>Select</option>
                                        <option value="Permanent">Permanent</option>
                                        <option value="COS">COS</option>
                                    </select>
                                    <div class="valid-feedback">
                                        Looks good!
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <button type="submit" class="btn btn-primary w-25">Add Job</button>
                                    <a href="viewJob.php" class="btn btn-danger py-3 w-25">Cancel</a>
                                </div>
                            </form>

                            <script>
                                function addField(containerId) {
                                    const container = document.getElementById(containerId + '-container');
                                    const input = document.createElement('input');
                                    input.type = 'text';
                                    input.name = containerId + '[]';
                                    input.className = 'form-control mb-2';
                                    input.placeholder = 'Enter ' + containerId.replace(/([A-Z])/g, ' $1').toLowerCase();
                                    container.appendChild(input);
                                }
                            </script>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="assets/js/jquery-3.6.0.min.js"></script>
    <script src="assets/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/jquery.slimscroll.min.js"></script>
    <script src="assets/js/app.js"></script>
    <script src="assets/plugins/select2/js/select2.min.js"></script>
    <script>
        $('#confirmLogout').click(function () {
            window.location.href = 'logout.php';
        });

        $(document).ready(function () {
            $('.select2').select2();
        });

        (() => {
            'use strict';
            const forms = document.querySelectorAll('.needs-validation');
            Array.prototype.slice.call(forms).forEach((form) => {
                form.addEventListener('submit', (event) => {
                    if (!form.checkValidity()) {
                        event.preventDefault();
                        event.stopPropagation();
                    }
                    form.classList.add('was-validated');
                }, false);
            });
        })();

        $('#deadline').datepicker({
            autoclose: true,
            format: 'yyyy-mm-dd'
        });
    </script>
</body>

</html>
