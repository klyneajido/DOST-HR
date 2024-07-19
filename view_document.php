<?php
// Start session
session_start();
include_once 'PHP_Connections/db_connection.php';

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    // Redirect to login page if not logged in
    header('Location: login.php');
    exit();
}

// Get user's name from session
$user_name = isset($_SESSION['username']) ? $_SESSION['username'] : 'Guest';
$profile_image_path = isset($_SESSION['profile_image']) ? $_SESSION['profile_image'] : 'assets/img/profiles/default-profile.png';

// Validate input
if (!isset($_GET['id'])) {
    // Redirect or handle error
    header('Location: index.php'); // Redirect to dashboard or appropriate page
    exit();
}

// Sanitize the input
$documentId = $mysqli->real_escape_string($_GET['id']);

// Fetch document contents from the database
$sql = "SELECT * FROM documents WHERE doc_id = '$documentId'";
$result = $mysqli->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $documentName = $row['name']; // Assuming 'name' field contains the document name
    $documentContent = $row['content']; // Assuming 'content' field contains the document content

    // Embed the PDF document using data URL within an iframe
    $base64Content = base64_encode($documentContent);
    $iframeSrc = 'data:application/pdf;base64,' . $base64Content;
} else {
    // Handle document not found error
    echo 'Document not found.';
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
    <title>View Document</title>
    <link rel="shortcut icon" href="assets/img/dost_logo.png">

    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/plugins/fontawesome/css/fontawesome.min.css">
    <link rel="stylesheet" href="assets/plugins/fontawesome/css/all.min.css">
    <link rel="stylesheet" href="assets/css/style.css">

    <style>

    </style>
</head>
<body class="scrollbar" id="style-5">
    <div class="main-wrapper">
    <?php include("navbar.php")?>
        <div class="page-wrapper">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">Viewing <?php echo htmlspecialchars($documentName); ?></h4>
                            </div>
                            <div class="card-body">
                                <div class="iframe-container">
                                    <iframe src="<?php echo $iframeSrc; ?>" frameborder="0"></iframe>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="logoutModal" tabindex="-1" aria-labelledby="logoutModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="logoutModalLabel">Confirm Logout</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label>
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

    <script src="assets/js/jquery-3.6.0.min.js"></script>
    <script src="assets/js/popper.min.js"></script>
    <script src="assets/js/bootstrap.min.js"></script>
    <script src="assets/js/feather.min.js"></script>
    <script src="assets/plugins/slimscroll/jquery.slimscroll.min.js"></script>
    <script src="assets/plugins/apexchart/apexcharts.min.js"></script>
    <script src="assets/plugins/apexchart/chart-data.js"></script>
    <script src="assets/js/script.js"></script>

    <script>
        document.getElementById('sidebarLogoutLink').addEventListener('click', function(event) {
            event.preventDefault();
            $('#logoutModal').modal('show');
        });

        document.getElementById('confirmLogout').addEventListener('click', function() {
            window.location.href = 'PHP_Connections/logout.php';
        });
    </script>

</body>

</html>

