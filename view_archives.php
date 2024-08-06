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
    <link rel="stylesheet" href="assets/css/style.css">
</head>

<body class="scrollbar" id="style-5">
    <?php include("logout_modal.php");?>
    <?php include("archive_modals.php");?>
    <div class="main-wrapper">
        <?php include("navbar.php") ?>
        <div class="page-wrapper">
            <div class="container-fluid">
                <div class="breadcrumb-path mb-4 my-4">
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="archive.php"><img src="assets/img/dash.png" class="mr-2"
                                    alt="breadcrumb" />Archive</a>
                        </li>
                        <li class="breadcrumb-item active">Files</li>
                    </ul>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title d-inline">Archived Jobs</h4>
                                <div class="search-container d-inline float-right" style="margin-left: 20px;">
                                    <form action="archive.php" method="get" class="d-flex flex-wrap">
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
                            <div class="card-body">

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

                                <nav aria-label="Page navigation">
                                    <ul class="pagination justify-content-center mt-3">
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
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">Archived Announcements</h4>
                                <div class="search-container d-inline float-right" style="margin-left: 20px;">
                                    <form action="archive.php" method="get" class="d-flex flex-wrap">
                                        <input type="text" name="search_announcement" class="form-control mr-2"
                                            placeholder="Search Announcements"
                                            style="flex: 1; min-width: 400px; border-radius: 30px;"
                                            value="<?php echo htmlspecialchars($search_announcement); ?>">
                                        <button class="btn" type="submit"
                                            style="background: none; border: none; padding: 0;">
                                            <i class="fas fa-search" style="color: #000;"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                            <div class="card-body">


                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <thead class="text-center">
                                            <tr>
                                                <th>Title</th>
                                                <th>Description</th>
                                                <th>Link</th>
                                                <th>Image</th>
                                                <th>Created At</th>
                                                <th>Updated At</th>
                                                <th>Archived By</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody class="text-center">
                                            <?php if ($result_announcement_archive->num_rows > 0): ?>
                                            <?php while ($archive = $result_announcement_archive->fetch_assoc()): ?>
                                            <?php
                        // Encode the image_announcement BLOB data to base64
                        $image_data = base64_encode($archive['image_announcement']);
                    ?>
                                            <tr>
                                                <td><?php echo htmlspecialchars($archive['title']); ?></td>
                                                <td><?php echo htmlspecialchars($archive['description_announcement']); ?>
                                                </td>
                                                <td>
                                                    <a href="<?php echo htmlspecialchars($archive['link']); ?>"
                                                        target="_blank">
                                                        <?php echo htmlspecialchars($archive['link']); ?>
                                                    </a>
                                                </td>
                                                <td>
                                                    <img src="data:image/jpeg;base64,<?php echo $image_data; ?>"
                                                        alt="Image" width="50" height="50">
                                                </td>
                                                <td><?php echo formatDate($archive['created_at']); ?></td>
                                                <td><?php echo formatDate($archive['updated_at']); ?></td>
                                                <td><?php echo htmlspecialchars($archive['archived_by']); ?></td>
                                                <td>
                                                    <a href="#"
                                                        class="btn btn-success btn-sm restore-announcement-button"
                                                        data-id="<?php echo htmlspecialchars($archive['announcement_id']); ?>">
                                                        <i class="fas fa-undo"></i>
                                                    </a>
                                                    <a href="PHP_Connections/deleteAnnouncement.php?id=<?php echo htmlspecialchars($archive['announcement_id']); ?>"
                                                        class="btn btn-danger btn-sm delete-announcement-button"
                                                        data-id="<?php echo htmlspecialchars($archive['announcement_id'], ENT_QUOTES, 'UTF-8'); ?>">
                                                        <i class="fas fa-trash"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                            <?php endwhile; ?>
                                            <?php else: ?>
                                            <tr>
                                                <td colspan="8">No archived announcements found.</td>
                                            </tr>
                                            <?php endif; ?>
                                        </tbody>
                                    </table>
                                </div>



                                <nav aria-label="Page navigation">
                                    <ul class="pagination justify-content-center mt-3">
                                        <li class="page-item <?php if ($announcements_page <= 1) {
                                            echo 'disabled';
                                        } ?>">
                                            <a class="page-link"
                                                href="?announcements_page=<?php echo $announcements_page - 1; ?>"
                                                aria-label="Previous">
                                                <span aria-hidden="true">&laquo;</span>
                                                <span class="sr-only">Previous</span>
                                            </a>
                                        </li>

                                        <?php
                                        $announcements_start = max(1, $announcements_page - 1);
$announcements_end = min($total_pages_announcements, $announcements_page + 1);

if ($announcements_start > 1) {
    echo '<li class="page-item"><a class="page-link" href="?announcements_page=1">1</a></li>';
    if ($announcements_start > 2) {
        echo '<li class="page-item"><span class="page-link">...</span></li>';
    }
}

for ($i = $announcements_start; $i <= $announcements_end; $i++) : ?>
                                        <li class="page-item <?php if ($announcements_page == $i) {
                                            echo 'active';
                                        } ?>"><a class="page-link"
                                                href="?announcements_page=<?php echo $i; ?>"><?php echo $i; ?></a></li>
                                        <?php endfor;

if ($announcements_end < $total_pages_announcements) {
    if ($announcements_end < $total_pages_announcements - 1) {
        echo '<li class="page-item"><span class="page-link">...</span></li>';
    }
    echo '<li class="page-item"><a class="page-link" href="?announcements_page=' . $total_pages_announcements . '">' . $total_pages_announcements . '</a></li>';
}
?>

                                        <li class="page-item <?php if ($announcements_page >= $total_pages_announcements) {
                                                echo 'disabled';
                                            } ?>">
                                            <a class="page-link"
                                                href="?announcements_page=<?php echo $announcements_page + 1; ?>"
                                                aria-label="Next">
                                                <span aria-hidden="true">&raquo;</span>
                                                <span class="sr-only">Next</span>
                                            </a>
                                        </li>
                                    </ul>
                                </nav>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">Archived Applicants</h4>
                                <div class="search-container d-inline float-right" style="margin-left: 20px;">
                                    <form action="archive.php" method="get" class="d-flex flex-wrap">
                                        <input type="text" name="search_applicant" class="form-control mr-2"
                                            placeholder="Search Applicants"
                                            style="flex: 1; min-width: 400px; border-radius: 30px;"
                                            value="<?php echo htmlspecialchars($search_applicant); ?>">
                                        <button class="btn" type="submit"
                                            style="background: none; border: none; padding: 0;">
                                            <i class="fas fa-search" style="color: #000;"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <thead class="text-center">
                                            <tr>
                                                <th>Job Title</th>
                                                <th>Position</th>
                                                <th>Plantilla No.</th>
                                                <th>Full Name</th>
                                                <th>Email</th>
                                                <th>Contact Number</th>
                                                <th>Application Date</th>
                                                <th>Interview Date</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody class="text-center">
                                            <?php if ($result_applicant_archive->num_rows > 0): ?>
                                            <?php while ($applicant = $result_applicant_archive->fetch_assoc()): ?>
                                            <tr>
                                                <td><?php echo htmlspecialchars($applicant['job_title']); ?></td>
                                                <td><?php echo htmlspecialchars($applicant['position_or_unit']); ?></td>
                                                <td><?php echo isset($applicant['plantilla']) && !empty($applicant['plantilla']) ? htmlspecialchars($applicant['plantilla']) : 'N/A'; ?>
                                                </td>
                                                <td><?php echo htmlspecialchars($applicant['firstname']) . ' ' . htmlspecialchars($applicant['lastname']); ?>
                                                </td>
                                                <td><?php echo htmlspecialchars($applicant['email']); ?></td>
                                                <td><?php echo htmlspecialchars($applicant['contact_number']); ?></td>
                                                <td><?php echo isset($applicant['application_date']) ? formatDate($applicant['application_date']) : 'N/A'; ?>
                                                </td>
                                                <td><?php echo isset($applicant['interview_date']) ? formatDate($applicant['interview_date']) : 'N/A'; ?>
                                                </td>
                                                <td>
                                                    <a href="PHP_Connections/deleteApplicant.php?id=<?php echo htmlspecialchars($applicant['applicantarchive_id']); ?>"
                                                        class="btn btn-danger btn-sm delete-applicant-button"
                                                        data-id="<?php echo htmlspecialchars($applicant['applicantarchive_id'], ENT_QUOTES, 'UTF-8'); ?>">
                                                        <i class="fas fa-trash"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                            <?php endwhile; ?>
                                            <?php else: ?>
                                            <tr>
                                                <td colspan="9">No archived applicants found.</td>
                                            </tr>
                                            <?php endif; ?>
                                        </tbody>
                                    </table>
                                </div>


                                <nav aria-label="Page navigation">
                                    <ul class="pagination justify-content-center mt-3">
                                        <li class="page-item <?php if ($applicants_page <= 1) {
                    echo 'disabled';
                } ?>">
                                            <a class="page-link"
                                                href="?applicants_page=<?php echo $applicants_page - 1; ?>"
                                                aria-label="Previous">
                                                <span aria-hidden="true">&laquo;</span>
                                                <span class="sr-only">Previous</span>
                                            </a>
                                        </li>

                                        <?php
                $applicants_start = max(1, $applicants_page - 1);
$applicants_end = min($total_pages_applicants, $applicants_page + 1);

if ($applicants_start > 1) {
    echo '<li class="page-item"><a class="page-link" href="?applicants_page=1">1</a></li>';
    if ($applicants_start > 2) {
        echo '<li class="page-item"><span class="page-link">...</span></li>';
    }
}

for ($i = $applicants_start; $i <= $applicants_end; $i++) : ?>
                                        <li class="page-item <?php if ($applicants_page == $i) {
                    echo 'active';
                } ?>"><a class="page-link" href="?applicants_page=<?php echo $i; ?>"><?php echo $i; ?></a></li>
                                        <?php endfor;

if ($applicants_end < $total_pages_applicants) {
    if ($applicants_end < $total_pages_applicants - 1) {
        echo '<li class="page-item"><span class="page-link">...</span></li>';
    }
    echo '<li class="page-item"><a class="page-link" href="?applicants_page=' . $total_pages_applicants . '">' . $total_pages_applicants . '</a></li>';
}
?>

                                        <li class="page-item <?php if ($applicants_page >= $total_pages_applicants) {
                    echo 'disabled';
                } ?>">
                                            <a class="page-link"
                                                href="?applicants_page=<?php echo $applicants_page + 1; ?>"
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