<?php
// Start session
session_start();

// Include database connection file
include_once 'db_connection.php'; // Ensure this file exists and contains the MySQL connection code

// Debugging: Check if $mysqli is set
if (!isset($mysqli)) {
    die('Database connection failed. $mysqli is not set.');
}

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get username and password from form POST data
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Validate username and password (You can add more validation as per your requirement)

    // Query to check if the username and password exist in the database
    $query = "SELECT * FROM admins WHERE username = ? AND password = ?";

    // Prepare the query
    $stmt = $mysqli->prepare($query);

    if ($stmt === false) {
        die('MySQL prepare error: ' . htmlspecialchars($mysqli->error));
    }

    // Bind parameters
    $stmt->bind_param('ss', $username, $password);

    // Execute the query
    if (!$stmt->execute()) {
        die('Execute failed: ' . htmlspecialchars($stmt->error));
    }

    // Store the result
    $result = $stmt->get_result();

    // Check if the user exists in the database
    if ($result->num_rows == 1) {
        // Authentication successful, set session variables
        $_SESSION['username'] = $username;

        // Redirect to dashboard or any other page
        header('Location: index.php');
        exit();
    } else {
        // Authentication failed, redirect back to login page with error message
        header('Location: login.html?error=login_failed');
        exit();
    }
} else {
    // Redirect back to login page if accessed directly
    header('Location: login.html');
    exit();
}

