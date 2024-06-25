<?php
$errors = array();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    include_once 'PHP_Connections/db_connection.php';

    // Validate and sanitize input
    $name = isset($_POST['name']) ? trim($_POST['name']) : '';
    $username = isset($_POST['username']) ? trim($_POST['username']) : '';
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';
    $confirmPassword = isset($_POST['confirmPassword']) ? $_POST['confirmPassword'] : '';

    if (empty($name)) {
        $errors['name'] = "Name cannot be empty.";
    }

    if (empty($username)) {
            $errors['username'] = "Username cannot be empty.";
        } else {
            // Check if username already exists
            $sql = "SELECT admin_id FROM admins WHERE username = ?";
            $stmt = $mysqli->prepare($sql);
            if ($stmt) {
                $stmt->bind_param("s", $username);
                $stmt->execute();
                $stmt->store_result();
                if ($stmt->num_rows > 0) {
                    $errors['username'] = "Username already exists.";
                }
                $stmt->close();
            } else {
                $errors['general'] = "Error in preparing statement: " . $mysqli->error;
            }
        }

    if (empty($email)) {
        $errors['email'] = "Email cannot be empty.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = "Invalid email format.";
    } else {
        // Check if email already exists
        $sql = "SELECT admin_id FROM admins WHERE email = ?";
        $stmt = $mysqli->prepare($sql);
        if ($stmt) {
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $stmt->store_result();
            if ($stmt->num_rows > 0) {
                $errors['email'] = "Email already exists.";
            }
            $stmt->close();
        } else {
            $errors['general'] = "Error in preparing statement: " . $mysqli->error;
        }
    }

    if (empty($password)) {
            $errors['password'] = "Password cannot be empty.";
        } elseif (strlen($password) < 8 || !preg_match('/[0-9]/', $password) || !preg_match('/[\W_]/', $password)) {
            $errors['password'] = "Password must be at least 8 characters long and include at least one number and one special character.";
        }

    if ($password !== $confirmPassword) {
        $errors['confirmPassword'] = "Passwords do not match.";
    }

    if (empty($errors)) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Prepare and execute SQL statement
        $sql = "INSERT INTO admins (name, username, password, email) VALUES (?, ?, ?, ?)";
        $stmt = $mysqli->prepare($sql);

        if ($stmt) {
            $stmt->bind_param("ssss", $name, $username, $hashed_password, $email);

            if ($stmt->execute()) {
                // Registration successful, redirect to login page
                header("Location: login.php");
                exit(); // Ensure script termination after redirect
            } else {
                $errors['general'] = "Error: " . $stmt->error;
            }

            $stmt->close();
        } else {
            $errors['general'] = "Error in preparing statement: " . $mysqli->error;
        }

        $mysqli->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <meta
      name="viewport"
      content="width=device-width, initial-scale=1.0, user-scalable=0"
    />
    <title>Kanakku - Bootstrap Admin HTML Template</title>

    <link rel="shortcut icon" href="assets/img/favicon.png" />

    <link rel="stylesheet" href="assets/css/bootstrap.min.css" />

    <link
      rel="stylesheet"
      href="assets/plugins/fontawesome/css/fontawesome.min.css"
    />
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
          <img
            class="img-fluid logo-dark mb-2"
            src="assets/img/dost_logo.png"
            alt="Logo"
          />
            <div class="loginbox">
                    <div class="login-right">
                        <div class="login-right-wrap">
                            <h1>Register</h1>
                            <p class="account-subtitle">Access to our dashboard</p>

                            <form action="register.php" method="post">
                                <div class="form-group">
                                    <label class="form-control-label">Name</label>
                                    <input class="form-control" type="text" id="Name" name="name" value="<?php echo htmlspecialchars($name ?? ''); ?>" required />
                                    <?php if (isset($errors['name'])): ?>
                                        <small class="form-text text-danger"><?php echo $errors['name']; ?></small>
                                    <?php endif; ?>
                                </div>
                                <div class="form-group">
                                    <label class="form-control-label">Username</label>
                                    <input class="form-control" type="text" id="Username" name="username" value="<?php echo htmlspecialchars($username ?? ''); ?>" required />
                                    <?php if (isset($errors['username'])): ?>
                                        <small class="form-text text-danger"><?php echo $errors['username']; ?></small>
                                    <?php endif; ?>
                                </div>
                                <div class="form-group">
                                    <label class="form-control-label">Email Address</label>
                                    <input class="form-control" type="email" id="Email" name="email" value="<?php echo htmlspecialchars($email ?? ''); ?>" required />
                                    <?php if (isset($errors['email'])): ?>
                                        <small class="form-text text-danger"><?php echo $errors['email']; ?></small>
                                    <?php endif; ?>
                                </div>
                                <div class="form-group">
                                    <label class="form-control-label">Password</label>
                                    <input class="form-control" type="password" id="Password" name="password" required />
                                    <?php if (isset($errors['password'])): ?>
                                        <small class="form-text text-danger"><?php echo $errors['password']; ?></small>
                                    <?php endif; ?>
                                </div>
                                <div class="form-group">
                                    <label class="form-control-label">Confirm Password</label>
                                    <input class="form-control" type="password" id="ConfirmPass" name="confirmPassword" required />
                                    <?php if (isset($errors['confirmPassword'])): ?>
                                        <small class="form-text text-danger"><?php echo $errors['confirmPassword']; ?></small>
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

                            <div class="login-or">
                                <span class="or-line"></span>
                                <span class="span-or">or</span>
                            </div>

                            <div class="social-login">
                                <span>Register with</span>
                                <a href="#" class="facebook"><i class="fab fa-facebook-f"></i></a>
                                <a href="#" class="google"><i class="fab fa-google"></i></a>
                            </div>

                            <div class="text-center dont-have">
                                Already have an account? <a href="login.html">Login</a>
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
