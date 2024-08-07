<?php
session_start();

// Generate CSRF token
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Check if user is already logged in
if (isset($_SESSION['username'])) {
    header('Location: index.php');
    exit();
}
$errors = isset($_GET['errors']) ? $_GET['errors'] : '';
$input_data = isset($_GET['input_data']) ? $_GET['input_data'] : '';

$email = isset($input_data['email']) ? htmlspecialchars($input_data['email'], ENT_QUOTES, 'UTF-8') : '';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0" />
    <title>DOST-HRMO - Forgot Password</title>

    <link rel="shortcut icon" href="assets/img/dost_logo.png" />
    <link rel="stylesheet" href="assets/css/bootstrap.min.css" />
    <link rel="stylesheet" href="assets/plugins/fontawesome/css/fontawesome.min.css" />
    <link rel="stylesheet" href="assets/plugins/fontawesome/css/all.min.css" />
    <link rel="stylesheet" href="assets/css/style.css" />

    <style>
        .back-to-login-icon {
            display: inline-flex;
            align-items: center;
            font-size: 1rem;
            transition: transform 0.2s;
        }

        .back-to-login-icon:hover {
            transform: translateX(-5px);
        }

        .back-to-login-icon i {
            margin-right: 5px;
        }
    </style>
</head>

<body>
    <div class="main-wrapper login-body">
        <div class="login-wrapper">
            <div class="container">
                
                <img class="img-fluid logo-dark mb-2" src="assets/img/dost_logo.png" alt="Logo" />
                <div class="loginbox">
                    
                    <div class="login-right">
                        <div class="login-right-wrap">
                            <h1>Forgot Password</h1>
                            <p class="account-subtitle">Enter your email to receive a verification code</p>

                            <!-- FORM -->
                            <form action="PHP_Connections/send_verification.php" method="POST">
                                <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>" />
                                <div class="form-group">
                                    <label class="form-control-label">Email</label>
                                    <input class="form-control<?php echo !empty($errors) ? ' is-invalid' : ''; ?>" name="email" value="<?php echo $email; ?>" autocomplete="off" type="email" required />
                                    <?php if (!empty($errors)): ?>
                                        <small class="form-text text-danger"><?php echo htmlspecialchars($errors, ENT_QUOTES, 'UTF-8'); ?></small>
                                    <?php endif; ?>
                                </div>
                                <button class="btn btn-lg btn-block btn-primary" type="submit">Send Verification Code</button>
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
