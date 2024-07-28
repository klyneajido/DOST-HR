<?php
// Start session
session_start();

// Include the database connection file
require 'db_connection.php';

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    // Redirect to login page if not logged in
    header('Location: ../login.php');
    exit();
}

// Get user's name and profile image from session
$user_name = $_SESSION['username'];
$profile_image_path = isset($_SESSION['profile_image']) ? $_SESSION['profile_image'] : 'assets/img/profiles/default-profile.png';

// Initialize an array to store errors
$errors = [];
$success = '';

// Check if the form has been submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Retrieve form data
    $name = trim($_POST['name']);
    $location = trim($_POST['location']);

    // Validate form data
    if (empty($name)) {
        $errors[] = 'Department name is required.';
    }

    if (empty($location)) {
        $errors[] = 'Location is required.';
    }

    // If there are no errors, proceed with inserting the data into the database
    if (empty($errors)) {
        $stmt = $mysqli->prepare("INSERT INTO department (name, location) VALUES (?, ?)");

        // Check if the statement was prepared successfully
        if ($stmt === false) {
            $errors[] = 'Error preparing statement: ' . htmlspecialchars($mysqli->error);
        } else {
            $stmt->bind_param("ss", $name, $location);

            if ($stmt->execute()) {
                $success = 'Department added successfully.';
            } else {
                $errors[] = 'Failed to add department: ' . htmlspecialchars($stmt->error);
            }

            $stmt->close();
        }
    }
}
?>
