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
                                                            <a href="download_document.php?id=' . $documentId . '" class="btn btn-primary px-4">Download</a>
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

        <div class="header">

            <div class="header-left">
                <div class="logo-wrapper">
                    <a href="index.php" class="logo">
                        <img src="assets/img/DOST.png" alt="Logo">
                    </a>

                </div>

                <a href="index.php" class="logo logo-small">
                    <img src="assets/img/dost_logo.png" alt="Logo" width="30" height="30">
                </a>
                <a href="javascript:void(0);" id="toggle_btn">
                    <span class="bar-icon">
                        <span></span>
                        <span></span>
                        <span></span>
                    </span>
                </a>
            </div>

            <div class="top-nav-search" style="width:46.5%; margin-left:8%; min-width:20%;">
                <form>
                    <input type="text" class="form-control" placeholder="">
                    <button class="btn" type="submit"><i class="fas fa-search"></i></button>
                </form>
            </div>


            <a class="mobile_btn" id="mobile_btn">
                <i class="fas fa-bars"></i>
            </a>
            <ul class="nav user-menu">

                <li class="nav-item dropdown has-arrow main-drop">
                    <a href="#" class="dropdown-toggle nav-link" data-toggle="dropdown">
                        <span class="user-img">
                            <img src="<?php echo htmlspecialchars($profile_image_path); ?>" alt="Avatar" style="border-radius: 50%; width: 45px; height: 45px;">
                            <span class="status online"></span>
                        </span>
                        <span><?php echo htmlspecialchars($user_name); ?></span>
                    </a>
                    <div class="dropdown-menu">
                        <a class="dropdown-item" href="profile.html"><i data-feather="user" class="mr-1"></i> Profile</a>
                        <a class="dropdown-item" href="settings.html"><i data-feather="settings" class="mr-1"></i> Settings</a>
                        <a class="dropdown-item" href="#" id="logoutLink"><i data-feather="log-out" class="mr-1"></i> Logout</a>
                    </div>


                    <script>
                        document.getElementById('logoutLink').addEventListener('click', function(event) {
                            event.preventDefault();
                            $('#logoutModal').modal('show');
                        });

                        document.getElementById('confirmLogout').addEventListener('click', function() {
                            window.location.href = 'PHP_Connections/logout.php';
                        });
                    </script>
                </li>
                </li>

            </ul>
            <div class="dropdown mobile-user-menu show">
                <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-ellipsis-v"></i></a>
                <div class="dropdown-menu dropdown-menu-right ">
                    <a class="dropdown-item" href="profile.php">My Profile</a>
                    <a class="dropdown-item" href="settings.html">Settings</a>
                    <a class="dropdown-item" href="PHP_Connections/logout.php">Logout</a>
                </div>
            </div>

        </div>


        <div class="sidebar" id="sidebar">
            <div class="sidebar-inner slimscroll">
                <div class="sidebar-contents">
                    <div id="sidebar-menu" class="sidebar-menu">
                        <div class="mobile-show">
                            <div class="offcanvas-menu">
                                <div class="user-info align-center bg-theme text-center">
                                    <span class="lnr lnr-cross  text-white" id="mobile_btn_close">X</span>
                                    <a href="javascript:void(0)" class="d-block menu-style text-white">
                                        <div class="user-avatar d-inline-block mr-3">
                                            <img src="<?php echo htmlspecialchars($profile_image_path); ?>" alt="user avatar" class="rounded-circle" width="50">
                                        </div>
                                    </a>
                                </div>
                            </div>
                            <div class="sidebar-input">
                                <div class="top-nav-search">
                                    <form>
                                        <input type="text" class="form-control" placeholder="Search here">
                                        <button class="btn" type="submit"><i class="fas fa-search"></i></button>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <ul>
                            <li>
                                <a href="index.php"><img src="assets/img/home.svg" alt="sidebar_img">
                                    <span>Dashboard</span></a>
                            </li>
                            <li>
                                <a href="applicants.php"><img src="assets/img/employee.svg" alt="sidebar_img"><span>
                                        Applicants</span></a>
                            </li>
                            <li>
                                <a href="viewJob.php"><img src="assets/img/company.svg" alt="sidebar_img"> <span>
                                        View Job</span></a>
                            </li>
                            <li class="active">
                                <a href="transparency.php"><img src="assets/img/employee.svg" alt="sidebar_img"><span>
                                        Transparency</span></a>
                            </li>

                            <li>
                                <a href="profile.php"><img src="assets/img/profile.svg" alt="sidebar_img">
                                    <span>Profile</span></a>
                            </li>
                        </ul>
                        <ul class="logout">
                            <li>
                                <a href="#" id="sidebarLogoutLink"><img src="assets/img/logout.svg" alt="sidebar_img"><span>Log out</span></a>

                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <script>
            document.getElementById('sidebarLogoutLink').addEventListener('click', function(event) {
                event.preventDefault();
                $('#logoutModal').modal('show');
            });

            document.getElementById('confirmLogout').addEventListener('click', function() {
                window.location.href = 'PHP_Connections/logout.php';
            });
        </script>
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
    <!-- sdsadasdasd -->

</body>

</html>