<?php
session_start();
include_once 'PHP_Connections/db_connection.php';

if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit();
}

$user_name = isset($_SESSION['username']) ? $_SESSION['username'] : 'Guest';
$profile_image_path = isset($_SESSION['profile_image']) ? $_SESSION['profile_image'] : 'assets/img/profiles/default-profile.png';

$query = "SELECT a.id, a.lastname, a.firstname, a.middlename, a.sex, a.address, a.email, a.contact_number, 
                 a.application_letter, a.personal_data_sheet, a.performance_rating, a.eligibility_rating_license, 
                 a.transcript_of_records, a.certificate_of_employment, a.proof_of_ratings_seminars, 
                 a.proof_of_rewards, j.position
          FROM applicants a 
          LEFT JOIN job j ON a.job_id = j.job_id";
$result = mysqli_query($mysqli, $query);

$applicants = [];
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $applicants[] = $row;
    }
}
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $applicantId = $_POST['id'];

    // Prevent SQL injection
    $applicantId = mysqli_real_escape_string($mysqli, $applicantId);

    // Perform deletion query
    $deleteQuery = "DELETE FROM applicants WHERE id = '$applicantId'";

    if (mysqli_query($mysqli, $deleteQuery)) {
        echo "Applicant deleted successfully!";
    } else {
        echo "Error deleting applicant: " . mysqli_error($mysqli);
    }
} else {
    echo "Invalid request. Please provide an applicant ID.";
}


?>
<!DOCTYPE html>
<html lang="en">
<style>
    .file-preview img {
        max-width: 100px;
        height: auto;
    }

    #style-5::-webkit-scrollbar-track {
        -webkit-box-shadow: inset 0 0 6px rgba(0, 0, 0, 0.3);
        background-color: #F5F5F5;
    }

    #style-5::-webkit-scrollbar {
        width: 10px;
        background-color: #F5F5F5;
    }

    #style-5::-webkit-scrollbar-thumb {
        background-color: #0ae;

        background-image: --webkit-gradient(linear, 0 0, 0 100%,
                color-stop(.5, rgba(255, 255, 255, .2)),
                color-stop(.5, transparent), to(transparent));
    }
</style>

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

<body>
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
                                    <tr >
                                        <th>ID</th>
                                        <th>Job Title</th>
                                        <th>Last Name</th>
                                        <th>First Name</th>
                                        <th>Middle Name</th>
                                        <th>Sex</th>
                                        <th>Address</th>
                                        <th>Email</th>
                                        <th>Contact Number</th>
                                        <th>Application Letter</th>
                                        <th>Personal Data Sheet</th>
                                        <th>Performance Rating</th>
                                        <th>Eligibility/Rating/License</th>
                                        <th>Transcript of Records</th>
                                        <th>Certificate of Employment</th>
                                        <th>Proof of Ratings/Seminars</th>
                                        <th>Proof of Rewards</th>
                                       
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($applicants)) : ?>
                                        <?php foreach ($applicants as $applicant) : ?>
                                            <tr>
                                                <td><?php echo htmlspecialchars($applicant['id']); ?></td>
                                                <td><?php echo htmlspecialchars($applicant['position']); ?></td>
                                                <td><?php echo htmlspecialchars($applicant['lastname']); ?></td>
                                                <td><?php echo htmlspecialchars($applicant['firstname']); ?></td>
                                                <td><?php echo htmlspecialchars($applicant['middlename']); ?></td>
                                                <td><?php echo htmlspecialchars($applicant['sex']); ?></td>
                                                <td><?php echo htmlspecialchars($applicant['address']); ?></td>
                                                <td><?php echo htmlspecialchars($applicant['email']); ?></td>
                                                <td><?php echo htmlspecialchars($applicant['contact_number']); ?></td>
                                                <td class="file-preview">
                                                    <?php echo getFilePreview($applicant['application_letter']); ?>
                                                </td>
                                                <td class="file-preview">
                                                    <?php echo getFilePreview($applicant['personal_data_sheet']); ?>
                                                </td>
                                                
                                                <td class="file-preview">
                                                <?php echo getFilePreview($applicant['performance_rating']); ?>
                                                </td>
                                                <td class="file-preview">
                                                    <?php echo getFilePreview($applicant['eligibility_rating_license']); ?>
                                                </td>
                                                <td class="file-preview">
                                                    <?php echo getFilePreview($applicant['transcript_of_records']); ?>
                                                </td>
                                                <td class="file-preview">
                                                    <?php echo getFilePreview($applicant['certificate_of_employment']); ?>
                                                </td>
                                                <td class="file-preview">
                                                    <?php echo getFilePreview($applicant['proof_of_ratings_seminars']); ?>
                                                </td>
                                                <td class="file-preview">
                                                    <?php echo getFilePreview($applicant['proof_of_rewards']); ?>
                                                </td>
                                                
                                                <td>
                                                    <button type="button" class="btn btn-danger delete-btn" data-applicant-id="<?php echo $applicant['id']; ?>" data-toggle="modal" data-target="#deleteModal">
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

    
<script>
    // JavaScript for deleting applicant
    $(document).ready(function() {
        $('.delete-btn').click(function() {
            var applicantId = $(this).data('applicant-id');
            $('#confirmDelete').data('applicant-id', applicantId); // Set data attribute to modal button
        });

        $('#confirmDelete').click(function() {
            var applicantId = $(this).data('applicant-id');
            // Perform AJAX request to delete applicant
            $.ajax({
                url: 'PHP_Connections/delete_applicant.php',
                method: 'POST',
                data: { id: applicantId },
                success: function(response) {
                    // Handle success, maybe refresh table or show message
                    alert('Applicant deleted successfully!');
                    // Example: Reload the page after deletion
                    location.reload();
                },
                error: function(xhr, status, error) {
                    // Handle error
                    console.error(xhr.responseText);
                }
            });
        });
    });
</script>

</html>
<?php
function getFilePreview($filePath)
{
    $filePath = '../DOSTHR-PUBLIC/' . htmlspecialchars($filePath);
    $fileExtension = pathinfo($filePath, PATHINFO_EXTENSION);
    $imageExtensions = ['jpg', 'jpeg', 'png', 'gif'];

    if (in_array($fileExtension, $imageExtensions)) {
        return '<img src="' . $filePath . '" alt="Image">';
    } else {
        return '<a href="' . $filePath . '" target="_blank">View</a>';
    }
}
?>