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

$errors = isset($_GET['errors']) ? $_GET['errors'] : array();
$input_data = isset($_GET['input_data']) ? $_GET['input_data'] : array();

$username = isset($input_data['username']) ? htmlspecialchars($input_data['username'], ENT_QUOTES, 'UTF-8') : '';

$username_error = isset($errors['username']) ? $errors['username'] : '';
$password_error = isset($errors['password']) ? $errors['password'] : '';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0" />
    <title>DOST-HRMO</title>

    <link rel="shortcut icon" href="assets/img/dost_logo.png" />
    <link rel="stylesheet" href="assets/css/bootstrap.min.css" />
    <link rel="stylesheet" href="assets/plugins/fontawesome/css/fontawesome.min.css" />
    <link rel="stylesheet" href="assets/plugins/fontawesome/css/all.min.css" />
    <link rel="stylesheet" href="assets/css/style.css" />
</head>

<body>
    <div class="main-wrapper login-body">
        <div class="login-wrapper">
            <div class="container">
                
            <img class="img-fluid logo-dark mb-2" src="assets/img/dost_logo.png" alt="Logo" />
                <div class="loginbox">
                    
                    <div class="login-right">
                        <div class="login-right-wrap">
                            <h1>Login</h1>
                            <p class="account-subtitle">Access to our dashboard</p>

                            <!-- FORM -->
                            <form action="PHP_Connections/loginConn.php" method="POST">
                                <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>" />
                                <div class="form-group">
                                    <label class="form-control-label">Username</label>
                                    <input class="form-control <?php echo !empty($username_error) ? 'is-invalid' : ''; ?>" name="username" value="<?php echo $username; ?>" autocomplete="off" />
                                    <?php if (!empty($username_error)): ?>
                                        <small class="form-text text-danger"><?php echo htmlspecialchars($username_error, ENT_QUOTES, 'UTF-8'); ?></small>
                                    <?php endif; ?>
                                </div>
                                <div class="form-group">
                                    <label class="form-control-label">Password</label>
                                    <div class="pass-group">
                                        <input type="password" class="form-control pass-input <?php echo !empty($password_error) ? 'is-invalid' : ''; ?>" name="password" autocomplete="off" id="password" />
                                        <i id="icon" class="far fa-eye"></i>
                                        <?php if (!empty($password_error)): ?>
                                            <small class="form-text text-danger"><?php echo htmlspecialchars($password_error, ENT_QUOTES, 'UTF-8'); ?></small>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <?php if (!empty($errors['general'])): ?>
                                    <div class="form-group">
                                        <small class="form-text text-danger"><?php echo htmlspecialchars($errors['general'], ENT_QUOTES, 'UTF-8'); ?></small>
                                    </div>
                                <?php endif; ?>
                                <div class="form-group">
                                    <div class="row">
                                        <!-- <div class="col-6">
                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox" class="custom-control-input" id="cb1" />
                                                <label class="custom-control-label" for="cb1">Remember me</label>
                                            </div>
                                        </div> -->
                                        <!-- <div class="col-6 text-right">
                                            <a class="forgot-link" href="forgot-password.html">Forgot Password?</a>
                                        </div> -->
                                    </div>
                                </div>
                                <button class="btn btn-lg btn-block btn-primary" type="submit">Login</button>
                                <div class="text-center dont-have">
                                    Don't have an account yet? <a href="register.php">Register</a>
                                </div>
                            </form>
                            <!-- END FORM -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        var myInput = document.getElementById('password'),
            myIcon = document.getElementById('icon');

        myIcon.onclick = function () {
            if (myIcon.classList.contains('fa-eye')) {
                myIcon.classList.toggle('fa-eye-slash');
                myIcon.classList.toggle('fa-eye');
                myInput.setAttribute('type', 'text');
            } else {
                myInput.setAttribute('type', 'password');
                myIcon.classList.toggle('fa-eye');
                myIcon.classList.toggle('fa-eye-slash');
            }
        };
    </script>
    <script src="assets/js/jquery-3.5.1.min.js"></script>
    <script src="assets/js/popper.min.js"></script>
    <script src="assets/js/bootstrap.min.js"></script>
    <script src="assets/js/feather.min.js"></script>
    <script src="assets/js/script.js"></script>
</body>

</html>
