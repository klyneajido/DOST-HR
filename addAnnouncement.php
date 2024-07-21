<?php
session_start();
include_once 'PHP_Connections/db_connection.php';

if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit();
}

$user_name = isset($_SESSION['username']) ? $_SESSION['username'] : 'Guest';
$profile_image_path = isset($_SESSION['profile_image']) ? $_SESSION['profile_image'] : 'assets/img/profiles/default-profile.png';

$errors = [];
$max_description_length = 300; // Example maximum length

// Check if form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $title = $_POST['title'];
    $description = $_POST['description'];
    $link = $_POST['link'];
    $image_data = file_get_contents($_FILES['image']['tmp_name']);

    // Validate form data
    if (empty($title)) {
        $errors['title'] = "Title is required";
    }
    if (strlen($description) > $max_description_length) {
        $errors['description'] = "Description must not exceed {$max_description_length} characters.";
    }
    if (empty($link)) {
        $errors['link'] = "Link is required";
    }
    if (empty($image_data)) {
        $errors['image'] = "Image is required";
    }

    // If no errors, proceed with data insertion
    if (empty($errors)) {
        // Prepare SQL statement
        $sql = "INSERT INTO announcements (title, description_announcement, link, image_announcement, created_at) VALUES (?, ?, ?, ?, NOW())";
        $stmt = $mysqli->prepare($sql);

        if ($stmt) {
            // Bind parameters and execute
            $stmt->bind_param("ssss", $title, $description, $link, $image_data);

            // Execute SQL statement
            if ($stmt->execute()) {
                // Get the ID of the inserted announcement
                $announcement_id = $stmt->insert_id;

                // Record action in the history table
                $history_stmt = $mysqli->prepare("
                    INSERT INTO history (action, details, user_id, date) 
                    VALUES (?, ?, (SELECT admin_id FROM admins WHERE username = ?), NOW())
                ");
                $action = "Added Announcement";
                $details = "Announcement Title: $title";
                $history_stmt->bind_param("sss", $action, $details, $user_name);
                $history_stmt->execute();
                $history_stmt->close();

                header('Location: announcements.php?success=Announcement added successfully');
                exit();
            } else {
                $errors['database'] = "Error executing statement: " . $stmt->error;
            }
        } else {
            $errors['database'] = "Error preparing statement: " . $mysqli->error;
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
    <?php include("navbar.php")?>
    <div class="page-wrapper">
        <div class="row">
            <div class="col-md-9 mx-auto my-5">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Add New Announcement</h4>
                    </div>
                    <div class="card-body">
                    <?php if (!empty($success)) : ?>
                        <div class="alert alert-success"><?php echo htmlspecialchars($success); ?></div>
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

                    <form method="POST" action="addAnnouncement.php" enctype="multipart/form-data">
                        <div class="form-group">
                            <label for="title">Title</label>
                            <input type="text" name="title" id="title" class="form-control" value="<?php echo isset($_POST['title']) ? htmlspecialchars($_POST['title']) : ''; ?>">
                        </div>
                        <div class="form-group">
                            <label for="description">Description</label>
                            <textarea name="description" id="description" class="form-control" rows="5"><?php echo isset($_POST['description']) ? htmlspecialchars($_POST['description']) : (isset($announcement['description_announcement']) ? htmlspecialchars($announcement['description_announcement']) : ''); ?></textarea>
                            <small class="text-muted"><span id="description-count">0</span> / 300 characters</small>
                        </div>
                        <div class="form-group">
                            <label for="link">Link</label>
                            <input type="text" name="link" id="link" class="form-control" value="<?php echo isset($_POST['link']) ? htmlspecialchars($_POST['link']) : ''; ?>">
                        </div>
                        <div class="form-group">
                            <label for="image">Image</label>
                            <input type="file" name="image" id="image" class="form-control-file" accept="image/png, image/jpeg">
                        </div>
                        <button type="submit" class="btn btn-primary py-3 w-25">Add Announcement</button>
                        <a href="announcements.php" class="btn btn-secondary py-3 w-25">Cancel</a>
                    </form>

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

