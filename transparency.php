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

// Fetch uploaded documents
$sql = "SELECT * FROM documents";
$result = $mysqli->query($sql);

// Array to store document cards HTML
$documentCards = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $documentId = $row['doc_id']; // Assuming your table has an ID field
        $documentName = $row['name'];
        // Assuming documents are stored as PDFs or Word files
        $icon = 'assets/img/pdf.png'; // Change this based on file type if needed

        // Generate HTML for document card
        $documentCard = '<div class="card m-2">
                         
                            <div class="card-body d-flex justify-content-between align-items-center">
                                <h6 class="card-title mb-0">' . $documentName . '</h6>
                                                        <div class="document-buttons">
                                                            <a href="download_document.php?id=' . $documentId . '" class="btn btn-primary py-3 px-4">Download</a>
                                                            <a href="view_document.php?id=' . $documentId . '" class="btn btn-success px-4 py-3">View</a>
                                                        </div>
                            </div>
                        </div>';

        // Append card HTML to array
        $documentCards[] = $documentCard;
    }
}
$uploadStatus = isset($_GET['upload_status']) ? $_GET['upload_status'] : '';
if ($uploadStatus === 'failed') {
    $errorMsg = isset($_GET['error']) ? urldecode($_GET['error']) : 'Unknown error occurred.';
    echo '<div class="alert alert-danger" role="alert">' . htmlspecialchars($errorMsg) . '</div>';
}
?>
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

<body>
    <?php include("logout_modal.php") ?>
    <div class="main-wrapper">
        <?php include("navbar.php") ?>
        <div class="page-wrapper">
            <?php
            // Place the error message display here
            if ($uploadStatus === 'failed') {
                echo '<div class="alert alert-danger mt-3 mb-3" role="alert">' . htmlspecialchars($errorMsg) . '</div>';
            }
            ?>
            <div class="container-fluid">
                <div class="breadcrumb-path mb-4 my-4">
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href=""><img src="assets/img/dash.png" class="mr-2" alt="breadcrumb" />Legal</a>
                        </li>
                        <li class="breadcrumb-item active">Documents</li>
                    </ul>
                    <form action="uploadDocument.php" method="post" accept-charset="utf-8" enctype="multipart/form-data">
                        <div class="form-group d-flex flex-column">
                            <div class="custom-file mb-3 flex-grow-1">
                                <input type="file" class="custom-file-input" id="customFile" name="document" accept="application/pdf">
                                <label class="custom-file-label" for="customFile">Choose file</label>
                            </div>
                            <button type="submit" class="btn btn-primary flex-grow-1">Upload</button>
                        </div>
                    </form>
                </div>
            </div>
            <h3 class="d-flex justify-content-center">Documents</h3>
            <div class="display-documents">
                <div class="container-fluid">
                    <div class="row">
                        <?php

                        foreach ($documentCards as $card) {

                            echo '<div class="col-md-12">'

                                . $card . '</div>';
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>

        <script>
            document.querySelector('.custom-file-input').addEventListener('change', function(e) {
                var fileName = document.getElementById("customFile").files[0].name;
                var nextSibling = e.target.nextElementSibling;
                nextSibling.innerText = fileName;
            });
        </script>

    </div>
    <script src="assets/js/date.js"></script>
    <script src="assets/js/jquery-3.6.0.min.js"></script>
    <script src="assets/js/popper.min.js"></script>
    <script src="assets/js/bootstrap.min.js"></script>
    <script src="assets/js/feather.min.js"></script>
    <script src="assets/plugins/slimscroll/jquery.slimscroll.min.js"></script>
    <script src="assets/plugins/apexchart/apexcharts.min.js"></script>
    <script src="assets/plugins/apexchart/chart-data.js"></script>
    <script src="assets/js/script.js"></script>

</body>

</html>