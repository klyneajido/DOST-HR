<?php include("PHP_Connections/fetch_jobs.php")?>
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
    <link rel="stylesheet" href="assets/css/jobs.css">
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
                            <a href=""><img src="assets/img/dash.png" class="mr-2" alt="breadcrumb" />Jobs</a>
                        </li>
                        <li class="breadcrumb-item active">Position</li>
                    </ul>

                </div>

                <?php if (!empty($errors)) : ?>
                <div class="alert alert-danger">
                    <?php foreach ($errors as $error) : ?>
                    <p><?php echo htmlspecialchars($error); ?></p>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>

                <div class="row">
                    <?php foreach ($jobs as $job) : ?>
                    <div class="col-lg-6">

                        <div class="card">
                            <div class="card-body shadow p-3">
                                <div class="p-3 d-flex justify-content-between align-items-center">
                                    <h5 class=""><strong>
                                            <?php 
                            if (empty($job['position_or_unit'])) : 
                                $position = ' '; 
                            else : 
                                $position = $job['position_or_unit']; 
                            endif;
                            echo htmlspecialchars($job['job_title'] . " " . $position); ?>
                                        </strong></h5>
                                    <div class="dropdown">
                                        <button class=" dropdown-toggle" type="button" id="dropdownMenuButton"
                                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <i class="fas fa-ellipsis-v"></i>
                                        </button>
                                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                            <a class="dropdown-item"
                                                href="PHP_Connections/archiveJobs.php?job_id=<?php echo $job['job_id']; ?>"
                                                onclick="return confirm('Are you sure you want to archive this job?');">Archive</a>
                                            <a class="dropdown-item"
                                                href="detailJob.php?job_id=<?php echo $job['job_id']; ?>">Edit</a>
                                        </div>
                                    </div>

                                </div>

                                <div class="mx-3 py-2">
                                    <p class="card-text pt-3"><strong>Description:</strong>
                                        <?php echo $job['description']; ?></p>


                                    <div class="row ">
                                        <div class="col-md-6">
                                            <p class="card-text"><strong>Department:</strong>
                                                <?php echo htmlspecialchars($job['department_name']); ?></p>
                                            <?php if (!empty($job['place_of_assignment'])) : ?>
                                            <p class="card-text"><strong>Place of Assignment:</strong>
                                                <?php echo htmlspecialchars($job['place_of_assignment']); ?></p>
                                            <?php endif; ?>
                                            <?php if ("COS" == ($job['status'])) : ?>
                                            <p class="card-text"><strong>Daily Salary:</strong> Php
                                                <?php echo htmlspecialchars(number_format($job['salary'], 2)); ?>
                                            </p>
                                            <?php else : ?>
                                            <p class="card-text"><strong>Monthly Salary:</strong> Php
                                                <?php echo htmlspecialchars(number_format($job['salary'], 2)); ?>
                                            </p>
                                            <?php endif; ?>
                                            <p class="card-text"><strong>Status:</strong>
                                                <?php echo htmlspecialchars($job['status']); ?></p>
                                        </div>

                                        <div class="col-md-6">
                                            <p class="card-text"><strong>Created At:</strong>
                                                <?php echo htmlspecialchars($job['created_at']); ?></p>
                                            <p class="card-text"><strong>Updated At:</strong>
                                                <?php echo htmlspecialchars($job['updated_at']); ?></p>
                                            <p class="card-text"><strong>Deadline:</strong>
                                                <?php echo htmlspecialchars($job['deadline']); ?></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        </a>
                    </div>
                    <?php endforeach; ?>

                </div>
                <nav aria-label="Page navigation">
                    <ul class="pagination justify-content-center mt-3">
                        <li class="page-item <?php if ($page <= 1) echo 'disabled'; ?>">
                            <a class="page-link" href="?page=<?php echo $page - 1; ?>" aria-label="Previous">
                                <span aria-hidden="true">&laquo;</span>
                                <span class="sr-only">Previous</span>
                            </a>
                        </li>
                        <?php
                    $start = max(1, $page - 2);
                    $end = min($total_pages, $page + 2);

                    if ($start > 1) {
                        echo '<li class="page-item"><a class="page-link" href="?page=1">1</a></li>';
                        if ($start > 2) {
                            echo '<li class="page-item"><span class="page-link">...</span></li>';
                        }
                    }

                    for ($i = $start; $i <= $end; $i++) : ?>
                        <li class="page-item <?php if ($i == $page) echo 'active'; ?>">
                            <a class="page-link" href="?page=<?php echo $i; ?>"><?php echo $i; ?></a>
                        </li>
                        <?php endfor;

                    if ($end < $total_pages) {
                        if ($end < $total_pages - 1) {
                            echo '<li class="page-item"><span class="page-link">...</span></li>';
                        }
                        echo '<li class="page-item"><a class="page-link" href="?page=' . $total_pages . '">' . $total_pages . '</a></li>';
                    }
                    ?>
                        <li class="page-item <?php if ($page >= $total_pages) echo 'disabled'; ?>">
                            <a class="page-link" href="?page=<?php echo $page + 1; ?>" aria-label="Next">
                                <span aria-hidden="true">&raquo;</span>
                                <span class="sr-only">Next</span>
                            </a>
                        </li>
                    </ul>
                </nav>
                <div class="user-menu">
                    <a href="addJob.php" class="btn btn-info btn-lg float-add-btn" title="Add Job">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="white"
                            class="bi bi-plus-circle-fill mb-1" viewBox="0 0 16 16">
                            <path
                                d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0M8.5 4.5a.5.5 0 0 0-1 0v3h-3a.5.5 0 0 0 0 1h3v3a.5.5 0 0 0 1 0v-3h3a.5.5 0 0 0 0-1h-3z" />
                        </svg>
                        Add Job
                    </a>
                </div>
                <div class="mobile-user-menu show">
                    <a href="addJob.php" class="btn btn-info btn-lg float-add-btn px-3 py-2" title="Add Job">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="white"
                            class="bi bi-plus-circle-fill mb-1" viewBox="0 0 16 16">
                            <path
                                d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0M8.5 4.5a.5.5 0 0 0-1 0v3h-3a.5.5 0 0 0 0 1h3v3a.5.5 0 0 0 1 0v-3h3a.5.5 0 0 0 0-1h-3z" />
                        </svg>
                </div>
            </div>
        </div>
    </div>
    <script src="assets/js/date.js"></script>
    <script src="assets/js/jquery-3.6.0.min.js"></script>
    <script src="assets/js/bootstrap.min.js"></script>
    <script src="assets/js/popper.min.js"></script>
    <script src="assets/js/bootstrap.min.js"></script>
    <script src="assets/js/jquery-3.6.0.min.js"></script>


    <script src="assets/js/feather.min.js"></script>

    <script src="assets/plugins/slimscroll/jquery.slimscroll.min.js"></script>

    <script src="assets/plugins/apexchart/apexcharts.min.js"></script>
    <script src="assets/plugins/apexchart/chart-data.js"></script>
    <script src="assets/js/viewJob.js"></script>
    <!-- sdsadasdasd -->

</body>

</html>