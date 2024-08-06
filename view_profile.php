<?php include("PHP_Connections/update_profile.php") ?>
<!DOCTYPE html>
<?php include("logout_modal.php")?>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
    <title>HRMO Admin</title>
    <link rel="shortcut icon" href="assets/img/dost_logo.png">
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/plugins/fontawesome/css/fontawesome.min.css">
    <link rel="stylesheet" href="assets/plugins/fontawesome/css/all.min.css">
    <link rel="stylesheet" href="assets/css/profile.css">
</head>

<body>
    <div class="main-wrapper">
        <?php include("navbar.php")?>
        <div class="page-wrapper">
            <div class="container-fluid">
                <div class="breadcrumb-path mb-4 my-4">
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="profile.php"><img src="assets/img/dash.png" class="mr-2"
                                    alt="breadcrumb" />Profile</a>
                        </li>
                        <li class="breadcrumb-item active">Account</li>
                    </ul>
                </div>
                <div class="card">
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
                        <form method="POST" enctype="multipart/form-data" id="profileForm" class="needs-validation" novalidate>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="row py-2">
                                        <div class="form-group col-md-6">
                                            <label for="name">Name</label>
                                            <input type="text" name="name" id="name" class="form-control"
                                            value="<?php echo htmlspecialchars($admin['name']); ?>" autocomplete="off" required>
                                            <div class="invalid-feedback">
                                                Please Enter a Name.
                                            </div>
                                        </div>

                                        <div class="form-group col-md-6">
                                            <label for="email">Email</label>
                                            <input type="email" name="email" id="email" class="form-control"
                                            value="<?php echo htmlspecialchars($admin['email']); ?>" autocomplete="off" required>
                                            <div class="invalid-feedback">
                                                Please Enter a valid Email.
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row py-2">
                                        <div class="form-group col-md-6">
                                            <label for="username">Username</label>
                                            <input type="text" name="username" id="username" class="form-control"
                                            value="<?php echo htmlspecialchars($admin['username']); ?>" autocomplete="off" disabled>
                                        </div>

                                        <div class="form-group col-md-6">
                                        <label for="password">Password</label>
                                        <input type="password" name="password" id="password" class="form-control"
                                        value="********" disabled>
                                        </div>
                                    </div>
                                    <div class="col-md-12 d-flex justify-content-end">
                                    <button type="submit" class="update-btn">Update</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Confirmation Modal -->
    <div class="modal fade" id="confirmationModal" tabindex="-1" role="dialog" aria-labelledby="confirmationModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="confirmationModalLabel">Confirm Update</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    Are you sure you want to update your profile?
                </div>
                <div class="modal-footer">
                    <button type="button" class="update-btn-2" id="confirmUpdate">Yes, Update</button>
                    <button type="button" class="cancel-btn" data-dismiss="modal">Cancel</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Success Modal -->
    <div class="modal fade" id="successModal" tabindex="-1" role="dialog" aria-labelledby="successModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="successModalLabel">Success</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    Your profile has been updated successfully.
                </div>
                <div class="modal-footer">
                    <button type="button" class="update-btn-2" data-dismiss="modal">OK</button>
                </div>
            </div>
        </div>
    </div>
    <script src="assets/js/jquery-3.6.0.min.js"></script>
    <script src="assets/js/popper.min.js"></script>
    <script src="assets/js/bootstrap.min.js"></script>
    <script src="assets/js/feather.min.js"></script>
    <script src="assets/plugins/slimscroll/jquery.slimscroll.min.js"></script>
    <script src="assets/plugins/apexchart/apexcharts.min.js"></script>
    <script src="assets/plugins/apexchart/chart-data.js"></script>
    <script src="assets/js/profile.js"></script>
    <script src="assets/js/script.js"></script>
</body>

</html>