<?php include("PHP_Connections/fetch_announcements.php")?>
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
                        <button class="mr-2" type="button" id="sortAsc" data-toggle="tooltip" data-placement="top"
                            title="Oldest First">
                            <i class="fas fa-arrow-up"></i>
                        </button>
                        <button type="button" id="sortDesc" data-toggle="tooltip" data-placement="top"
                            title="Newest First">
                            <i class="fas fa-arrow-down"></i>
                        </button>
                    </div>
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
                        <div class="card shadow-sm">
                            <div class="card-header ">
                                <h5 class="mb-0"><?php echo htmlspecialchars($announcement['title']); ?></h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-8">
                                        <p class="card-text"><strong>Description:</strong>
                                            <?php echo htmlspecialchars($announcement['announcement']); ?></p>
                                        <p class="card-text"><strong>Link:</strong>
                                            <?php echo htmlspecialchars($announcement['link']); ?></p>
                                        <p class="card-text"><strong>Created:</strong>
                                            <?php echo htmlspecialchars($announcement['created_at']); ?></p>
                                        <p class="card-text"><strong>Updated:</strong>
                                            <?php echo htmlspecialchars($announcement['updated_at']); ?></p>
                                    </div>
                                    <div class="col-md-4 text-end rounded">
                                        <img src="data:image/jpeg;base64,<?php echo base64_encode($announcement['image_shown']); ?>"
                                            alt="Announcement Image" class="img-fluid rounded ">
                                    </div>
                                </div>
                                <div class="d-flex">
                                    <a href="editAnnouncement.php?announcement_id=<?php echo $announcement['announcement_id']; ?>"
                                        class="btn btn-info me-2 mr-2">Edit</a>
                                    <a href="PHP_Connections/announcementArchive.php?announcement_id=<?php echo $announcement['announcement_id']; ?>"
                                        class="btn btn-danger archive-button">Archive</a>
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
    <script src="assets/js/announcements.js"></script>
</body>

</html>