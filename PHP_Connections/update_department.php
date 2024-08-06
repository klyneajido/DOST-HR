<?php

// Include the database connection
include_once 'db_connection.php';

// Start session
session_start();

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    // Redirect to login page if not logged in
    header('Location: login.php');
    exit();
}

// Initialize variables
$errors = [];
$success = "";

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the department ID
    $department_id = isset($_POST['department_id']) ? intval($_POST['department_id']) : 0;

    // Get the form data
    $name = isset($_POST['name']) ? trim($_POST['name']) : '';
    $location = isset($_POST['location']) ? trim($_POST['location']) : '';

    // Validate the form data
    if (empty($name)) {
        $errors[] = "Department Name is required.";
    }
    if (empty($location)) {
        $errors[] = "Location is required.";
    }

    // If no errors, update the department
    if (empty($errors)) {
        $query = "UPDATE department SET name = ?, location = ? WHERE department_id = ?";
        $stmt = $mysqli->prepare($query);
        $stmt->bind_param('ssi', $name, $location, $department_id);

        if ($stmt->execute()) {
            $success = "Department updated successfully.";
            header('Location: ../departments.php');
            exit();
        } else {
            $errors[] = "Failed to update department.";
        }

        $stmt->close();
    }

    $mysqli->close();
}
