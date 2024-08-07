<?php include("PHP_Connections/update_announcement.php")?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0" />
    <title>Edit Job</title>
    <link rel="shortcut icon" href="assets/img/dost_logo.png" />
    <link rel="stylesheet" href="assets/css/bootstrap.min.css" />
    <link rel="stylesheet" href="assets/plugins/select2/css/select2.min.css" />
    <link rel="stylesheet" href="assets/plugins/fontawesome/css/fontawesome.min.css" />
    <link rel="stylesheet" href="assets/plugins/fontawesome/css/all.min.css" />
    <link rel="stylesheet" href="assets/css/announcement.css" />
</head>

<body class="scrollbar" id="style-5">
    <?php include("modal_logout.php")?>
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

                            <!-- START FORM -->
                            <form id="announcementForm" method="POST" enctype="multipart/form-data" class="needs-validation" novalidate>
                                <div class="row mb-4">
                                    <div class="form-group col-md-12 ">
                                        <label for="title">Title</label>
                                        <input type="text" name="title" id="title" class="form-control" value="<?php echo htmlspecialchars($announcement['title']); ?>" autocomplete="off" required>
                                        <div class="invalid-feedback">Required</div>
                                    </div>

                                    <div class="form-group col-md-12 py-1">
                                        <label for="description">Description</label>
                                        <textarea name="description" id="description" class="form-control" rows="5" autocomplete="off" required><?php echo htmlspecialchars($announcement['description_announcement']); ?></textarea>
                                        <small class="text-muted"><span id="description-count">0</span> / 300 characters</small>
                                        <div class="invalid-feedback">Required</div>
                                    </div>

                                    <div class="form-group col-md-12 ">
                                        <label for="link">Link</label>
                                        <input type="text" name="link" id="link" class="form-control" value="<?php echo htmlspecialchars($announcement['link']); ?>" autocomplete="off" required>
                                        <div class="invalid-feedback">Required</div>
                                    </div>

                                    <div class="col-md-12">
                                        <label for="image" class="form-label">Image</label>
                                        <input type="file" class="form-control" name="image" id="image" accept="image/png, image/jpeg">
                                    </div>
                                </div>

                                <div class="d-flex justify-content-between">
                                    <button type="button" class="col-md-5 btn btn-info mr-2" data-toggle="modal" data-target="#confirmModal">Update</button>
                                    <a href="view_announcements.php" class="col-md-5 btn btn-secondary">Cancel</a>
                                </div>
                            </form>

                            <!-- Confirmation Modal -->
                            <div class="modal fade" id="confirmModal" tabindex="-1" role="dialog" aria-labelledby="confirmModalLabel" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="confirmModalLabel">Confirm Update</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            Are you sure you want to update this announcement?
                                        </div>
                                        <div class="modal-footer">
                                        <button type="button" class="btn btn-primary" id="confirmUpdate">Update</button>
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- End Confirmation Modal -->
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Scripts -->
        <script src="assets/js/jquery-3.6.0.min.js"></script>
        <script src="assets/js/popper.min.js"></script>
        <script src="assets/js/bootstrap.min.js"></script>
        <script src="assets/js/feather.min.js"></script>
        <script src="assets/plugins/slimscroll/jquery.slimscroll.min.js"></script>
        <script src="assets/plugins/select2/js/select2.min.js"></script>
        <script src="assets/js/script.js"></script>
        <script src="assets/js/edit_announcement.js"></script>


    </div>
</body>

</html>
