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
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data from URL parameters
    $job_title = $_POST['job_title'];
    $position = $_POST['position'];
    $department_id = $_POST['department_id'];
    $experienceortraining = $_POST['experienceortraining'];
    $dutiesandresponsibilities = $_POST['dutiesandresponsibilities'];
    $educationrequirement = $_POST['educreq'];
    $placeofassignment = $_POST['poa'];
    $department_id = $_POST['department_id'];
    $monthly_salary = $_POST['monthlysalary'];
    $status = $_POST['status'];
    $deadline = $_POST['deadline'];
    $description= $_POST['description'];

    if (empty($job_title)) {
        $errors['job_title'] = "Position is required";
    }
    if(empty($description)) {
        $errors['description'] = "Description is required";
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
    if (empty($deadline)) {
        $errors['deadline'] = "Deadline is required";
    }
    if (empty($educationrequirement)) {
        $errors['educreq'] = "Educational Requirement is required";
    }
    if (empty($experienceortraining)) {
        $errors['experienceortraining'] = "Experience or Training is required";
    }

    // If no errors, insert data into job table
    if (empty($errors)) {
        $stmt = $mysqli->prepare("INSERT INTO job (job_title, position_or_unit, description, education_requirement, experience_or_training, duties_and_responsibilities, department_id, salary, place_of_assignment, status, deadline, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())");
        $stmt->bind_param("sisssisdsi",$job_title, $position, $description, $educationrequirement, $experienceortraining,$dutiesandresponsibilities, $department_id, $monthly_salary, $placeofassignment, $status, $deadline );

        if ($stmt->execute()) {
            // Redirect back to the form page with a success message
            header('Location: viewJob.php?success=Job added successfully');
            exit();
        } else {
            $errors['database'] = "Error adding job: " . $mysqli->error;
            echo('SUCCESS');
            // Redirect back to the form page with error messages
            header('Location: viewJob.php?' . http_build_query($errors));
            exit();
        }
    } else {
        echo('ERRORRRRRRRRR');
        // Redirect back to the form page with error messages
        header('Location: viewJob.php?' . http_build_query($errors));
        exit();
    }
} else {
    echo('ERRORRRRRRRRRRRRRRRRRRRRRRRRRRRRRR');
    
    exit();
}
