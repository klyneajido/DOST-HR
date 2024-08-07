<?php
session_start();
$errors = isset($_GET['errors']) ? $_GET['errors'] : '';
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
$csrf_token = $_SESSION['csrf_token'];
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="assets/img/dost_logo.png" />
    <title>Reset Password - DOST-HRMO</title>
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        .form-control {
            border-radius: 0.25rem;
        }
        .is-invalid {
            border-color: #dc3545;
        }
        .error-text {
            color: #dc3545;
            font-size: 0.875rem;
            margin-top: 0.25rem;
        }
    </style>
</head>

<body>
    <div class="main-wrapper login-body">
        <div class="login-wrapper">
            <div class="container">
                <img class="img-fluid logo-dark mb-2" src="assets/img/dost_logo.png" alt="Logo">
                <div class="loginbox">
                    <div class="login-right">
                        <div class="login-right-wrap">
                            <h1>Reset Password</h1>
                            <p class="account-subtitle">Enter your new password below</p>

                            <!-- FORM -->
                            <form id="resetPasswordForm" action="PHP_Connections/reset_password_process.php" method="POST">
                                <div class="form-group">
                                    <label class="form-control-label">New Password</label>
                                    <input class="form-control<?php echo !empty($errors) ? ' is-invalid' : ''; ?>" name="new_password" type="password" required>
                                    <?php if (!empty($errors)): ?>
                                        <small class="error-text"><?php echo htmlspecialchars($errors, ENT_QUOTES, 'UTF-8'); ?></small>
                                    <?php endif; ?>
                                </div>
                                <div class="form-group">
                                    <label class="form-control-label">Confirm Password</label>
                                    <input class="form-control<?php echo !empty($errors) ? ' is-invalid' : ''; ?>" name="confirm_password" type="password" required>
                                    <?php if (!empty($errors)): ?>
                                        <small class="error-text"><?php echo htmlspecialchars($errors, ENT_QUOTES, 'UTF-8'); ?></small>
                                    <?php endif; ?>
                                </div>
                                <button class="btn btn-lg btn-block btn-primary" type="submit">Reset Password</button>
                            </form>
                            <!-- END FORM -->

                            <div class="text-center dont-have mt-3">
                                <a href="login.php" class="back-to-login-icon">
                                    <i class="fas fa-arrow-left"></i> Back to Login
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="assets/js/jquery-3.5.1.min.js"></script>
    <script src="assets/js/popper.min.js"></script>
    <script src="assets/js/bootstrap.min.js"></script>
    <script src="assets/js/feather.min.js"></script>
    <script src="assets/js/script.js"></script>
</body>

</html>
