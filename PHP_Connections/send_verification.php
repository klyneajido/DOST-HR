<?php

session_start();
require '../vendor/autoload.php'; // Include PHPMailer
require 'db_connection.php';   // Include your database connection

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Check if email is provided
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);

    // Validate email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        header('Location: forgot_password.php?errors=Invalid email address&input_data[email]=' . urlencode($email));
        exit();
    }

    // Store email in session
    $_SESSION['reset_email'] = $email;

    // Generate a 6-digit verification code
    $verification_code = sprintf('%06d', mt_rand(0, 999999));
    $expires_at = date('Y-m-d H:i:s', strtotime('+15 minutes')); // Code expires in 15 minutes
    $created_at = date('Y-m-d H:i:s');

    // Insert into password_resets table
    $stmt = $mysqli->prepare("INSERT INTO password_resets (email, verification_code, expires_at, created_at) VALUES (?, ?, ?, ?)");
    $stmt->bind_param('ssss', $email, $verification_code, $expires_at, $created_at);

    if ($stmt->execute()) {
        // Send verification email
        $mail = new PHPMailer(true);

        try {
            // Server settings
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com'; // Gmail SMTP server
            $mail->SMTPAuth   = true;
            $mail->Username   = 'dosthrmo@gmail.com'; // Your Gmail address
            $mail->Password   = 'szoj mmej jhfr hyxn'; // Your App Password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = 587;

            // Recipients
            $mail->setFrom('dosthrmo@gmail.com', 'DOST-HRMO');
            $mail->addAddress($email);

            // Content
            $mail->isHTML(true);
            $mail->Subject = 'Password Reset Verification Code';
            $mail->Body    = "You have requested a password reset. Your verification code is <strong>$verification_code</strong>. This code will expire in 15 minutes. <br>";

            $mail->send();
            header('Location: ../verify_code.php?status=Verification code sent');
        } catch (Exception $e) {
            // Handle email sending error
            header('Location: forgot_password.php?errors=Email could not be sent. Mailer Error: ' . $mail->ErrorInfo . '&input_data[email]=' . urlencode($email));
        }
    } else {
        // Handle database insertion error
        header('Location: forgot_password.php?errors=Failed to generate verification code&input_data[email]=' . urlencode($email));
    }

    $stmt->close();
    $mysqli->close();
} else {
    // If not POST request, redirect to the forgot password page
    header('Location: forgot_password.php');
    exit();
}
