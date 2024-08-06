<?php include_once("PHP_Connections/populate_department_inputs.php")?>

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
    <link rel="stylesheet" href="assets/css/departments.css" />

</head>

<body class="scrollbar" id="style-5">
    <?php include("logout_modal.php")?>
    <div class="main-wrapper">
        <?php include("navbar.php")?>
        <div class="page-wrapper">
            <div class="row">
                <div class="col-md-9 mx-auto my-5">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Edit Department</h4>
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
                            <form method="POST" action="PHP_Connections/update_department.php" enctype="multipart/form-data"
                                class="needs-validation" novalidate>
                                <input type="hidden" name="department_id" value="<?php echo htmlspecialchars($department['department_id']); ?>">
                                <div class="row mb-4">
                                    <div class="form-group col-md-12 ">
                                        <label for="name">Department Name: </label>
                                        <input type="text" name="name" id="name" class="form-control" value="<?php echo htmlspecialchars($department['name']); ?>"
                                            autocomplete="off" required>
                                        <div class="invalid-feedback">Required</div>
                                    </div>

                                    <div class="form-group col-md-12 ">
                                        <label for="location">Location: </label>
                                        <input type="text" name="location" id="location" class="form-control" value="<?php echo htmlspecialchars($department['location']); ?>"
                                            autocomplete="off" required>
                                        <div class="invalid-feedback">Required</div>
                                    </div>
                                    <div class="d-flex justify-content-between mx-1 col-md-12">
                                        <button class="col-md-5 bwtn btn-info" type="submit">Update</button>
                                        <a href="departments.php" class="col-md-5 btn btn-secondary">Cancel</a>
                                    </div>
                                </div>
                            </form>
                            <!-- END FORM -->
                        </div>
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
    <script src="assets/js/addAnnouncement.js"></script>
</body>

</html>
