<?php
// Start session
session_start();
include_once 'PHP_Connections/db_connection.php';

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    // Redirect to login page if not logged in
    header('Location: login.php');
    exit();
}
$user_name = isset($_SESSION['username']) ? $_SESSION['username'] : 'Guest';
$profile_image_path = isset($_SESSION['profile_image']) ? $_SESSION['profile_image'] : 'assets/img/profiles/default-profile.png';
// Initialize variables for error messages
$errors = [];

// Check if form was submitted
if ($_SERVER["REQUEST_METHOD"] == "GET") {
    // Get form data from URL parameters
    $position = $_GET['position'];
    $department_id = $_GET['department_id'];
    $monthly_salary = $_GET['monthlysalary'];
    $status = $_GET['status'];

    // Validate form data
    if (empty($position)) {
        $errors['position'] = "Position is required";
    }
    if (empty($department_id)) {
        $errors['department_id'] = "Department is required";
    }
    if (empty($monthly_salary)) {
        $errors['monthlysalary'] = "Monthly Salary is required";
    }
    if (empty($status)) {
        $errors['status'] = "Status is required";
    }

    // If no errors, insert data into job table
    if (empty($errors)) {
        $stmt = $mysqli->prepare("INSERT INTO job (position,  department_id, monthlysalary, status) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("sids", $position, $department_id, $monthly_salary, $status);

        if ($stmt->execute()) {
            // Redirect back to the form page with a success message
            header('Location: viewJob.php?success=Job added successfully');
            exit();
        } else {
            $errors['database'] = "Error adding job: " . $mysqli->error;
            // Redirect back to the form page with error messages
            header('Location: viewJob.php?' . http_build_query($errors));
            exit();
        }
    } else {
        // Redirect back to the form page with error messages
        header('Location: viewJob.php?' . http_build_query($errors));
        exit();
    }
} else {
    header('Location: viewJob.php');
    exit();
}
