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
    <link rel="stylesheet" href="assets/css/style.css">
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
                <div class="col-xl-12 col-sm-12 col-12">
                    <div class="card">
                        <div class="card-header">
                            <h2 class="card-titles">Applicants</h2>
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
                                                <a href="download_documents.php?id=<?php echo $applicant['id']; ?>"
                                                    style="color: white; text-decoration: none;">Download All</a>
                                            </button>
                                        </td>

                                        <td><?php echo htmlspecialchars($applicant['status']); ?></td>
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
                    </div>
                </div>
            </div>
        </div>

</body>
<script src="assets/js/date.js"></script>
<script src="assets/js/jquery-3.6.0.min.js"></script>

<script src="assets/js/popper.min.js"></script>
<script src="assets/js/bootstrap.min.js"></script>

<script src="assets/js/feather.min.js"></script>

<script src="assets/plugins/slimscroll/jquery.slimscroll.min.js"></script>

<script src="assets/plugins/apexchart/apexcharts.min.js"></script>
<script src="assets/plugins/apexchart/chart-data.js"></script>
<script src="assets/js/script.js"></script>

</html>
<?php

