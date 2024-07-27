<?php 
include("PHP_Connections/checkUser.php"); 

// Retrieve error messages from query string
$errors = isset($_GET['errors']) ? json_decode($_GET['errors'], true) : [];
$success_message = isset($_GET['success_message']) ? $_GET['success_message'] : '';

?>

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
    <style>
        .password-cover {
            -webkit-text-security: disc;
        }
        .form-control[disabled] {
            background-color: #e9ecef;
            cursor: not-allowed;
        }
        .error-message {
            color: red;
            font-size: 0.875em;
        }
    </style>
</head>

<body class="scrollbar" id="style-5">
    <?php include("logout_modal.php") ?>
    <div class="main-wrapper">
        <?php include("navbar.php") ?>
        <div class="page-wrapper">
            <div class="container-fluid">
                <div class="breadcrumb-path mb-4 my-4">
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href=""><img src="assets/img/dash.png" class="mr-2" alt="breadcrumb" />Accounts</a>
                        </li>
                        <li class="breadcrumb-item active">Edit Admin</li>
                    </ul>
                </div>
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Edit Admin Account</h3>
                    </div>
                    <div class="card-body">
                        <?php
                        if (isset($_GET['id'])) {
                            $admin_id = intval($_GET['id']);
                            $sql = "SELECT * FROM admins WHERE admin_id = ?";
                            $stmt = $mysqli->prepare($sql);
                            if ($stmt) {
                                $stmt->bind_param("i", $admin_id);
                                $stmt->execute();
                                $result = $stmt->get_result();
                                if ($result->num_rows > 0) {
                                    $admin = $result->fetch_assoc();
                                } else {
                                    die("Admin not found.");
                                }
                                $stmt->close();
                            } else {
                                die("Error in preparing statement: " . $mysqli->error);
                            }
                        } else {
                            die("No admin ID provided.");
                        }
                        ?>
                        <form id="updateForm" action="PHP_Connections/updateAccount.php" method="POST">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="name">Name</label>
                                        <input type="text" class="form-control" id="name" name="name" value="<?php echo htmlspecialchars($admin['name']); ?>" required>
                                        <?php if (isset($errors['name'])): ?>
                                            <div class="text-danger"><?php echo htmlspecialchars($errors['name']); ?></div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="username">Username</label>
                                        <input type="text" class="form-control" id="username" name="username" value="<?php echo htmlspecialchars($admin['username']); ?>" disabled>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="authority">Authority</label>
                                        <select class="form-control <?php echo isset($errors['authority']) ? 'is-invalid' : ''; ?>" id="authority" name="authority" required>
                                            <option value="admin" <?php echo $admin['authority'] == 'admin' ? 'selected' : ''; ?>>Admin</option>
                                            <option value="superadmin" <?php echo $admin['authority'] == 'superadmin' ? 'selected' : ''; ?>>Superadmin</option>
                                        </select>
                                        <?php if (isset($errors['authority'])): ?>
                                            <div class="invalid-feedback"><?php echo htmlspecialchars($errors['authority']); ?></div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="newPassword">New Password</label>
                                        <input type="password" class="form-control <?php echo isset($errors['newPassword']) ? 'is-invalid' : ''; ?>" id="newPassword" name="newPassword">
                                        <?php if (isset($errors['newPassword'])): ?>
                                            <div class="invalid-feedback"><?php echo htmlspecialchars($errors['newPassword']); ?></div>
                                        <?php endif; ?>
                                    </div>
                                    <div class="form-group">
                                        <label for="confirmPassword">Confirm Password</label>
                                        <input type="password" class="form-control <?php echo isset($errors['confirmPassword']) ? 'is-invalid' : ''; ?>" id="confirmPassword" name="confirmPassword">
                                        <?php if (isset($errors['confirmPassword'])): ?>
                                            <div class="invalid-feedback"><?php echo htmlspecialchars($errors['confirmPassword']); ?></div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                            <input type="hidden" name="admin_id" value="<?php echo htmlspecialchars($admin['admin_id']); ?>">
                            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#confirmationModal">Update Password</button>
                            <a href="accounts.php" class="btn btn-secondary">Cancel</a>
                        </form>
                    </div>
                </div>
                <!-- Confirmation Modal -->
                <div class="modal fade" id="confirmationModal" tabindex="-1" role="dialog" aria-labelledby="confirmationModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="confirmationModalLabel">Confirm Update</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <p>Are you sure you want to update the admin account?</p>
                                <p>Make sure you have permission first before updating this account</p>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                <button type="button" class="btn btn-primary" onclick="submitForm()">Yes, Update</button>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Pop-up notification -->
                <?php if (!empty($success_message)) : ?>
                    <script>
                        alert('<?php echo addslashes($success_message); ?>');
                    </script>
                <?php endif; ?>
            </div>
        </div>
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
    <script>
        function submitForm() {
            document.getElementById("updateForm").submit();
        }
    </script>
</body>

</html>
