<?php
// Start session
session_start();
include_once 'PHP_Connections\db_connection.php';

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    // Redirect to login page if not logged in
    header('Location: login.php');
    exit();
}

// Get user's name from session
$user_name = isset($_SESSION['username']) ? $_SESSION['username'] : 'Guest';
$profile_image_path = isset($_SESSION['profile_image']) ? $_SESSION['profile_image'] : 'assets/img/profiles/default-profile.png';

$success_message = isset($_GET['success']) ? htmlspecialchars($_GET['success']) : '';

// Check if search query is set
$search = isset($_GET['search']) ? $mysqli->real_escape_string($_GET['search']) : '';

// Check if sort order is set
$order = isset($_GET['order']) ? $_GET['order'] : 'desc'; // Default order descending

// Prepare SQL query
$sql = "SELECT a.announcement_id, a.title, a.description_announcement as announcement, a.link, a.image_announcement as image_shown, a.created_at, a.updated_at 
        FROM announcements a ";

if (!empty($search)) {
    $sql .= " WHERE a.title LIKE '%$search%' OR a.description_announcement LIKE '%$search%'";
}

$sql .= " ORDER BY a.created_at $order"; // Sort by created_at field and order by descending or ascending

$result = $mysqli->query($sql);

// Initialize an empty array to store announcements data
$announcements = [];

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $announcements[] = $row;
    }
} else {
    $errors['database'] = "No announcements found.";
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
    <link rel="stylesheet" href="assets/css/announcement.css">
    <!-- [if lt IE 9]>
			<script src="assets/js/html5shiv.min.js"></script>
			<script src="assets/js/respond.min.js"></script>
		<![endif] -->
</head>

<body class="scrollbar" id="style-5">
    <?php include("logout_modal.php")?>
    <div class="main-wrapper">

        <?php include("navbar.php")?>

        <div class="page-wrapper">
            <div class="container-fluid">
                <div class="breadcrumb-path mb-4 my-4">
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href=""><img src="assets/img/dash.png" class="mr-2" alt="breadcrumb" />Announcements</a>
                        </li>
                        <li class="breadcrumb-item active">Posts</li>
                    </ul>
                    <div class="sort d-flex">
                        <button type="button" id="sortAsc" data-toggle="tooltip" data-placement="top"
                            title="Oldest First">
                            <i class="fas fa-arrow-up"></i>
                        </button>
                        <button type="button" id="sortDesc" data-toggle="tooltip" data-placement="top"
                            title="Newest First">
                            <i class="fas fa-arrow-down"></i>
                        </button>
                    </div>
                    <script>
                    document.getElementById('sortAsc').addEventListener('click', function() {
                        window.location.href = 'announcements.php?order=asc';
                    });

                    document.getElementById('sortDesc').addEventListener('click', function() {
                        window.location.href = 'announcements.php?order=desc';
                    });
                    </script>

                </div>

                <?php if (!empty($errors)) : ?>
                <div class="alert alert-danger text-center">
                    <?php foreach ($errors as $error) : ?>
                    <p><?php echo htmlspecialchars($error); ?></p>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>

                <!-- Display announcements -->
                <div class="row">
                    <?php foreach ($announcements as $announcement) : ?>
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-body shadow p-3">
                                <h5 class="card-header"><?php echo htmlspecialchars($announcement['title']); ?></h5>
                                <div class="row mx-3 my-2">

                                    <div class="col-md-8">

                                        <p class="card-text"><strong>Description:</strong>
                                            <?php echo htmlspecialchars($announcement['announcement']); ?></p>
                                        <p class="card-text"><strong>Link:</strong>
                                            <?php echo htmlspecialchars($announcement['link']); ?></p>
                                        <p class="card-text"><strong>Created:</strong>
                                            <?php echo htmlspecialchars($announcement['created_at']); ?></p>
                                        <p class="card-text"><strong>Updated:</strong>
                                            <?php echo htmlspecialchars($announcement['updated_at']); ?></p>
                                        <a href="editAnnouncement.php?announcement_id=<?php echo $announcement['announcement_id']; ?>"
                                            class="btn btn-primary py-3 px-3 w-25">Edit</a>
                                        <a href="PHP_Connections/announcementArchive.php?announcement_id=<?php echo $announcement['announcement_id']; ?>"
                                            class="btn btn-danger py-3 w-25 archive-button">Archive</a>
                                    </div>
                                    <div class="col-md-4 text-right">
                                        <br>
                                        <img src="data:image/jpeg;base64,<?php echo base64_encode($announcement['image_shown']); ?>"
                                            alt="Announcement Image" class="img-fluid">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>

                <!-- Add announcement button -->
                <div class="user-menu">
                    <a href="addAnnouncement.php" class="btn btn-info btn-lg float-add-btn" title="Add Announcement">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="white"
                            class="bi bi-plus-circle-fill mb-1" viewBox="0 0 16 16">
                            <path
                                d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0M8.5 4.5a.5.5 0 0 0-1 0v3h-3a.5.5 0 0 0 0 1h3v3a.5.5 0 0 0 1 0v-3h3a.5.5 0 0 0 0-1h-3z" />
                        </svg>
                        Add Announcement
                    </a>
                </div>

                <div class="mobile-user-menu show">
                    <a href="addAnnouncement.php" class="btn btn-info btn-lg float-add-btn px-3 py-2"
                        title="Add Announcement">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="white"
                            class="bi bi-plus-circle-fill mb-1" viewBox="0 0 16 16">
                            <path
                                d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0M8.5 4.5a.5.5 0 0 0-1 0v3h-3a.5.5 0 0 0 0 1h3v3a.5.5 0 0 0 1 0v-3h3a.5.5 0 0 0 0-1h-3z" />
                        </svg>

                    </a>
                </div>

            </div>
        </div>
        <script>
        document.addEventListener('DOMContentLoaded', function() {
            const archiveButtons = document.querySelectorAll('.archive-button');
            archiveButtons.forEach(button => {
                button.addEventListener('click', function(event) {
                    const confirmed = confirm(
                        'Are you sure you want to archive this announcement?');
                    if (!confirmed) {
                        event.preventDefault();
                    }
                });
            });
        });
        </script>
        <!-- Pop-up notification -->
        <?php if (!empty($success_message)): ?>
        <script>
        alert('<?php echo addslashes($success_message); ?>');
        </script>
        <?php endif; ?>

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