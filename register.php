<?php
$errors = isset($_GET['errors']) ? $_GET['errors'] : array();
$name = isset($_GET['input_data']['name']) ? $_GET['input_data']['name'] : '';
$username = isset($_GET['input_data']['username']) ? $_GET['input_data']['username'] : '';
$email = isset($_GET['input_data']['email']) ? $_GET['input_data']['email'] : '';

$username_error = isset($errors['username']) ? $errors['username'] : '';
$email_error = isset($errors['email']) ? $errors['email'] : '';
$password_error = isset($errors['password']) ? $errors['password'] : '';
$confirmPassword_error = isset($errors['confirmPassword']) ? $errors['confirmPassword'] : '';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0" />
    <title>Kanakku - Bootstrap Admin HTML Template</title>
    <link rel="shortcut icon" href="assets/img/favicon.png" />
    <link rel="stylesheet" href="assets/css/bootstrap.min.css" />
    <link rel="stylesheet" href="assets/plugins/fontawesome/css/fontawesome.min.css" />
    <link rel="stylesheet" href="assets/plugins/fontawesome/css/all.min.css" />
    <link rel="stylesheet" href="assets/css/style.css" />
    <!--[if lt IE 9]>
    <script src="assets/js/html5shiv.min.js"></script>
    <script src="assets/js/respond.min.js"></script>
    <![endif]-->
</head>

<body>
    <div class="main-wrapper login-body">
        <div class="login-wrapper">
            <div class="container">
                <img class="img-fluid logo-dark mb-2" src="assets/img/dost_logo.png" alt="Logo" />
                <div class="loginbox">
                    <div class="login-right">
                        <div class="login-right-wrap">
                            <h1>Register</h1>
                            <p class="account-subtitle">Access to our dashboard</p>

                            <form action="PHP_Connections/registerConn.php" method="post">
                                <div class="form-group">
                                    <label class="form-control-label">Name</label>
                                    <input class="form-control" type="text" id="Name" name="name"
                                        value="<?php echo htmlspecialchars($name); ?>" required />
                                </div>
                                <div class="form-group">
                                    <label class="form-control-label">Username</label>
                                    <input class="form-control" type="text" id="Username" name="username"
                                        value="<?php echo htmlspecialchars($username); ?>" required />
                                    <?php if (!empty($username_error)): ?>
                                        <small class="form-text text-danger"><?php echo $username_error; ?></small>
                                    <?php endif; ?>
                                </div>
                                <div class="form-group">
                                    <label class="form-control-label">Email Address</label>
                                    <input class="form-control" type="email" id="Email" name="email"
                                        value="<?php echo htmlspecialchars($email); ?>" required />
                                    <?php if (!empty($email_error)): ?>
                                        <small class="form-text text-danger"><?php echo $email_error; ?></small>
                                    <?php endif; ?>
                                </div>
                                <div class="form-group">
                                    <label class="form-control-label">Password</label>
                                    <input class="form-control" type="password" id="Password" name="password"
                                        required />
                                    <?php if (!empty($password_error)): ?>
                                        <small class="form-text text-danger"><?php echo $password_error; ?></small>
                                    <?php endif; ?>
                                </div>
                                <div class="form-group">
                                    <label class="form-control-label">Confirm Password</label>
                                    <input class="form-control" type="password" id="ConfirmPass" name="confirmPassword"
                                        required />
                                    <?php if (!empty($confirmPassword_error)): ?>
                                        <small class="form-text text-danger"><?php echo $confirmPassword_error; ?></small>
                                    <?php endif; ?>
                                </div>
                                <?php if (isset($errors['general'])): ?>
                                    <div class="form-group">
                                        <small class="form-text text-danger"><?php echo $errors['general']; ?></small>
                                    </div>
                                <?php endif; ?>
                                <div class="form-group mb-0">
                                    <button class="btn btn-lg btn-block btn-primary" type="submit">
                                        Register
                                    </button>
                                </div>
                            </form>

                            <!-- <div class="login-or">
                                <span class="or-line"></span>
                                <span class="span-or">or</span>
                            </div> -->

                            <!-- <div class="social-login">
                                <span>Register with</span>
                                <a href="#" class="facebook"><i class="fab fa-facebook-f"></i></a>
                                <a href="#" class="google"><i class="fab fa-google"></i></a>
                            </div> -->

                            <div class="text-center dont-have">
                                Already have an account? <a href="login.php">Login</a>
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