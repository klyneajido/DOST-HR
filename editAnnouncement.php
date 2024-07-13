<?php
session_start();
include_once 'PHP_Connections/db_connection.php';

if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit();
}

$user_name = isset($_SESSION['username']) ? $_SESSION['username'] : 'Guest';
$profile_image_path = isset($_SESSION['profile_image']) ? $_SESSION['profile_image'] : 'assets/img/profiles/default-profile.png';

$announcement_id = isset($_GET['announcement_id']) ? (int)$_GET['announcement_id'] : 0;

if ($announcement_id === 0) {
    header('Location: announcements.php');
    exit();
}

$sql = "SELECT * FROM announcements WHERE announcement_id = ?";
$stmt = $mysqli->prepare($sql);
$stmt->bind_param('i', $announcement_id);
$stmt->execute();
$result = $stmt->get_result();
$announcement = $result->fetch_assoc();

if (!$announcement) {
    header('Location: announcements.php');
    exit();
}

$errors = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $link = $_POST['link'];
    $updated_now = date('Y-m-d H:i:s');

    // Handle image upload
    if ($_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $image_data = file_get_contents($_FILES['image']['tmp_name']);
    } else {
        // Keep the existing image if no new image is uploaded
        $image_data = $announcement['image_announcement'];
    }

    if (empty($title)) {
        $errors['title'] = "Title is required";
    }
    if (empty($description)) {
        $errors['description'] = "Description is required";
    }
    if (empty($link)) {
        $errors['link'] = "Link is required";
    }

    if (empty($errors)) {
        $sql = "UPDATE announcements SET title = ?, description_announcement = ?, image_announcement = ?, link = ?, updated_at = ? WHERE announcement_id = ?";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param('sssssi', $title, $description, $image_data, $link, $updated_now, $announcement_id);

        if ($stmt->execute()) {
            header('Location: announcements.php?success=Announcement updated successfully');
            exit();
        } else {
            $errors['database'] = "Error updating announcement: " . $mysqli->error;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<style>
	#style-5::-webkit-scrollbar-track
	{
		-webkit-box-shadow: inset 0 0 6px rgba(0,0,0,0.3);
		background-color: #F5F5F5;
	}

	#style-5::-webkit-scrollbar
	{
		width: 10px;
		background-color: #F5F5F5;
	}

	#style-5::-webkit-scrollbar-thumb
	{
		background-color: #0ae;
		
		background-image: -webkit-gradient(linear, 0 0, 0 100%,
											color-stop(.5, rgba(255, 255, 255, .2)),
							color-stop(.5, transparent), to(transparent));
	}
</style>

<body class="scrollbar" id="style-5">
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0" />
    <title>Edit Job</title>
    <link rel="shortcut icon" href="assets/img/dost_logo.png" />
    <link rel="stylesheet" href="assets/css/bootstrap.min.css" />
    <link rel="stylesheet" href="assets/plugins/select2/css/select2.min.css" />
    <link rel="stylesheet" href="assets/plugins/fontawesome/css/fontawesome.min.css" />
    <link rel="stylesheet" href="assets/plugins/fontawesome/css/all.min.css" />
    <link rel="stylesheet" href="assets/css/style.css" />
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
                <form method="GET" action="viewJob.php">
                    <input type="text" class="form-control" name="search" placeholder="Search...">
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
                                        <form method="GET" action="viewJob.php">
                                            <input type="text" class="form-control" name="search" placeholder="Search...">
                                            <button class="btn" type="submit"><i class="fas fa-search"></i></button>
                                        </form>
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
                            <li>
                                <a href="addJob.php"><img src="assets/img/calendar.svg" alt="sidebar_img">
                                    <span>Add Jobs</span></a>
                            </li>
                            <li  class="active">
                              <a href="announcements.php"><img src="assets/img/manage.svg" alt="sidebar_img">
                                <span>Announcements</span></a>
                            </li>
                            <li>
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
          <div class="row">
              <div class="col-md-9 mx-auto my-5">
                  <div class="card">
                      <div class="card-header">
                          <h4 class="card-title">Edit Announcement</h4>
                      </div>
                      <div class="card-body">
                      <?php if (!empty($errors)) : ?>
                            <div class="alert alert-danger">
                                <ul>
                                    <?php foreach ($errors as $error) : ?>
                                        <li><?php echo htmlspecialchars($error); ?></li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        <?php endif; ?>
                        <form method="POST" enctype="multipart/form-data">
                            <div class="form-group">
                                <label for="title">Title</label>
                                <input type="text" name="title" id="title" class="form-control" value="<?php echo htmlspecialchars($announcement['title']); ?>">
                            </div>
                            <div class="form-group">
                                <label for="description">Description</label>
                                <textarea name="description" id="description" class="form-control"><?php echo htmlspecialchars($announcement['description_announcement']); ?></textarea>
                            </div>
                            <div class="form-group">
                                <label for="link">Link</label>
                                <input type="text" name="link" id="link" class="form-control" value="<?php echo htmlspecialchars($announcement['link']); ?>">
                            </div>
                            <div class="form-group">
                                <label for="current_image">Current Image</label><br>
                                <?php if (!empty($announcement['image_announcement'])) : ?>
                                    <img src="data:image/jpeg;base64,<?php echo base64_encode($announcement['image_announcement']); ?>" alt="Current Image" style="max-width: 200px; max-height: 200px;">
                                <?php else : ?>
                                    <span>No image uploaded</span>
                                <?php endif; ?>
                            </div>
                            <div class="form-group">
                                <label for="image">New Image</label>
                                <input type="file" name="image" id="image" class="form-control-file">
                            </div>
                            <button type="submit" class="btn btn-primary py-3 w-25">Update Announcement</button>
                            <a href="announcements.php" class="btn btn-secondary py-3 w-25">Cancel</a>
                        </form>
                      </div>
                  </div>
              </div>
          </div>
        </div>

    </div>

    <script src="assets/js/jquery-3.6.0.min.js"></script>
    <script src="assets/js/popper.min.js"></script>
    <script src="assets/js/bootstrap.min.js"></script>
    <script src="assets/js/feather.min.js"></script>
    <script src="assets/plugins/slimscroll/jquery.slimscroll.min.js"></script>
    <script src="assets/plugins/select2/js/select2.min.js"></script>
    <script src="assets/js/script.js"></script>
</body>

</html>