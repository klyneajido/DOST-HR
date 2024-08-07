<?php include("PHP_Connections/fetch_archives_applicants.php")?>
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
    <?php include("modal_logout.php");?>
    <?php include("modals_archive.php");?>
    <div class="main-wrapper">
        <?php include("navbar.php") ?>
        <div class="page-wrapper">
            <div class="container-fluid">
                <div class="breadcrumb-path mb-4 my-4">
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="view_archives.php"><img src="assets/img/dash.png" class="mr-2"
                                    alt="breadcrumb" />Archive</a>
                        </li>
                        <li class="breadcrumb-item active">Files</li>
                    </ul>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">Archived Applicants</h4>
                                <div class="search-container d-inline float-right" style="margin-left: 20px;">
                                    <input type="text" id="searchInput" class="form-control mr-2"
                                        placeholder="Search Applicants"
                                        style="flex: 1; min-width: 400px; border-radius: 30px;">
                                </div>

                            </div>
                            <div>
                                <div class="table-responsive">
                                    <table id="applicantsTable" class="table table-striped">
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
                                                <td>
                                                    <?php 
                                                echo isset($applicant['plantilla']) && !empty($applicant['plantilla']) 
                                                    ? htmlspecialchars($applicant['plantilla']) 
                                                    : '<span class="font-italic">N/A</span>'; 
                                                ?>
                                                </td>

                                                <td><?php echo htmlspecialchars($applicant['firstname']) . ' ' . htmlspecialchars($applicant['lastname']); ?>
                                                </td>
                                                <td><?php echo htmlspecialchars($applicant['email']); ?></td>
                                                <td><?php echo htmlspecialchars($applicant['contact_number']); ?></td>
                                                <td><?php echo isset($applicant['application_date']) ? formatDate($applicant['application_date']) : 'N/A'; ?>
                                                </td>
                                                <td>
                                                    <?php 
                                                echo isset($applicant['interview_date']) && !empty($applicant['interview_date']) 
                                                    ? htmlspecialchars(formatDate($applicant['interview_date']))
                                                    : '<span class="font-italic">N/A</span>'; 
                                                ?>
                                                </td>
                                                <td>
                                                    <a href="#" class="btn btn-danger btn-sm delete-applicant-button"
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
                                    <ul class="pagination justify-content-center my-3">
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
        <script src="assets/js/archives_applicants.js"></script>
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