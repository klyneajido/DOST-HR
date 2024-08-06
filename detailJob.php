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
                </div>

                <div class="">
                    <div class="row">
                        <div class="col-lg-8 col-md-12 mb-4">
                            <div class="card h-100">
                                <div class="card-header d-flex">
                                    <h4 class="pt-2"><strong><?php echo htmlspecialchars($job['job_title'] ." ". $job['position_or_unit']); ?></strong></h4>
                                </div>
                                <div class="card-body">
                                    <div class="col-md-12 mb-3">
                                        <h6><strong>Description</strong></h6>
                                        <p><?php echo nl2br(htmlspecialchars($job['description'])); ?></p>
                                    </div>
                                    <div class="col-md-12 mb-3">
                                        <div class="">
                                            <!-- Display job requirements -->
                                            <div class="mb-3">
                                                <h6><strong>Educational Requirements</strong></h6>
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
                                                <h6><strong>Experience or Training</strong></h6>
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
                                                <h6><strong>Duties and Responsibilities</strong></h6>
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
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-12 mb-4">
                            <div class="card h-100">
                                <div class="card-header d-flex border-0">
                                    <div class="col-md-12">
                                        <a href="editJob.php?job_id=<?php echo $job['job_id']; ?>" class="col-md-12 btn btn-primary py-3">Edit</a>
                                    </div>
                                </div>
                                <div class="card-body pl-2">
                                    <div class="pl-4 mb-2">
                                        <h6><strong>Department</strong></h6>
                                        <p><?php echo htmlspecialchars($job['department_name']); ?></p>
                                    </div>
                                    <div class="pl-4 mb-2">
                                        <h6><strong>Place of Assignment</strong></h6>
                                        <p><?php echo nl2br(htmlspecialchars($job['place_of_assignment'])); ?></p>
                                    </div>
                                    <div class="pl-4 mb-2">
                                        <?php if ("COS" == ($job['status'])) : ?>
                                        <div class="mb-2">
                                            <h6><strong>Daily Salary</strong></h6>
                                            <p>₱<?php echo number_format($job['salary'], 2); ?></p>
                                        </div>
                                        <?php else : ?>
                                        <div class="mb-2">
                                            <h6><strong>Monthly Salary</strong></h6>
                                            <p>₱<?php echo number_format($job['salary'], 2); ?></p>
                                        </div>
                                        <?php endif; ?>
                                    </div>
                                    <div class="pl-4 mb-3">
                                        <h6><strong>Status</strong></h6>
                                        <p><?php echo htmlspecialchars($job['status']); ?></p>
                                    </div>
                                    <div class="pl-4 mb-2">
                                        <h6><strong>Created</strong></h6>
                                        <p><?php echo formatDate($job['created_at']); ?></p>
                                    </div>
                                    <div class="pl-4 mb-2">
                                        <h6><strong>Updated</strong></h6>
                                        <p><?php echo formatDate($job['updated_at']); ?></p>
                                    </div>
                                    <div class="pl-4">
                                        <h6><strong>Deadline</strong></h6>
                                        <p><?php echo formatDateDeadline($job['deadline']); ?></p>
                                    </div>
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
    <script src="assets/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/main.js"></script>
    <script src="assets/js/script.js"></script>
</body>

</html>
