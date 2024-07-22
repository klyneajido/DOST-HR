<?php include("PHP_Connections/fetch_job_description.php")?>
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
    <link rel="stylesheet" href="assets/css/detailJob.css">
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
                    <h3 class="card-title">
                        <?php echo htmlspecialchars($job['job_title']." ". $job['position_or_unit']); ?></h3>
                </div>

                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card mb-4">
                                <div class="card-header d-flex">
                                    <h4 class="col-md-8 pt-2 border border-danger"> <strong>
                                            <?php echo htmlspecialchars($job['job_title'] ." ". $job['position_or_unit']); ?></strong>
                                    </h4>
                                    <div class="col-md-4 user-menu justify-content-end align-items-center border border-danger">
                                    <a href="editJob.php?job_id=<?php echo $job['job_id']; ?>"
                                        class="btn btn-primary py-3 px-5">Edit</a>
                                    </div>
                                </div>
                                <div class="card-body mx-3">
                                    <div class="mb-3">
                                        <h5><strong>Department</strong></h5>
                                        <p><?php echo htmlspecialchars($job['department_name']); ?></p>
                                    </div>
                                    <div class="mb-3">
                                        <h5><strong>Description</strong></h5>
                                        <p><?php echo nl2br(htmlspecialchars($job['description'])); ?></p>
                                    </div>
                                    
                                    <!-- Display job requirements -->
                                    <div class="mb-3">
                                        <h5><strong>Educational Requirements</strong></h5>
                                        <ul>
                                            <?php if (isset($requirements['education'])): ?>
                                                <?php foreach ($requirements['education'] as $requirement): ?>
                                                    <li><?php echo htmlspecialchars($requirement); ?></li>
                                                <?php endforeach; ?>
                                            <?php else: ?>
                                                <li>No educational requirements listed.</li>
                                            <?php endif; ?>
                                        </ul>
                                    </div>
                                    <div class="mb-3">
                                        <h5><strong>Experience or Training</strong></h5>
                                        <ul>
                                            <?php if (isset($requirements['experience'])): ?>
                                                <?php foreach ($requirements['experience'] as $requirement): ?>
                                                    <li><?php echo htmlspecialchars($requirement); ?></li>
                                                <?php endforeach; ?>
                                            <?php else: ?>
                                                <li>No experience or training requirements listed.</li>
                                            <?php endif; ?>
                                        </ul>
                                    </div>
                                    <div class="mb-3">
                                        <h5><strong>Duties and Responsibilities</strong></h5>
                                        <ul>
                                            <?php if (isset($requirements['duties'])): ?>
                                                <?php foreach ($requirements['duties'] as $requirement): ?>
                                                    <li><?php echo htmlspecialchars($requirement); ?></li>
                                                <?php endforeach; ?>
                                            <?php else: ?>
                                                <li>No duties and responsibilities listed.</li>
                                            <?php endif; ?>
                                        </ul>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <h5><strong>Place of Assignment</strong></h5>
                                                <p><?php echo nl2br(htmlspecialchars($job['place_of_assignment'])); ?></p>
                                            </div>
                                            <?php if ("COS" == ($job['status'])) : ?>
                                                <div class="mb-3">
                                                    <h5><strong>Daily Salary</strong></h5>
                                                    <p>₱<?php echo htmlspecialchars($job['salary']); ?></p>
                                                </div>
                                            <?php else : ?>
                                                <div class="mb-3">
                                                    <h5><strong>Salary</strong></h5>
                                                    <p>₱<?php echo htmlspecialchars($job['salary']); ?></p>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <h5><strong>Status</strong></h5>
                                                <p><?php echo htmlspecialchars($job['status']); ?></p>
                                            </div>
                                            <div class="mb-3">
                                                <h5><strong>Date Created</strong></h5>
                                                <p><?php echo htmlspecialchars($job['created_at']); ?></p>
                                            </div>
                                            <div class="mb-3">
                                                <h5><strong>Last Updated</strong></h5>
                                                <p><?php echo htmlspecialchars($job['updated_at']); ?></p>
                                            </div>
                                            <div class="mb-3">
                                                <h5><strong>Deadline</strong></h5>
                                                <p><?php echo htmlspecialchars($job['deadline']); ?></p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="assets/js/jquery-3.6.0.min.js"></script>
    <script src="assets/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/main.js"></script>
    <script src="assets/js/applicant.js"></script>
</body>
</html>
