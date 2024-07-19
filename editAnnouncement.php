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

    // Check if a new image is uploaded
    if (!empty($_FILES['image']['tmp_name'])) {
        $image_data = file_get_contents($_FILES['image']['tmp_name']);
    } else {
        $image_data = $announcement['image_announcement']; // Use the existing image if no new image is uploaded
    }

    // Updated_at using MySQL NOW() function
    $sql_update = "UPDATE announcements SET title = ?, description_announcement = ?, image_announcement = ?, link = ?, updated_at = NOW() WHERE announcement_id = ?";
    $stmt_update = $mysqli->prepare($sql_update);
    $stmt_update->bind_param('ssssi', $title, $description, $image_data, $link, $announcement_id);

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
        if ($stmt_update->execute()) {
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
    <?php include("navbar.php")?>

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
                              <textarea name="description" id="description" class="form-control" rows="5"><?php echo htmlspecialchars($announcement['description_announcement']); ?></textarea>
                              <small class="text-muted"><span id="description-count">0</span> / 300 characters</small>
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
    <script>
    // Ensure DOM is fully loaded before executing JavaScript
    document.addEventListener('DOMContentLoaded', function() {
        // Select the textarea element
        const descriptionTextarea = document.getElementById('description');
        // Select the span element for character count
        const descriptionCount = document.getElementById('description-count');

        // Update character count on input event
        descriptionTextarea.addEventListener('input', function() {
            const currentLength = descriptionTextarea.value.length;
            descriptionCount.textContent = currentLength;

            // Optionally limit the textarea length to 300 characters
            if (currentLength > 300) {
                descriptionTextarea.value = descriptionTextarea.value.substring(0, 300);
                descriptionCount.textContent = 300;
            }
        });

        // Initialize character count on page load
        descriptionCount.textContent = descriptionTextarea.value.length;
    });
    </script>                              
    <script src="assets/js/jquery-3.6.0.min.js"></script>
    <script src="assets/js/popper.min.js"></script>
    <script src="assets/js/bootstrap.min.js"></script>
    <script src="assets/js/feather.min.js"></script>
    <script src="assets/plugins/slimscroll/jquery.slimscroll.min.js"></script>
    <script src="assets/plugins/select2/js/select2.min.js"></script>
    <script src="assets/js/script.js"></script>
</body>

</html>

