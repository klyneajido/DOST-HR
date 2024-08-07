<?php include("PHP_Connections/fetch_archives_announcements.php")?>
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
    <?php include("modal_logout.php"); ?>
    <?php include("modals_archive.php"); ?>
    <div class="main-wrapper">
        <?php include("navbar.php") ?>
        <div class="page-wrapper">
            <div class="container-fluid">
                <div class="breadcrumb-path mb-4 my-4">
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="view_archives_announcements.php"><img src="assets/img/dash.png" class="mr-2"
                                    alt="breadcrumb" />Archive</a>
                        </li>
                        <li class="breadcrumb-item active">Announcements</li>
                    </ul>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">Archived Announcements</h4>
                                <div class="search-container d-inline float-right" style="margin-left: 20px;">
                                    <input type="text" id="searchInput" class="form-control mr-2"
                                        placeholder="Search Announcements"
                                        style="flex: 1; min-width: 400px; border-radius: 30px;"
                                        value="<?php echo htmlspecialchars($search_announcement); ?>">
                                </div>
                            </div>
                            <div>
                                <div class="table-responsive">
                                    <table id="announcementsTable" class="table table-striped">
                                        <thead class="text-center">
                                            <tr>
                                                <th>Title</th>
                                                <th>Description</th>
                                                <th>Link</th>
                                                <th>Image</th>
                                                <th>Created At</th>
                                                <th>Updated At</th>
                                                <th>Archived By</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody class="text-center">
                                            <?php if ($result_announcement_archive->num_rows > 0): ?>
                                            <?php while ($archive = $result_announcement_archive->fetch_assoc()): ?>
                                            <?php
                        // Encode the image_announcement BLOB data to base64
                        $image_data = base64_encode($archive['image_announcement']);
                    ?>
                                            <tr>
                                                <td><?php echo htmlspecialchars($archive['title']); ?></td>
                                                <td><?php echo htmlspecialchars($archive['description_announcement']); ?>
                                                </td>
                                                <td>
                                                    <a href="<?php echo htmlspecialchars($archive['link']); ?>"
                                                        target="_blank">
                                                        <?php echo htmlspecialchars($archive['link']); ?>
                                                    </a>
                                                </td>
                                                <td>
                                                    <img class="rounded"src="data:image/jpeg;base64,<?php echo $image_data; ?>"
                                                        alt="Image" width="50" height="50">
                                                </td>
                                                <td><?php echo formatDate($archive['created_at']); ?></td>
                                                <td><?php echo formatDate($archive['updated_at']); ?></td>
                                                <td><?php echo htmlspecialchars($archive['archived_by']); ?></td>
                                                <td>
                                                    <a href="#"
                                                        class="btn btn-success btn-sm restore-announcement-button"
                                                        data-id="<?php echo htmlspecialchars($archive['announcement_id']); ?>">
                                                        <i class="fas fa-undo"></i>
                                                    </a>
                                                    <a href="#"
                                                        class="btn btn-danger btn-sm delete-announcement-button"
                                                        data-id="<?php echo htmlspecialchars($archive['announcement_id'], ENT_QUOTES, 'UTF-8'); ?>">
                                                        <i class="fas fa-trash"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                            <?php endwhile; ?>
                                            <?php else: ?>
                                            <tr>
                                                <td colspan="8">No archived announcements found.</td>
                                            </tr>
                                            <?php endif; ?>
                                        </tbody>
                                    </table>
                                </div>

                                <nav aria-label="Page navigation">
                                    <ul class="pagination justify-content-center my-3">
                                        <li class="page-item <?php if ($announcements_page <= 1) {
                                            echo 'disabled';
                                        } ?>">
                                            <a class="page-link"
                                                href="?announcements_page=<?php echo $announcements_page - 1; ?>"
                                                aria-label="Previous">
                                                <span aria-hidden="true">&laquo;</span>
                                                <span class="sr-only">Previous</span>
                                            </a>
                                        </li>

                                        <?php
                                        $announcements_start = max(1, $announcements_page - 1);
$announcements_end = min($total_pages_announcements, $announcements_page + 1);

if ($announcements_start > 1) {
    echo '<li class="page-item"><a class="page-link" href="?announcements_page=1">1</a></li>';
    if ($announcements_start > 2) {
        echo '<li class="page-item"><span class="page-link">...</span></li>';
    }
}

for ($i = $announcements_start; $i <= $announcements_end; $i++) : ?>
                                        <li class="page-item <?php if ($announcements_page == $i) {
                                            echo 'active';
                                        } ?>"><a class="page-link"
                                                href="?announcements_page=<?php echo $i; ?>"><?php echo $i; ?></a></li>
                                        <?php endfor;

if ($announcements_end < $total_pages_announcements) {
    if ($announcements_end < $total_pages_announcements - 1) {
        echo '<li class="page-item"><span class="page-link">...</span></li>';
    }
    echo '<li class="page-item"><a class="page-link" href="?announcements_page=' . $total_pages_announcements . '">' . $total_pages_announcements . '</a></li>';
}
?>

                                        <li class="page-item <?php if ($announcements_page >= $total_pages_announcements) {
                                                echo 'disabled';
                                            } ?>">
                                            <a class="page-link"
                                                href="?announcements_page=<?php echo $announcements_page + 1; ?>"
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
        <script src="assets/js/archives_announcements.js"></script>

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
