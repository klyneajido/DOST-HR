<?php

// Start session
session_start();
include_once 'db_connection.php';

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    // Redirect to login page if not logged in
    header('Location: login.php');
    exit();
}

// Get user's name and profile image from session
$user_name = $_SESSION['username'];
$profile_image_path = isset($_SESSION['profile_image']) ? $_SESSION['profile_image'] : 'assets/img/profiles/default-profile.png';

// Get departments for the dropdown
$sql = "SELECT department_id, name FROM department";
$result = $mysqli->query($sql);

$departments = [];
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $departments[] = $row;
    }
} else {
    echo "Error retrieving departments: " . $mysqli->error;
}

$errors = [];
$success = "";

// Check if form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $job_title = $_POST['job_title'] ?? '';
    $position = $_POST['position'] ?? '';
    $department_id = $_POST['department_id'] ?? '';
    $education_requirement = $_POST['educationrequirement'] ?? [];
    $experience = $_POST['experience'] ?? [];
    $training = $_POST['training'] ?? [];
    $eligibility = $_POST['eligibility'] ?? [];
    $duties_and_responsibilities = $_POST['dutiesandresponsibilities'] ?? [];
    $competencies = $_POST['competencies'] ?? [];
    $place_of_assignment = $_POST['poa'] ?? '';
    $salary = $_POST['salary'] ?? '';
    $status = $_POST['status'] ?? '';
    $deadline = $_POST['deadline'] ?? '';
    $description = $_POST['description'] ?? '';

    // Filter out empty values from arrays
    $education_requirement = array_filter($education_requirement, fn ($value) => !empty(trim($value)));
    $experience = array_filter($experience, fn ($value) => !empty(trim($value)));
    $training = array_filter($training, fn ($value) => !empty(trim($value)));
    $eligibility = array_filter($eligibility, fn ($value) => !empty(trim($value)));
    $duties_and_responsibilities = array_filter($duties_and_responsibilities, fn ($value) => !empty(trim($value)));
    $competencies = array_filter($competencies, fn ($value) => !empty(trim($value)));

    // Validate inputs
    if (empty($job_title)) {
        $errors['job_title'] = "Job Title is required";
    }
    if (empty($description)) {
        $errors['description'] = "Description is required";
    }
    if (empty($department_id)) {
        $errors['department_id'] = "Department is required";
    }
    if (empty($salary)) {
        $errors['salary'] = "Monthly Salary is required";
    }
    if (empty($status)) {
        $errors['status'] = "Status is required";
    }
    if (empty($deadline)) {
        $errors['deadline'] = "Deadline is required";
    }
    if (empty($education_requirement)) {
        $errors['education_requirement'] = "At least one educational requirement is required";
    }
    if (empty($experience)) {
        $errors['experience'] = "At least one experience requirement is required";
    }
    if (empty($training)) {
        $errors['training'] = "At least one training requirement is required";
    }
    if (empty($eligibility)) {
        $errors['eligibility'] = "At least one eligibility requirement is required";
    }
    if (empty($duties_and_responsibilities)) {
        $errors['duties_and_responsibilities'] = "At least one duty or responsibility is required";
    }
    if (empty($competencies)) {
        $errors['competencies'] = "At least one competency requirement is required";
    }

    if (empty($errors)) {
        // Insert job details into the job table
        $stmt = $mysqli->prepare("INSERT INTO job (job_title, position_or_unit, description, department_id, salary, place_of_assignment, status, deadline, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW())");
        $stmt->bind_param("ssssssss", $job_title, $position, $description, $department_id, $salary, $place_of_assignment, $status, $deadline);

        if ($stmt->execute()) {
            $job_id = $stmt->insert_id; // Get the ID of the inserted job

            // Insert education requirements
            foreach ($education_requirement as $requirement) {
                $stmt_req = $mysqli->prepare("INSERT INTO job_requirements (job_id, requirement_text, requirement_type) VALUES (?, ?, 'education')");
                $stmt_req->bind_param("is", $job_id, $requirement);
                $stmt_req->execute();
                $stmt_req->close();
            }

            // Insert experience requirements
            foreach ($experience as $requirement) {
                $stmt_req = $mysqli->prepare("INSERT INTO job_requirements (job_id, requirement_text, requirement_type) VALUES (?, ?, 'experience')");
                $stmt_req->bind_param("is", $job_id, $requirement);
                $stmt_req->execute();
                $stmt_req->close();
            }

            // Insert training requirements
            foreach ($training as $requirement) {
                $stmt_req = $mysqli->prepare("INSERT INTO job_requirements (job_id, requirement_text, requirement_type) VALUES (?, ?, 'training')");
                $stmt_req->bind_param("is", $job_id, $requirement);
                $stmt_req->execute();
                $stmt_req->close();
            }

            // Insert eligibility requirements
            foreach ($eligibility as $requirement) {
                $stmt_req = $mysqli->prepare("INSERT INTO job_requirements (job_id, requirement_text, requirement_type) VALUES (?, ?, 'eligibility')");
                $stmt_req->bind_param("is", $job_id, $requirement);
                $stmt_req->execute();
                $stmt_req->close();
            }

            // Insert duties and responsibilities
            foreach ($duties_and_responsibilities as $requirement) {
                $stmt_req = $mysqli->prepare("INSERT INTO job_requirements (job_id, requirement_text, requirement_type) VALUES (?, ?, 'duties')");
                $stmt_req->bind_param("is", $job_id, $requirement);
                $stmt_req->execute();
                $stmt_req->close();
            }

            // Insert competencies requirements
            foreach ($competencies as $requirement) {
                $stmt_req = $mysqli->prepare("INSERT INTO job_requirements (job_id, requirement_text, requirement_type) VALUES (?, ?, 'competencies')");
                $stmt_req->bind_param("is", $job_id, $requirement);
                $stmt_req->execute();
                $stmt_req->close();
            }

            // Record action in the history table
            $history_stmt = $mysqli->prepare("
                INSERT INTO history (action, details, user_id, date) 
                VALUES (?, ?, (SELECT admin_id FROM admins WHERE username = ?), NOW())
            ");
            $action = "Added New Job";
            $details = "Job Title: $job_title $position";
            $history_stmt->bind_param("sss", $action, $details, $user_name);
            $history_stmt->execute();
            $history_stmt->close();

            $success = "Job added successfully with requirements.";
            header('Location: ./viewJob.php');
            exit();
        } else {
            $errors['database'] = "Error adding job: " . $stmt->error;
        }

        $stmt->close();
    }
}

$mysqli->close();
