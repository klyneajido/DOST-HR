<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <meta
      name="viewport"
      content="width=device-width, initial-scale=1.0, user-scalable=0"
    />
    <title>Dleohr - Bootstrap Admin HTML Template</title>

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
                <h1>Login</h1>
                <p class="account-subtitle">Access to our dashboard</p>

                <!-- FORM -->
                <form
                  action="login.php"
                  method="POST"
                  onsubmit="return validation()"
                >
                  <div class="form-group">
                    <label class="form-control-label">Username</label>
                    <input class="form-control" name="username" />
                     <?php
                    // PHP logic to handle login submission
                    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                        // Include database connection file
                        include_once 'db_connection.php'; // Ensure this file exists and contains the MySQL connection code

                        // Debugging: Check if $mysqli is set
                        if (!isset($mysqli)) {
                            die('Database connection failed. $mysqli is not set.');
                        }

                        // Get username and password from form POST data
                        $username = $_POST['username'];
                        $password = $_POST['password'];

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
                                session_start();
                                $_SESSION['username'] = $username;
                                $_SESSION['name'] = $row['name']; // Store user's name in the session

                                // Redirect to dashboard or any other page
                                header('Location: index.php');
                                exit();
                            } else {
                                // Authentication failed, show error message
                                echo '<div class="invalid-feedback d-block">Invalid username or password.</div>';
                            }
                        } else {
                            // Authentication failed, show error message
                            echo '<div class="invalid-feedback d-block">Invalid username or password.</div>';
                        }
                    }
                    ?>
                  </div>
                  <div class="form-group">
                    <label class="form-control-label">Password</label>
                    <div class="pass-group">
                      <input
                        type="password"
                        class="form-control pass-input"
                        name="password"
                      />
                      <span class="fas fa-eye toggle-password"></span>
                    </div>
                  </div>
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
      function validation() {
        var id = document.f1.user.value;
        var ps = document.f1.pass.value;
        if (id.length == "" && ps.length == "") {
          alert("User Name and Password fields are empty");
          return false;
        } else {
          if (id.length == "") {
            alert("User Name is empty");
            return false;
          }
          if (ps.length == "") {
            alert("Password field is empty");
            return false;
          }
        }
      }
    </script>
    <script src="assets/js/jquery-3.5.1.min.js"></script>

    <script src="assets/js/popper.min.js"></script>
    <script src="assets/js/bootstrap.min.js"></script>

    <script src="assets/js/feather.min.js"></script>

    <script src="assets/js/script.js"></script>
  </body>
</html>
