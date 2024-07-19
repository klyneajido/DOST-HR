<?php include_once("PHP_Connections/fetch_applicants.php")?>

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
    <link rel="stylesheet" href="assets/css/applicant.css">
    <!-- [if lt IE 9]>
			<script src="assets/js/html5shiv.min.js"></script>
			<script src="assets/js/respond.min.js"></script>
		<![endif] -->
</head>

<body class="scrollbar" id="style-5">
    <!-- Modal for Delete Confirmation -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel">Confirm Delete</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    Are you sure you want to delete this applicant?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-danger" id="confirmDelete">Delete</button>
                </div>
            </div>
        </div>
    </div>

    <div class="main-wrapper">

        <?php include("navbar.php") ?>
        <div class="page-wrapper">
            <div class="container-fluid">
                <!-- Breadcrumb section -->
                <div class="breadcrumb-path mb-4 my-4">
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href=""><img src="assets/img/dash.png" class="mr-2" alt="breadcrumb" />Applicants</a>
                        </li>
                    </ul>
                </div>
                <!-- Table section -->
                <div class="col-xl-12 col-sm-12 col-12 pb-3">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between ">
                            <h2 class="card-titles">Applicants</h2>
                            <!-- Rows per page dropdown -->
                            <div class="form-group d-flex text-center rows_per_page">
                                <label for="rows_per_page " class="mr-2">Rows</label>
                                <select class="form-control" id="rows_per_page" onchange="changeRowsPerPage()">
                                    <option value="10" <?php echo $rows_per_page == 10 ? 'selected' : ''; ?>>10</option>
                                    <option value="20" <?php echo $rows_per_page == 20 ? 'selected' : ''; ?>>20</option>
                                    <option value="50" <?php echo $rows_per_page == 50 ? 'selected' : ''; ?>>50</option>
                                    <option value="100" <?php echo $rows_per_page == 100 ? 'selected' : ''; ?>>100
                                    </option>
                                </select>
                            </div>

                        </div>
                        <div class="table-responsive">
                            <table class="table custom-table no-footer text-center">
                                <thead>
                                    <tr>
                                        <!-- <th>ID</th> -->
                                        <th>Job Title</th>
                                        <th>Last Name</th>
                                        <th>First Name</th>
                                        <th>Middle Name</th>
                                        <th>Sex</th>
                                        <th>Address</th>
                                        <th>Email</th>
                                        <th>Contact Number</th>
                                        <th>Course</th>
                                        <th>Years of Experience</th>
                                        <th>Hours of Training</th>
                                        <th>Eligibility</th>
                                        <th>List of Awards</th>
                                        <th>Attachments</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($applicants)) : ?>
                                    <?php foreach ($applicants as $applicant) : ?>
                                    <tr>
                                        <!-- <td><?php echo htmlspecialchars($applicant['id']); ?></td> -->
                                        <td><?php echo htmlspecialchars($applicant['job_title']); ?></td>
                                        <td><?php echo htmlspecialchars($applicant['lastname']); ?></td>
                                        <td><?php echo htmlspecialchars($applicant['firstname']); ?></td>
                                        <td><?php echo htmlspecialchars($applicant['middlename']); ?></td>
                                        <td><?php echo htmlspecialchars($applicant['sex']); ?></td>
                                        <td><?php echo htmlspecialchars($applicant['address']); ?></td>
                                        <td><?php echo htmlspecialchars($applicant['email']); ?></td>
                                        <td><?php echo htmlspecialchars($applicant['contact_number']); ?></td>
                                        <td><?php echo htmlspecialchars($applicant['course']); ?></td>
                                        <td><?php echo htmlspecialchars($applicant['years_of_experience']); ?></td>
                                        <td><?php echo htmlspecialchars($applicant['hours_of_training']); ?></td>
                                        <td><?php echo htmlspecialchars($applicant['eligibility']); ?></td>
                                        <td><?php echo htmlspecialchars($applicant['list_of_awards']); ?></td>
                                        <td>
                                            <button type="button" class="btn btn-primary">
                                                <a href="download_documents.php?id=<?php echo $applicant['id']; ?>">Download
                                                    All</a>
                                            </button>
                                        </td>

                                        <td>
                                            <select class="status-dropdown form-control"
                                                data-applicant-id="<?php echo $applicant['id']; ?>">
                                                <option value="Shortlisted"
                                                    <?php echo ($applicant['status'] === 'Shortlisted') ? 'selected' : ''; ?>>
                                                    Shortlisted</option>
                                                <option value="Interview"
                                                    <?php echo ($applicant['status'] === 'Interview') ? 'selected' : ''; ?>>
                                                    Interview</option>
                                                <option value="Endorsed"
                                                    <?php echo ($applicant['status'] === 'Endorsed') ? 'selected' : ''; ?>>
                                                    Endorsed</option>
                                            </select>
                                        </td>
                                        <td>
                                            <button type="button" class="btn btn-danger delete-btn"
                                                data-applicant-id="<?php echo $applicant['id']; ?>" data-toggle="modal"
                                                data-target="#deleteModal">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                    <?php else : ?>
                                    <tr>
                                        <td colspan="18">No applicants found.</td>
                                    </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination and rows per page controls -->
                        <nav aria-label="Page navigation " class="mb-3">
                            <ul class="pagination justify-content-center mt-3">
                                <li class="page-item <?php if ($page <= 1) echo 'disabled'; ?>">
                                    <a class="page-link"
                                        href="?applicants_page=<?php echo $page - 1; ?>&rows_per_page=<?php echo $rows_per_page; ?>"
                                        aria-label="Previous">
                                        <span aria-hidden="true">&laquo;</span>
                                        <span class="sr-only">Previous</span>
                                    </a>
                                </li>

                                <?php
        $start = max(1, $page - 1);
        $end = min($total_pages, $page + 1);

        if ($start > 1) {
            echo '<li class="page-item"><a class="page-link" href="?applicants_page=1&rows_per_page=' . $rows_per_page . '">1</a></li>';
            if ($start > 2) {
                echo '<li class="page-item"><span class="page-link">...</span></li>';
            }
        }

        for ($i = $start; $i <= $end; $i++) : ?>
                                <li class="page-item <?php if ($page == $i) echo 'active'; ?>">
                                    <a class="page-link"
                                        href="?applicants_page=<?php echo $i; ?>&rows_per_page=<?php echo $rows_per_page; ?>"><?php echo $i; ?></a>
                                </li>
                                <?php endfor;

        if ($end < $total_pages) {
            if ($end < $total_pages - 1) {
                echo '<li class="page-item"><span class="page-link">...</span></li>';
            }
            echo '<li class="page-item"><a class="page-link" href="?applicants_page=' . $total_pages . '&rows_per_page=' . $rows_per_page . '">' . $total_pages . '</a></li>';
        }
        ?>

                                <li class="page-item <?php if ($page >= $total_pages) echo 'disabled'; ?>">
                                    <a class="page-link"
                                        href="?applicants_page=<?php echo $page + 1; ?>&rows_per_page=<?php echo $rows_per_page; ?>"
                                        aria-label="Next">
                                        <span aria-hidden="true">&raquo;</span>
                                        <span class="sr-only">Next</span>
                                    </a>
                                </li>
                            </ul>
                        </nav>

                        <script>
                        function changeRowsPerPage() {
                            var rowsPerPage = document.getElementById('rows_per_page').value;
                            var url = new URL(window.location.href);
                            url.searchParams.set('rows_per_page', rowsPerPage);
                            window.location.href = url.toString();
                        }
                        </script>
                    </div>
                </div>

               
            </div>

        </div>
</body>
<script src="assets/js/date.js"></script>
<script src="assets/js/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    // Event handler for status dropdown change
    $('.status-dropdown').change(function() {
        var status = $(this).val();
        var applicantId = $(this).data('applicant-id');

        $.ajax({
            url: 'PHP_Connections/update_status.php',
            type: 'POST',
            data: {
                id: applicantId,
                status: status
            },
            success: function(response) {
                console.log('Status updated successfully:', response);
            },
            error: function(xhr, status, error) {
                console.error('Failed to update status:', error);
                console.log('Response:', xhr.responseText);
            }
        });
    });
});
</script>
<script src="assets/js/popper.min.js"></script>
<script src="assets/js/bootstrap.min.js"></script>
<script src="assets/js/feather.min.js"></script>
<script src="assets/plugins/slimscroll/jquery.slimscroll.min.js"></script>
<script src="assets/js/script.js"></script>

</html>