<?php include("PHP_Connections/fetch_archives.php")?>
<!-- eyyoo -->
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
    <div class="modal fade" id="passwordModalJob" tabindex="-1" role="dialog" aria-labelledby="passwordModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="passwordModalLabel">Confirm Deletion</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="deleteForm">
                        <input type="hidden" id="deleteJobId" name="id" value="">
                        <div class="form-group">
                            <label for="adminPassword">Admin Password</label>
                            <input type="password" class="form-control" id="adminPassword" name="password" required>
                        </div>
                        <button type="submit" class="btn btn-danger">Delete Job</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="passwordModalAnnouncement" tabindex="-1" role="dialog" aria-labelledby="passwordModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="passwordModalLabel">Confirm Deletion</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="deleteAnnouncementForm">
                        <input type="hidden" id="deleteAnnouncementId" name="id" value="">
                        <div class="form-group">
                            <label for="adminPasswordAnnouncement">Admin Password</label>
                            <input type="password" class="form-control" id="adminPasswordAnnouncement" name="password" required>
                        </div>
                        <button type="submit" class="btn btn-danger">Delete Announcement</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="successModalJob" tabindex="-1" role="dialog" aria-labelledby="successModalJobLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="successModalJobLabel">Job Deleted</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>The job has been deleted successfully.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Success Deletion Modal for Announcements -->
    <div class="modal fade" id="successModalAnnouncement" tabindex="-1" role="dialog" aria-labelledby="successModalAnnouncementLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="successModalAnnouncementLabel">Announcement Deleted</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>The announcement has been deleted successfully.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <div class="main-wrapper">
        <?php include("navbar.php") ?>
        <div class="page-wrapper">
            <div class="container-fluid">
                <div class="breadcrumb-path mb-4 my-4">
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="archive.php"><img src="assets/img/dash.png" class="mr-2" alt="breadcrumb" />Archive</a>
                        </li>
                        <li class="breadcrumb-item active">Files</li>
                    </ul>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title d-inline">Archived Jobs</h4>
                                <div class="search-container d-inline float-right" style="margin-left: 20px;">
                                    <form action="archive.php" method="get" class="d-flex flex-wrap">
                                        <input type="text" name="search" class="form-control mr-2" placeholder="Search Archived  Jobs" style="flex: 1; min-width: 400px; border-radius: 30px;">
                                        <button class="btn" type="submit" style="background: none; border: none; padding: 0;">
                                            <i class="fas fa-search" style="color: #000;"></i> <!-- Set color to desired color -->
                                        </button>
                                    </form>
                                </div>
                            </div>
                            <div class="card-body">

                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <thead class="text-center">
                                            <tr>
                                                <!-- <th>ID</th> -->
                                                <th>Title</th>
                                                <th>Position/Unit</th>
                                                <th>Description</th>
                                                <th class="w-25">Education Requirement</th>
                                                <th>Experience or Training</th>
                                                <th>Duties and Responsibilities</th>
                                                <th>Salary</th>
                                                <th>Department</th>
                                                <th>Place of Assignment</th>
                                                <th>Status</th>
                                                <!-- <th>Proof</th> -->
                                                <th>Created At</th>
                                                <th>Updated At</th>
                                                <th>Deadline</th>
                                                <th>Archived By</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
    <?php
    if ($result_archive->num_rows > 0) {
        while ($job = $result_archive->fetch_assoc()) {
            echo "<tr class='text-center'>";
            echo "<td>" . htmlspecialchars($job['job_title']) . "</td>";
            echo "<td>" . htmlspecialchars($job['position_or_unit']) . "</td>";
            echo "<td class='description-column'>" . htmlspecialchars($job['description']) . "</td>";
            echo "<td>" . htmlspecialchars($job['requirement_type']) . "</td>"; // Adjusted column name
            echo "<td>" . htmlspecialchars($job['requirement_text']) . "</td>"; // Adjusted column name
            echo "<td>₱" . htmlspecialchars($job['salary']) . "</td>";
            echo "<td>" . htmlspecialchars($job['department_name']) . "</td>"; // Adjusted column name
            echo "<td>" . htmlspecialchars($job['place_of_assignment']) . "</td>";
            echo "<td>" . htmlspecialchars($job['status']) . "</td>";
            echo "<td>" . htmlspecialchars($job['created_at']) . "</td>";
            echo "<td>" . htmlspecialchars($job['updated_at']) . "</td>";
            echo "<td>" . htmlspecialchars($job['deadline']) . "</td>";
            echo "<td>" . htmlspecialchars($job['archived_by']) . "</td>";
            echo "<td>
                    <a href='#' class='btn btn-success btn-sm restore-button' data-id='" . htmlspecialchars($job['jobarchive_id']) . "'>
                        <i class='fas fa-undo'></i>
                    </a>
                    <a href='PHP_Connections/deleteJob.php' class='btn btn-danger btn-sm delete-button' data-id='" . htmlspecialchars($job['jobarchive_id']) . "'>
                        <i class='fas fa-trash'></i>
                    </a>
                  </td>";
            echo "</tr>";
        }
    } else {
        echo "<tr class='text-center'><td colspan='12'>No archived Jobs found.</td></tr>";
    }
    ?>
</tbody>

                                    </table>
                                </div>
                                <nav aria-label="Page navigation">
                                    <ul class="pagination justify-content-center mt-3">
                                        <li class="page-item <?php if ($jobs_page <= 1) echo 'disabled'; ?>">
                                            <a class="page-link" href="?jobs_page=<?php echo $jobs_page - 1; ?>" aria-label="Previous">
                                                <span aria-hidden="true">&laquo;</span>
                                                <span class="sr-only">Previous</span>
                                            </a>
                                        </li>

                                        <?php
                                        $jobs_start = max(1, $jobs_page - 1);
                                        $jobs_end = min($total_pages_jobs, $jobs_page + 1);

                                        if ($jobs_start > 1) {
                                            echo '<li class="page-item"><a class="page-link" href="?jobs_page=1">1</a></li>';
                                            if ($jobs_start > 2) {
                                                echo '<li class="page-item"><span class="page-link">...</span></li>';
                                            }
                                        }

                                        for ($i = $jobs_start; $i <= $jobs_end; $i++) : ?>
                                            <li class="page-item <?php if ($jobs_page == $i) echo 'active'; ?>"><a class="page-link" href="?jobs_page=<?php echo $i; ?>"><?php echo $i; ?></a></li>
                                        <?php endfor;

                                        if ($jobs_end < $total_pages_jobs) {
                                            if ($jobs_end < $total_pages_jobs - 1) {
                                                echo '<li class="page-item"><span class="page-link">...</span></li>';
                                            }
                                            echo '<li class="page-item"><a class="page-link" href="?jobs_page=' . $total_pages_jobs . '">' . $total_pages_jobs . '</a></li>';
                                        }
                                        ?>

                                        <li class="page-item <?php if ($jobs_page >= $total_pages_jobs) echo 'disabled'; ?>">
                                            <a class="page-link" href="?jobs_page=<?php echo $jobs_page + 1; ?>" aria-label="Next">
                                                <span aria-hidden="true">&raquo;</span>
                                                <span class="sr-only">Next</span>
                                            </a>
                                        </li>
                                    </ul>
                                </nav>

                            </div>
                        </div>
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">Archived Announcements</h4>
                                <div class="search-container d-inline float-right" style="margin-left: 20px;">
                                    <form action="archive.php" method="get" class="d-flex flex-wrap">
                                        <input type="text" name="search_announcement" class="form-control mr-2" placeholder="Search Announcements" style="flex: 1; min-width: 400px; border-radius: 30px;" value="<?php echo htmlspecialchars($search_announcement); ?>">
                                        <button class="btn" type="submit" style="background: none; border: none; padding: 0;">
                                            <i class="fas fa-search" style="color: #000;"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <thead class="text-center">
                                            <tr>
                                                <!-- <th>ID</th> -->
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
                                            <?php
                                            if ($result_announcement_archive->num_rows > 0) {
                                                while ($archive = $result_announcement_archive->fetch_assoc()) {
                                                    // Encode the image_announcement BLOB data to base64
                                                    $image_data = base64_encode($archive['image_announcement']);
                                                    echo "<tr>";
                                                    echo "<td>" . htmlspecialchars($archive['title']) . "</td>";
                                                    echo "<td>" . htmlspecialchars($archive['description_announcement']) . "</td>";
                                                    echo "<td><a href='" . htmlspecialchars($archive['link']) . "'>" . htmlspecialchars($archive['link']) . "</a></td>";
                                                    // Use base64-encoded string as the src attribute of the img tag
                                                    echo "<td><img src='data:image/jpeg;base64," . $image_data . "' alt='Image' width='50' height='50'></td>";
                                                    echo "<td>" . htmlspecialchars($archive['created_at']) . "</td>";
                                                    echo "<td>" . htmlspecialchars($archive['updated_at']) . "</td>";
                                                    echo "<td>" . htmlspecialchars($archive['archived_by']) . "</td>";
                                                    echo "<td>
                                                    <a href='#' class='btn btn-success btn-sm restore-announcement-button' onclick='confirmRestore(" . htmlspecialchars($archive['announcement_id'], ENT_QUOTES, 'UTF-8') . "); return false;'>
                                                        <i class='fas fa-undo'></i>
                                                    </a>
                                                    <a href='PHP_Connections/deleteAnnouncement.php' class='btn btn-danger btn-sm delete-announcement-button' data-id='" . htmlspecialchars($archive['announcement_id'], ENT_QUOTES, 'UTF-8') . "'>
                                                        <i class='fas fa-trash'></i>
                                                    </a>
                                                </td>";

                                                    echo "</tr>";
                                                }
                                            } else {
                                                echo "<tr><td colspan='8'>No archived announcements found.</td></tr>";
                                            }
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                                <nav aria-label="Page navigation">
                                    <ul class="pagination justify-content-center mt-3">
                                        <li class="page-item <?php if ($announcements_page <= 1) echo 'disabled'; ?>">
                                            <a class="page-link" href="?announcements_page=<?php echo $announcements_page - 1; ?>" aria-label="Previous">
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
                                            <li class="page-item <?php if ($announcements_page == $i) echo 'active'; ?>"><a class="page-link" href="?announcements_page=<?php echo $i; ?>"><?php echo $i; ?></a></li>
                                        <?php endfor;

                                        if ($announcements_end < $total_pages_announcements) {
                                            if ($announcements_end < $total_pages_announcements - 1) {
                                                echo '<li class="page-item"><span class="page-link">...</span></li>';
                                            }
                                            echo '<li class="page-item"><a class="page-link" href="?announcements_page=' . $total_pages_announcements . '">' . $total_pages_announcements . '</a></li>';
                                        }
                                        ?>

                                        <li class="page-item <?php if ($announcements_page >= $total_pages_announcements) echo 'disabled'; ?>">
                                            <a class="page-link" href="?announcements_page=<?php echo $announcements_page + 1; ?>" aria-label="Next">
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
        <script>
            function confirmAction(action, event) {
                event.preventDefault(); // Prevent the default link behavior
                const confirmed = confirm(`Are you sure you want to ${action} this item?`);
                if (confirmed) {
                    window.location.href = event.target.href; // Redirect to the link if confirmed
                }
            }
        </script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                document.querySelectorAll('.restore-button').forEach(function(button) {
                    button.addEventListener('click', function(event) {
                        event.preventDefault();
                        var jobId = this.getAttribute('data-id');
                        if (confirm('Are you sure you want to restore this job?')) {
                            window.location.href = 'PHP_Connections/restoreJob.php?id=' + jobId;
                        }
                    });
                });

                // document.querySelectorAll('.delete-button').forEach(function(button) {
                //     button.addEventListener('click', function(event) {
                //         event.preventDefault();
                //         var jobId = this.getAttribute('data-id');
                //         if (confirm('Are you sure you want to delete this job?')) {
                //             window.location.href = 'deleteJob.php?id=' + jobId;
                //         }
                //     });
                // });
            });
        </script>
        <script>
            function confirmRestore(id) {
                if (confirm("Are you sure you want to restore this announcement?")) {
                    window.location.href = 'PHP_Connections/restoreAnnouncement.php?id=' + id;
                }
            }
        </script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                let deleteJobId = null;

                document.querySelectorAll('.delete-button').forEach(function(button) {
                    button.addEventListener('click', function(event) {
                        event.preventDefault();
                        deleteJobId = this.getAttribute('data-id');
                        $('#passwordModalJob').modal('show');
                    });
                });

                document.getElementById('deleteForm').addEventListener('submit', function(event) {
                    event.preventDefault();
                    const password = document.getElementById('adminPassword').value;

                    fetch('PHP_Connections/deleteJob.php', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json'
                            },
                            body: JSON.stringify({
                                id: deleteJobId,
                                password: password
                            })
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                $('#passwordModalJob').modal('hide');
                                $('#successModalJob').modal('show');
                                setTimeout(function() {
                                    location.reload(); // Refresh the page after showing the success message
                                }, 2000);
                            } else {
                                alert('Error: ' + data.message);
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                        });
                });

                let deleteAnnouncementId = null;

                document.querySelectorAll('.delete-announcement-button').forEach(function(button) {
                    button.addEventListener('click', function(event) {
                        event.preventDefault();
                        deleteAnnouncementId = this.getAttribute('data-id');
                        $('#passwordModalAnnouncement').modal('show');
                    });
                });

                document.getElementById('deleteAnnouncementForm').addEventListener('submit', function(event) {
                    event.preventDefault();
                    const adminPassword = document.getElementById('adminPasswordAnnouncement').value;

                    fetch('PHP_Connections/deleteAnnouncement.php', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json'
                            },
                            body: JSON.stringify({
                                id: deleteAnnouncementId,
                                password: adminPassword
                            })
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                $('#passwordModalAnnouncement').modal('hide');
                                $('#successModalAnnouncement').modal('show');
                                setTimeout(function() {
                                    location.reload(); // Refresh the page after showing the success message
                                }, 2000);
                            } else {
                                alert('Error: ' + data.message);
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                        });
                });
            });
        </script>
        <!-- <script>
            document.addEventListener('DOMContentLoaded', function() {
                let deleteAnnouncementId = null;

                document.querySelectorAll('.delete-announcement-button').forEach(function(button) {
                    button.addEventListener('click', function(event) {
                        event.preventDefault();
                        deleteAnnouncementId = this.getAttribute('data-id');
                        $('#passwordModalAnnouncement').modal('show');
                    });
                });

                document.getElementById('deleteAnnouncementForm').addEventListener('submit', function(event) {
                    event.preventDefault();
                    const adminPassword = document.getElementById('adminPasswordAnnouncement').value;

                    fetch('PHP_Connections/deleteAnnouncement.php', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json'
                            },
                            body: JSON.stringify({
                                id: deleteAnnouncementId,
                                password: adminPassword
                            })
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                alert('Announcement deleted successfully');
                                location.reload();
                            } else {
                                alert('Error: ' + data.message);
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                        })
                        .finally(() => {
                            $('#passwordModalAnnouncement').modal('hide');
                        });
                });
            });
        </script> -->
        <script src="assets/js/date.js"></script>
        <script src="assets/js/jquery-3.6.0.min.js"></script>
        <script src="assets/js/popper.min.js"></script>
        <script src="assets/js/bootstrap.min.js"></script>
        <script src="assets/js/feather.min.js"></script>
        <script src="assets/plugins/slimscroll/jquery.slimscroll.min.js"></script>
        <script src="assets/plugins/apexchart/apexcharts.min.js"></script>
        <script src="assets/plugins/apexchart/chart-data.js"></script>
        <script src="assets/js/script.js"></script>
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