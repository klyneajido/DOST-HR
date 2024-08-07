<?php

session_start();
require 'db_connection.php'; // Include your database connection

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Combine the 6 separate inputs into one string
    $input_code = implode('', $_POST['code']);

    // Retrieve stored email from the session
    $stored_email = isset($_SESSION['reset_email']) ? $_SESSION['reset_email'] : '';

    if (empty($stored_email)) {
        // No email stored in session, redirect to forgot password
        header('Location: ../forgot_password.php?errors=' . urlencode('Session expired or invalid.'));
        exit();
    }

    // Fetch the stored verification code and expiration time from the database
    $stmt = $mysqli->prepare("SELECT verification_code, expires_at FROM password_resets WHERE email = ? ORDER BY created_at DESC LIMIT 1");
    $stmt->bind_param('s', $stored_email);
    $stmt->execute();
    $stmt->bind_result($stored_code, $expires_at);
    $stmt->fetch();
    $stmt->close();

    // Check if the verification code exists and has not expired
    $current_time = date('Y-m-d H:i:s');
    if ($stored_code && $current_time <= $expires_at) {
        // Validate the verification code
        if ($input_code === $stored_code) {
            // Verification successful, redirect to reset password page
            header('Location: ../reset_password.php');
            exit();
        } else {
            // Verification failed
            $error_message = 'Invalid verification code';
            header('Location: ../verify_code.php?errors=' . urlencode($error_message));
            exit();
        }
    } else {
        // No valid verification code found or it has expired
        $error_message = 'Verification code expired or not found';
        header('Location: ../verify_code.php?errors=' . urlencode($error_message));
        exit();
    }
} else {
    // If not POST request, redirect to the verify code page
    header('Location: ../verify_code.php');
    exit();
}
