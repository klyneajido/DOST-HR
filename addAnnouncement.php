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

$errors = [];
// Check if form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Get form data
  $title = $_POST['title'];
  $description = $_POST['description'];
  $link = $_POST['link'];
  $image = $_FILES['image']['name'];
  $image_temp = $_FILES['image']['tmp_name'];

  // Validate form data
  if (empty($title)) {
      $errors['title'] = "Title is required";
  }
  if (empty($description)) {
      $errors['description'] = "Description is required";
  }
  if (empty($link)) {
      $errors['link'] = "Link is required";
  }
  if (empty($image)) {
      $errors['image'] = "Image is required";
  }

  // If no errors, proceed with data insertion
  if (empty($errors)) {
      // Prepare SQL statement
      $sql = "INSERT INTO announcements (title, description_announcement, link, image_announcement, created_at) VALUES (?, ?, ?, ?, NOW())";
      $stmt = $mysqli->prepare($sql);

      if ($stmt) {
          // Bind parameters and execute
          $stmt->bind_param("ssss", $title, $description, $link, $image);
          
          // Move uploaded file to desired location
          $upload_directory = "uploads/"; // Directory where images will be stored
          move_uploaded_file($image_temp, $upload_directory . $image);

          // Execute SQL statement
          if ($stmt->execute()) {
              $success = "Announcement added successfully!";
              // Redirect or display success message
              header('Location: announcements.php');
              exit();
          } else {
              echo "Error executing statement: " . $stmt->error;
          }
      } else {
          echo "Error preparing statement: " . $mysqli->error;
      }

      // Close statement
      $stmt->close();
  }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0" />
    <title>DOST-HRMO</title>

    <link rel="shortcut icon" href="assets/img/dost_logo.png">
    <link rel="stylesheet" href="assets/css/bootstrap.min.css" />
    <link rel="stylesheet" href="assets/plugins/select2/css/select2.min.css" />
    <link rel="stylesheet" href="assets/plugins/fontawesome/css/fontawesome.min.css" />
    <link rel="stylesheet" href="assets/plugins/fontawesome/css/all.min.css" />
    <link rel="stylesheet" href="assets/css/style.css" />
    <style>
        #style-5::-webkit-scrollbar-track {
            -webkit-box-shadow: inset 0 0 6px rgba(0,0,0,0.3);
            background-color: #F5F5F5;
        }

        #style-5::-webkit-scrollbar {
            width: 10px;
            background-color: #F5F5F5;
        }

        #style-5::-webkit-scrollbar-thumb {
            background-color: #0ae;
            background-image: -webkit-gradient(linear, 0 0, 0 100%, color-stop(.5, rgba(255, 255, 255, .2)), color-stop(.5, transparent), to(transparent));
        }
    </style>
</head>
<body class="scrollbar" id="style-5">
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
                        <h4 class="card-title">Add New Announcement</h4>
                    </div>
                    <div class="card-body">
                        <?php if (!empty($success)) : ?>
                            <div class="alert alert-success">
                                <?php echo htmlspecialchars($success); ?>
                            </div>
                        <?php endif; ?>

                        <?php if (!empty($errors)) : ?>
                            <div class="alert alert-danger">
                                <ul>
                                    <?php foreach ($errors as $error) : ?>
                                        <li><?php echo htmlspecialchars($error); ?></li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        <?php endif; ?>

                        <form method="POST" action="addAnnouncement.php" enctype="multipart/form-data" onsubmit="return confirm('Are you sure you want to add this announcement?');">
                          <div class="form-group">
                              <label for="title"><p>Title <b class="text-danger">*</b></p></label>
                              <input type="text" name="title" id="title" class="form-control" value="<?php echo isset($_POST['title']) ? htmlspecialchars($_POST['title']) : ''; ?>">
                              <?php if (isset($errors['title'])) : ?>
                                  <small class="text-danger"><?php echo $errors['title']; ?></small>
                              <?php endif; ?>
                          </div>
                          <div class="form-group">
                              <label for="description"><p>Description <b class="text-danger">*</b></p></label>
                              <textarea name="description" id="description" class="form-control" rows="5"><?php echo isset($_POST['description']) ? htmlspecialchars($_POST['description']) : ''; ?></textarea>
                              <?php if (isset($errors['description'])) : ?>
                                  <small class="text-danger"><?php echo $errors['description']; ?></small>
                              <?php endif; ?>
                          </div>
                          <div class="form-group">
                              <label for="link"><p>Link <b class="text-danger">*</b></p></label>
                              <input type="text" name="link" id="link" class="form-control" value="<?php echo isset($_POST['link']) ? htmlspecialchars($_POST['link']) : ''; ?>">
                              <?php if (isset($errors['link'])) : ?>
                                  <small class="text-danger"><?php echo $errors['link']; ?></small>
                              <?php endif; ?>
                          </div>
                          <div class="form-group">
                              <label for="image"><p>Image <b class="text-danger">*</b></p></label>
                              <input type="file" name="image" id="image" class="form-control-file">
                              <?php if (isset($errors['image'])) : ?>
                                  <small class="text-danger"><?php echo $errors['image']; ?></small>
                              <?php endif; ?>
                          </div>
                          <button type="submit" class="btn btn-primary py-3 w-25">Add Announcement</button>
                          <a href="announcements.php" class="btn btn-danger py-3 w-25">Cancel</a>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts remain unchanged -->
    <script src="assets/js/jquery-3.6.0.min.js"></script>
    <script src="assets/js/popper.min.js"></script>
    <script src="assets/js/bootstrap.min.js"></script>
    <script src="assets/js/feather.min.js"></script>
    <script src="assets/plugins/slimscroll/jquery.slimscroll.min.js"></script>
    <script src="assets/plugins/select2/js/select2.min.js"></script>
    <script src="assets/js/script.js"></script>
</body>
</html>