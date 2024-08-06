<?php include("PHP_Connections/fetch_archives.php")?>
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
    <link rel="stylesheet" href="assets/css/archives.css">
</head>

<body class="scrollbar" id="style-5">
    <?php include("modal_logout.php");?>
    <?php include("modals_archive.php");?>
    <div class="main-wrapper">
        <?php include("navbar.php") ?>
        <div class="page-wrapper">
            <div class="container-fluid">
                <div class="breadcrumb-path mb-4 my-4">
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="view_archives_jobs.php"><img src="assets/img/dash.png" class="mr-2"
                                    alt="breadcrumb" />Archive</a>
                        </li>
                        <li class="breadcrumb-item active">Jobs</li>
                    </ul>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title d-inline">Archived Jobs</h4>
                                <div class="search-container d-inline float-right">
                                    <form action="view_archives_jobs.php" method="get" class="d-flex flex-wrap">
                                        <input type="text" name="search" class="form-control mr-2"
                                            placeholder="Search Archived  Jobs"
                                            style="flex: 1; min-width: 400px; border-radius: 30px;">
                                        <button class="btn" type="submit"
                                            style="background: none; border: none; padding: 0;">
                                            <i class="fas fa-search" style="color: #000;"></i>
                                            <!-- Set color to desired color -->
                                        </button>
                                    </form>
                                </div>
                            </div>
                            <div class="">

                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <thead class="text-center">
                                            <tr>
                                                <th>Title</th>
                                                <th>Position/Unit</th>
                                                <th>Description</th>
                                                <th class="w-25">Education Requirement</th>
                                                <th>Experience or Training</th>
                                                <th>Duties and Responsibilities</th>
                                                <th>Salary</th>
                                                <th>Department</th>
                                                <th>Place of Assignment</th>
                                                <th>Status</th>
                                                <th>Created At</th>
                                                <th>Updated At</th>
                                                <th>Deadline</th>
                                                <th>Archived By</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php if ($result_archive->num_rows > 0): ?>
                                            <?php while ($job = $result_archive->fetch_assoc()): ?>
                                            <?php
                        // Convert comma-separated requirements into HTML lists
                        $education_list = !empty($job['education_requirements']) 
                            ? '<ul><li>' . str_replace(', ', '</li><li>', htmlspecialchars($job['education_requirements'])) . '</li></ul>' 
                            : '';
                        $experience_list = !empty($job['experience_requirements']) 
                            ? '<ul><li>' . str_replace(', ', '</li><li>', htmlspecialchars($job['experience_requirements'])) . '</li></ul>' 
                            : '';
                        $duties_list = !empty($job['duties_and_responsibilities']) 
                            ? '<ul><li>' . str_replace(', ', '</li><li>', htmlspecialchars($job['duties_and_responsibilities'])) . '</li></ul>' 
                            : '';
                    ?>
                                            <tr class="text-center">
                                                <td><?php echo htmlspecialchars($job['job_title']); ?></td>
                                                <td><?php echo htmlspecialchars($job['position_or_unit']); ?></td>
                                                <td class="description-column">
                                                    <?php echo htmlspecialchars($job['description']); ?></td>
                                                <td><?php echo $education_list; ?></td>
                                                <td><?php echo $experience_list; ?></td>
                                                <td><?php echo $duties_list; ?></td>
                                                <td>₱<?php echo htmlspecialchars($job['salary']); ?></td>
                                                <td><?php echo htmlspecialchars($job['department_name']); ?></td>
                                                <td><?php echo htmlspecialchars($job['place_of_assignment']); ?></td>
                                                <td><?php echo htmlspecialchars($job['status']); ?></td>
                                                <td><?php echo formatDate($job['created_at']); ?></td>
                                                <td><?php echo formatDate($job['updated_at']); ?></td>
                                                <td><?php echo formatDateDeadline($job['deadline']); ?></td>
                                                <td><?php echo htmlspecialchars($job['archived_by']); ?></td>
                                                <td>
                                                    <a href="#" class="btn btn-success btn-sm restore-button"
                                                        data-id="<?php echo htmlspecialchars($job['jobarchive_id']); ?>">
                                                        <i class="fas fa-undo"></i>
                                                    </a>
                                                    <a href="PHP_Connections/deleteJob.php?id=<?php echo htmlspecialchars($job['jobarchive_id']); ?>"
                                                        class="btn btn-danger btn-sm delete-button"
                                                        data-id="<?php echo htmlspecialchars($job['jobarchive_id']); ?>">
                                                        <i class="fas fa-trash"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                            <?php endwhile; ?>
                                            <?php else: ?>
                                            <tr class="text-center">
                                                <td colspan="14">No archived Jobs found.</td>
                                            </tr>
                                            <?php endif; ?>
                                        </tbody>
                                    </table>
                                </div>

                                <nav aria-label="Page navigation border border-danger">
                                    <ul class="pagination justify-content-center my-3">
                                        <li class="page-item <?php if ($jobs_page <= 1) {
                                            echo 'disabled';
                                        } ?>">
                                            <a class="page-link" href="?jobs_page=<?php echo $jobs_page - 1; ?>"
                                                aria-label="Previous">
                                                <span aria-hidden="true">&laquo;</span>
                                                <span class="sr-only">Previous</span>
                                            </a>
                                        </li>

                                        <?php
                                                                            $jobs_start = max(1, $jobs_page - 1);
                                    $jobs_end = min($total_pages_jobs, $jobs_page + 1);

                                    if ($jobs_start > 1) {
                                        echo '<li class="page-item"><a class="page-link" href="?jobs_page=1">1</a></li>';
                                        if ($jobs_start > 2) {
                                            echo '<li class="page-item"><span class="page-link">...</span></li>';
                                        }
}

for ($i = $jobs_start; $i <= $jobs_end; $i++) : ?>
                                        <li class="page-item <?php if ($jobs_page == $i) {
                                            echo 'active';
                                        } ?>"><a class="page-link"
                                                href="?jobs_page=<?php echo $i; ?>"><?php echo $i; ?></a></li>
                                        <?php endfor;

if ($jobs_end < $total_pages_jobs) {
    if ($jobs_end < $total_pages_jobs - 1) {
        echo '<li class="page-item"><span class="page-link">...</span></li>';
    }
    echo '<li class="page-item"><a class="page-link" href="?jobs_page=' . $total_pages_jobs . '">' . $total_pages_jobs . '</a></li>';
}
?>

                                        <li class="page-item <?php if ($jobs_page >= $total_pages_jobs) {
                                                echo 'disabled';
                                            } ?>">
                                            <a class="page-link" href="?jobs_page=<?php echo $jobs_page + 1; ?>"
                                                aria-label="Next">
                                                <span aria-hidden="true">&raquo;</span>
                                                <span class="sr-only">Next</span>
                                            </a>
                                        </li>
                                    </ul>
                                </nav>

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
        <script src="assets/js/script.js"></script>
        <script src="assets/js/archive.js"></script>
        <?php
        if (isset($_GET['restored']) && $_GET['restored'] == 1) {
            echo "<div class='alert alert-success'>Announcement restored successfully!</div>";
        }
if (isset($_GET['error']) && $_GET['error'] == 1) {
    echo "<div class='alert alert-danger'>Failed to restore announcement.</div>";
}
if (isset($_GET['notfound']) && $_GET['notfound'] == 1) {
    echo "<div class='alert alert-warning'>Announcement not found in archive.</div>";
}
if (isset($_GET['invalid']) && $_GET['invalid'] == 1) {
    echo "<div class='alert alert-danger'>Invalid request.</div>";
}
?>

</body>

</html>