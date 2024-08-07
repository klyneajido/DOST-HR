<?php
session_start();
$errors = isset($_GET['errors']) ? $_GET['errors'] : '';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="assets/img/dost_logo.png" />
    <title>Verify Code - DOST-HRMO</title>
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        .code-input {
            width: 40px;
            height: 40px;
            margin: 0 5px;
            text-align: center;
            font-size: 1.5rem;
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
                            <h1>Verify Code</h1>
                            <p class="account-subtitle">Enter the 6-digit verification code sent to your email</p>

                            <!-- FORM -->
                            <form id="verifyForm" action="PHP_Connections/verify_code_process.php" method="POST">
                                <div class="form-group">
                                    <label class="form-control-label">Verification Code</label>
                                    <div style="display: flex; justify-content: center;">
                                        <input class="form-control code-input<?php echo !empty($errors) ? ' is-invalid' : ''; ?>" maxlength="1" name="code[]" type="text" required>
                                        <input class="form-control code-input<?php echo !empty($errors) ? ' is-invalid' : ''; ?>" maxlength="1" name="code[]" type="text" required>
                                        <input class="form-control code-input<?php echo !empty($errors) ? ' is-invalid' : ''; ?>" maxlength="1" name="code[]" type="text" required>
                                        <input class="form-control code-input<?php echo !empty($errors) ? ' is-invalid' : ''; ?>" maxlength="1" name="code[]" type="text" required>
                                        <input class="form-control code-input<?php echo !empty($errors) ? ' is-invalid' : ''; ?>" maxlength="1" name="code[]" type="text" required>
                                        <input class="form-control code-input<?php echo !empty($errors) ? ' is-invalid' : ''; ?>" maxlength="1" name="code[]" type="text" required>
                                    </div>
                                    <?php if (!empty($errors)): ?>
                                        <small class="error-text"><?php echo htmlspecialchars($errors, ENT_QUOTES, 'UTF-8'); ?></small>
                                    <?php endif; ?>
                                </div>
                                <button class="btn btn-lg btn-block btn-primary" type="submit">Verify</button>
                            </form>
                            <!-- END FORM -->

                            <div class="text-center dont-have mt-3">
                                <a href="forgot_password.php" class="back-to-login-icon">
                                    <i class="fas fa-arrow-left"></i> Back to Forgot Password
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
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const inputs = document.querySelectorAll('.code-input');

            inputs.forEach((input, index) => {
                input.addEventListener('input', () => {
                    if (input.value.length === 1 && index < inputs.length - 1) {
                        inputs[index + 1].focus();
                    }
                });

                input.addEventListener('keydown', (event) => {
                    if (event.key === 'Backspace' && index > 0 && input.value === '') {
                        inputs[index - 1].focus();
                    }
                });
            });
        });
    </script>
</body>

</html>
