<?php

session_start();
require('db_connection.php'); // Include your database connection file

// Function to generate a hash of the new password
function hashPassword($password)
{
    return password_hash($password, PASSWORD_BCRYPT);
}

$errors = [];

// Check if the form was submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate email from session
    if (!isset($_SESSION['reset_email'])) {
        $errors[] = 'Invalid or expired reset request';
    } else {
        $email = $_SESSION['reset_email'];
        unset($_SESSION['reset_email']); // Clear email from session after validation
    }

    $newPassword = isset($_POST['new_password']) ? trim($_POST['new_password']) : '';
    $confirmPassword = isset($_POST['confirm_password']) ? trim($_POST['confirm_password']) : '';

    // Validate input
    if (empty($newPassword) || empty($confirmPassword)) {
        $errors[] = 'Both fields are required';
    } elseif ($newPassword !== $confirmPassword) {
        $errors[] = 'Passwords do not match';
    }

    // If no errors, proceed with password reset
    if (empty($errors)) {
        // Prepare SQL statement to update the password
        $stmt = $mysqli->prepare("UPDATE admins SET password = ? WHERE email = ?");
        if ($stmt) {
            $hashedPassword = hashPassword($newPassword);
            $stmt->bind_param('ss', $hashedPassword, $email);
            if ($stmt->execute()) {
                $_SESSION['success_message'] = 'Password reset successfully';
                header('Location: ../login.php'); // Redirect to login page after success
                exit();
            } else {
                $errors[] = 'Database error: Unable to update password';
            }
            $stmt->close();
        } else {
            $errors[] = 'Database error: Unable to prepare statement';
        }
    }

    // Redirect back to the form with errors
    $queryString = http_build_query(['errors' => implode(', ', $errors)]);
    header('Location: ../resetPassword.php?' . $queryString);
    exit();
} else {
    // If the request method is not POST, redirect to the reset password page
    header('Location: ../resetPassword.php');
    exit();
}
