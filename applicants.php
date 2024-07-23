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
    <script src="https://kit.fontawesome.com/0dcd39d035.js" crossorigin="anonymous"></script>
    <!-- [if lt IE 9]>
			<script src="assets/js/html5shiv.min.js"></script>
			<script src="assets/js/respond.min.js"></script>
		<![endif] -->
</head>

<body class="scrollbar" id="style-5">
    <?php include("logout_modal.php")?>
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
                    <div class="card ">
                        <div class="header_1 card-header  d-flex justify-content-between">
                            <h2 class="card-titles col-lg-10">Applicants</h2>
                            <!-- EXPORT BUTTON -->
                            <div class="export_btn col-lg-2 d-flex justify-content-end">
                                <button class="export btn btn-primary" id="export-button" type="button">
                                    <span class="button__text">Export</span>
                                    <span class="button__icon">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 35 35"
                                            id="bdd05811-e15d-428c-bb53-8661459f9307" data-name="Layer 2" class="svg">
                                            <path
                                                d="M17.5,22.131a1.249,1.249,0,0,1-1.25-1.25V2.187a1.25,1.25,0,0,1,2.5,0V20.881A1.25,1.25,0,0,1,17.5,22.131Z">
                                            </path>
                                            <path
                                                d="M17.5,22.693a3.189,3.189,0,0,1-2.262-.936L8.487,15.006a1.249,1.249,0,0,1,1.767-1.767l6.751,6.751a.7.7,0,0,0,.99,0l6.751-6.751a1.25,1.25,0,0,1,1.768,1.767l-6.752,6.751A3.191,3.191,0,0,1,17.5,22.693Z">
                                            </path>
                                            <path
                                                d="M31.436,34.063H3.564A3.318,3.318,0,0,1,.25,30.749V22.011a1.25,1.25,0,0,1,2.5,0v8.738a.815.815,0,0,0,.814.814H31.436a.815.815,0,0,0,.814-.814V22.011a1.25,1.25,0,1,1,2.5,0v8.738A3.318,3.318,0,0,1,31.436,34.063Z">
                                            </path>
                                        </svg>
                                    </span>
                                </button>
                            </div>
                            <!-- END EXPORT BUTTON -->
                        </div>

                        <div class="header_2 card-header d-flex justify-content-between  ">
                            <!-- START SEARCH -->
                            <div class="top-nav-search  ">
                                <form id="search-form" method="GET" action="applicants.php">
                                    <input type="text" id="search-input" name="search" class="form-control"
                                        placeholder="Search here"
                                        value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
                                    <input type="hidden" name="applicants_page" value="<?php echo $page; ?>">
                                    <input type="hidden" name="rows_per_page" value="<?php echo $rows_per_page; ?>">
                                    <button class="btn" type="submit"><i class="fas fa-search"></i></button>
                                </form>

                            </div>
                            <!-- END SEARCH -->

                            <!-- START FILTERS -->
                            <div class="filter d-flex row align-items-center">
                                <!-- Status Dropdown -->
                                <div class="dropdown mr-2">
                                    <button class="btn btn-secondary dropdown-toggle" type="button" id="statusDropdown"
                                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        Status
                                    </button>
                                    <div class="dropdown-menu" aria-labelledby="statusDropdown">
                                        <a class="dropdown-item" href="#" data-filter="status"
                                            data-value="Shortlisted">Shortlisted</a>
                                        <a class="dropdown-item" href="#" data-filter="status"
                                            data-value="Interview">Interview</a>
                                        <a class="dropdown-item" href="#" data-filter="status"
                                            data-value="Endorsed">Endorsed</a>
                                    </div>
                                </div>

                                <!-- Job Title Dropdown -->
                                <div class="dropdown mr-2">
                                    <button class="btn btn-secondary dropdown-toggle" type="button"
                                        id="jobTitleDropdown" data-toggle="dropdown" aria-haspopup="true"
                                        aria-expanded="false">
                                        Job Title
                                    </button>
                                    <div class="dropdown-menu" aria-labelledby="jobTitleDropdown">
                                        <?php foreach ($job_titles as $title): ?>
                                        <a class="dropdown-item" href="#" data-filter="job_title"
                                            data-value="<?php echo htmlspecialchars($title); ?>"><?php echo htmlspecialchars($title); ?></a>
                                        <?php endforeach; ?>
                                    </div>
                                </div>

                                <!-- Position Dropdown -->
                                <div class="dropdown mr-2">
                                    <button class="btn btn-secondary dropdown-toggle" type="button"
                                        id="positionDropdown" data-toggle="dropdown" aria-haspopup="true"
                                        aria-expanded="false">
                                        Position
                                    </button>
                                    <div class="dropdown-menu" aria-labelledby="positionDropdown">
                                        <?php foreach ($positions as $position): ?>
                                        <a class="dropdown-item" href="#" data-filter="position"
                                            data-value="<?php echo htmlspecialchars($position); ?>"><?php echo htmlspecialchars($position); ?></a>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                                <button id="reset-filters" class="button ">
                                    <svg class="svg-icon" fill="none" height="20" viewBox="0 0 20 20" width="20"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <g stroke="#ff342b" stroke-linecap="round" stroke-width="1.5">
                                            <path
                                                d="m3.33337 10.8333c0 3.6819 2.98477 6.6667 6.66663 6.6667 3.682 0 6.6667-2.9848 6.6667-6.6667 0-3.68188-2.9847-6.66664-6.6667-6.66664-1.29938 0-2.51191.37174-3.5371 1.01468">
                                            </path>
                                            <path
                                                d="m7.69867 1.58163-1.44987 3.28435c-.18587.42104.00478.91303.42582 1.0989l3.28438 1.44986">
                                            </path>
                                        </g>
                                    </svg>
                                </button>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table custom-table no-footer text-center">
                                <thead>
                                    <tr>
                                        <th data-column="job_title" class="sortable">Job Title <i class="fas"></i></th>
                                        <th data-column="position_or_unit" class="sortable">Position <i class="fas"></i>
                                        </th>
                                        <th data-column="lastname" class="sortable">Last Name <i class="fas"></i></th>
                                        <th data-column="firstname" class="sortable">First Name <i class="fas"></i></th>
                                        <th data-column="middlename" class="sortable">Middle Name <i class="fas"></i>
                                        </th>
                                        <th data-column="sex" class="sortable">Sex <i class="fas"></i></th>
                                        <th data-column="address" class="sortable">Address <i class="fas"></i></th>
                                        <th data-column="email" class="sortable">Email <i class="fas"></i></th>
                                        <th data-column="contact_number" class="sortable">Contact Number <i
                                                class="fas"></i></th>
                                        <th data-column="course" class="sortable">Course <i class="fas"></i></th>
                                        <th data-column="years_of_experience" class="sortable">Years of Experience <i
                                                class="fas"></i></th>
                                        <th data-column="hours_of_training" class="sortable">Hours of Training <i
                                                class="fas"></i></th>
                                        <th data-column="eligibility" class="sortable">Eligibility <i class="fas"></i>
                                        </th>
                                        <th data-column="list_of_awards" class="sortable">List of Awards <i
                                                class="fas"></i></th>
                                        <th data-column="application_date" class="sortable">Application Date <i
                                                class="fas"></i></th>
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
                                        <td><?php echo htmlspecialchars($applicant['position_or_unit']); ?></td>
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
                                        <td><?php echo formatDate($applicant['application_date']); ?></td>
                                        <td>
                                            <a
                                                href="PHP_Connections/download_documents.php?id=<?php echo $applicant['id']; ?>">Download
                                                All</a>
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
                        <nav aria-label="Page navigation" class="mb-3 col-xl-12 d-flex justify-content-between">
                            <div class="col-lg-4"></div>
                            <ul class="pagination col-lg-4 align-self-center justify-content-center mt-3">
                                <li class="page-item <?php if ($page <= 1) echo 'disabled'; ?>">
                                    <a class="page-link"
                                        href="?applicants_page=<?php echo $page - 1; ?>&rows_per_page=<?php echo $rows_per_page; ?>&search=<?php echo urlencode($search_query); ?>&job_title=<?php echo urlencode($job_title_filter); ?>&position=<?php echo urlencode($position_filter); ?>&status=<?php echo urlencode($status_filter); ?>"
                                        aria-label="Previous">
                                        <span aria-hidden="true">&laquo;</span>
                                        <span class="sr-only">Previous</span>
                                    </a>
                                </li>

                                <?php
        $start = max(1, $page - 1);
        $end = min($total_pages, $page + 1);

        if ($start > 1) {
            echo '<li class="page-item"><a class="page-link" href="?applicants_page=1&rows_per_page=' . $rows_per_page . '&search=' . urlencode($search_query) . '&job_title=' . urlencode($job_title_filter) . '&position=' . urlencode($position_filter) . '&status=' . urlencode($status_filter) . '">1</a></li>';
            if ($start > 2) {
                echo '<li class="page-item"><span class="page-link">...</span></li>';
            }
        }

        for ($i = $start; $i <= $end; $i++) : ?>
                                <li class="page-item <?php if ($page == $i) echo 'active'; ?>">
                                    <a class="page-link"
                                        href="?applicants_page=<?php echo $i; ?>&rows_per_page=<?php echo $rows_per_page; ?>&search=<?php echo urlencode($search_query); ?>&job_title=<?php echo urlencode($job_title_filter); ?>&position=<?php echo urlencode($position_filter); ?>&status=<?php echo urlencode($status_filter); ?>"><?php echo $i; ?></a>
                                </li>
                                <?php endfor;

        if ($end < $total_pages) {
            if ($end < $total_pages - 1) {
                echo '<li class="page-item"><span class="page-link">...</span></li>';
            }
            echo '<li class="page-item"><a class="page-link" href="?applicants_page=' . $total_pages . '&rows_per_page=' . $rows_per_page . '&search=' . urlencode($search_query) . '&job_title=' . urlencode($job_title_filter) . '&position=' . urlencode($position_filter) . '&status=' . urlencode($status_filter) . '">' . $total_pages . '</a></li>';
        }
        ?>

                                <li class="page-item <?php if ($page >= $total_pages) echo 'disabled'; ?>">
                                    <a class="page-link"
                                        href="?applicants_page=<?php echo $page + 1; ?>&rows_per_page=<?php echo $rows_per_page; ?>&search=<?php echo urlencode($search_query); ?>&job_title=<?php echo urlencode($job_title_filter); ?>&position=<?php echo urlencode($position_filter); ?>&status=<?php echo urlencode($status_filter); ?>"
                                        aria-label="Next">
                                        <span aria-hidden="true">&raquo;</span>
                                        <span class="sr-only">Next</span>
                                    </a>
                                </li>
                            </ul>
                            <div class="rows_page d-flex col-lg-4 justify-content-end align-items-center mt-3">
                                <div class="form-group d-flex align-items-center">
                                    <label for="rows_per_page" class="mr-2">Rows</label>
                                    <select class="form-control" id="rows_per_page" onchange="changeRowsPerPage()"
                                        style="width: auto; height: auto;">
                                        <option value="10" <?php echo $rows_per_page == 10 ? 'selected' : ''; ?>>10
                                        </option>
                                        <option value="20" <?php echo $rows_per_page == 20 ? 'selected' : ''; ?>>20
                                        </option>
                                        <option value="50" <?php echo $rows_per_page == 50 ? 'selected' : ''; ?>>50
                                        </option>
                                        <option value="100" <?php echo $rows_per_page == 100 ? 'selected' : ''; ?>>100
                                        </option>
                                    </select>
                                </div>
                            </div>
                        </nav>

                    </div>
                </div>
            </div>
        </div>
</body>

<script src="assets/js/date.js"></script>
<script src="assets/js/jquery-3.6.0.min.js"></script>
<script src="assets/js/applicant.js"></script>
<script src="assets/js/popper.min.js"></script>
<script src="assets/js/bootstrap.min.js"></script>
<script src="assets/js/feather.min.js"></script>
<script src="assets/plugins/slimscroll/jquery.slimscroll.min.js"></script>
<script src="assets/js/script.js"></script>

</html>