<?php
// Start session
session_start();

// Initialize error messages
$usernameError = '';
$passwordError = '';
$loginError = '';

// Include database connection file
include_once 'PHP_Connections\db_connection.php'; // Ensure this file exists and contains the MySQL connection code

// Debugging: Check if $mysqli is set
if (!isset($mysqli)) {
    die('Database connection failed. $mysqli is not set.');
}

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get username and password from form POST data
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Validate inputs
    if (empty($username)) {
        $usernameError = 'You need to input a username.';
    }
    if (empty($password)) {
        $passwordError = 'You need to input a password.';
    }

    if (empty($usernameError) && empty($passwordError)) {
        // Query to retrieve hashed password from the database
        $query = "SELECT * FROM admins WHERE username = ?";

        // Prepare the query
        $stmt = $mysqli->prepare($query);

        if ($stmt === false) {
            die('MySQL prepare error: ' . htmlspecialchars($mysqli->error));
        }

        // Bind parameter
        $stmt->bind_param('s', $username);

        // Execute the query
        if (!$stmt->execute()) {
            die('Execute failed: ' . htmlspecialchars($stmt->error));
        }

        // Store the result
        $result = $stmt->get_result();

        // Check if the user exists in the database
        if ($result->num_rows == 1) {
            // Fetch the row
            $row = $result->fetch_assoc();

            // Verify the password
            if (password_verify($password, $row['password'])) {
                // Authentication successful, set session variables
                $_SESSION['username'] = $username;
                $_SESSION['name'] = $row['name']; // Store user's name in the session

                // Redirect to dashboard or any other page
                header('Location: index.php');
                exit();
            } else {
                // Authentication failed, set error message
                $loginError = 'Invalid username or password.';
            }
        } else {
            // Authentication failed, set error message
            $loginError = 'Invalid username or password.';
        }
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
    <title>DOST-HRMO</title>

    <link rel="shortcut icon" href="assets/img/dost_logo.png" />

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
                <h1>Login</h1>
                <p class="account-subtitle">Access to our dashboard</p>

                <!-- FORM -->
                <form
                  action=""
                  method="POST"
                  onsubmit="return validation()"
                >
                  <div class="form-group">
                    <label class="form-control-label">Username</label>
                    <input class="form-control" name="username" value="" />
                      <?php if ($usernameError): ?>
                        <div class="invalid-feedback d-block"><?php echo $usernameError; ?></di>
                      <?php endif; ?>
                  </div>
                  <div class="form-group">
                    <label class="form-control-label">Password</label>
                    <div class="pass-group">
                      <input
                        type="password"
                        class="form-control pass-input"
                        name="password"
                        value=""
                      />
                      <?php if ($passwordError): ?>
                        <div class="invalid-feedback d-block"><?php echo $passwordError; ?></di>
                      <?php endif; ?>
                    </div>
                  </div>
                  <?php if ($loginError): ?>
                    <div class="invalid-feedback d-block"><?php echo $loginError; ?></div>
                  <?php endif; ?>
                  <div class="form-group">
                    <div class="row">
                      <div class="col-6">
                        <div class="custom-control custom-checkbox">
                          <input
                            type="checkbox"
                            class="custom-control-input"
                            id="cb1"
                          />
                          <label class="custom-control-label" for="cb1"
                            >Remember me</label
                          >
                        </div>
                      </div>
                      <div class="col-6 text-right">
                        <a class="forgot-link" href="forgot-password.html"
                          >Forgot Password ?</a
                        >
                      </div>
                    </div>
                  </div>
                  <button
                    class="btn btn-lg btn-block btn-primary"
                    type="submit"
                  >
                    Login
                  </button>
                  <div class="login-or">
                    <span class="or-line"></span>
                    <span class="span-or">or</span>
                  </div>

                  <div class="social-login mb-3">
                    <span>Login with</span>
                    <a href="#" class="facebook"
                      ><i class="fab fa-facebook-f"></i></a
                    ><a href="#" class="google"
                      ><i class="fab fa-google"></i
                    ></a>
                  </div>

                  <div class="text-center dont-have">
                    Don't have an account yet?
                    <a href="register.php">Register</a>
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
      // function validation() {
      //   var id = document.getElementsByName("username")[0].value;
      //   var ps = document.getElementsByName("password")[0].value;
      //   if (id == "" && ps == "") {
      //     alert("User Name and Password fields are edddmpty");
      //     return false;
      //   } else {
      //     if (id == "") {
      //       alert("User Name is empty");
      //       return false;
      //     }
      //     if (ps == "") {
      //       alert("Password field is empty");
      //       return false;
      //     }
      //   }
      // }
    </script>
    <script src="assets/js/jquery-3.5.1.min.js"></script>

    <script src="assets/js/popper.min.js"></script>
    <script src="assets/js/bootstrap.min.js"></script>

    <script src="assets/js/feather.min.js"></script>

    <script src="assets/js/script.js"></script>
  </body>
</html>
