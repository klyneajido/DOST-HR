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

// Get user's name from session
$user_name = isset($_SESSION['username']) ? $_SESSION['username'] : 'Guest';
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
    $job_title = $_POST['job_title'];
    $position = $_POST['position'];
    $department_id = $_POST['department_id'];
    $education_requirement = isset($_POST['educreq']) ? $_POST['educreq'] : [];
    $experience_or_training = isset($_POST['experienceortraining']) ? $_POST['experienceortraining'] : [];
    $duties_and_responsibilities = isset($_POST['dutiesandresponsibilities']) ? $_POST['dutiesandresponsibilities'] : [];
    $place_of_assignment = $_POST['poa'];
    $monthly_salary = $_POST['monthlysalary'];
    $status = $_POST['status'];
    $deadline = $_POST['deadline'];
    $description = $_POST['description'];

    // Validate inputs
    if (empty($job_title)) $errors['job_title'] = "Job Title is required";
    if (empty($description)) $errors['description'] = "Description is required";
    if (empty($department_id)) $errors['department_id'] = "Department is required";
    if (empty($monthly_salary)) $errors['monthlysalary'] = "Monthly Salary is required";
    if (empty($status)) $errors['status'] = "Status is required";
    if (empty($deadline)) $errors['deadline'] = "Deadline is required";
    if (empty($education_requirement)) $errors['education_requirement'] = "At least one educational requirement is required";
    if (empty($experience_or_training)) $errors['experience_or_training'] = "At least one experience or training requirement is required";
    if (empty($duties_and_responsibilities)) $errors['duties_and_responsibilities'] = "At least one duty or responsibility is required";

    if (empty($errors)) {
        // Insert job details into the job table
        $stmt = $mysqli->prepare("INSERT INTO job (job_title, position_or_unit, description, department_id, salary, place_of_assignment, status, deadline, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW())");
        $stmt->bind_param("ssssssss", $job_title, $position, $description, $department_id, $monthly_salary, $place_of_assignment, $status, $deadline);
    
        if ($stmt->execute()) {
            $job_id = $stmt->insert_id; // Get the ID of the inserted job
    
            // Insert education requirements
            foreach ($education_requirement as $requirement) {
                $stmt_req = $mysqli->prepare("INSERT INTO job_requirements (job_id, requirement_text, requirement_type) VALUES (?, ?, 'education')");
                $stmt_req->bind_param("is", $job_id, $requirement);
                $stmt_req->execute();
                $stmt_req->close();
            }
    
            // Insert experience or training requirements
            foreach ($experience_or_training as $requirement) {
                $stmt_req = $mysqli->prepare("INSERT INTO job_requirements (job_id, requirement_text, requirement_type) VALUES (?, ?, 'experience')");
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
    
            // Aggregate requirements and update job table
            $requirements_stmt = $mysqli->prepare("
                SELECT requirement_text, requirement_type 
                FROM job_requirements 
                WHERE job_id = ?
            ");
            $requirements_stmt->bind_param("i", $job_id);
            $requirements_stmt->execute();
            $requirements_result = $requirements_stmt->get_result();

            $education_requirement = [];
            $experience_requirements = [];
            $duties_and_responsibilities = [];

            while ($row = $requirements_result->fetch_assoc()) {
                switch ($row['requirement_type']) {
                    case 'education':
                        $education_requirement[] = $row['requirement_text'];
                        break;
                    case 'experience':
                        $experience_requirements[] = $row['requirement_text'];
                        break;
                    case 'duties':
                        $duties_and_responsibilities[] = $row['requirement_text'];
                        break;
                }
            }

            // Convert arrays to strings
            $education_requirement_list = implode("\n", $education_requirement);
            $experience_requirements_list = implode("\n", $experience_requirements);
            $duties_and_responsibilities_list = implode("\n", $duties_and_responsibilities);

            // Update the job table with aggregated requirements
            $update_stmt = $mysqli->prepare("
                UPDATE job 
                SET education_requirement = ?, 
                    experience_or_training = ?, 
                    duties_and_responsibilities = ?
                WHERE job_id = ?
            ");
            $update_stmt->bind_param("sssi", $education_requirement_list, $experience_requirements_list, $duties_and_responsibilities_list, $job_id);

            if ($update_stmt->execute()) {
                $success = "Job added successfully with requirements.";
            } else {
                $errors['database'] = "Error updating job: " . $update_stmt->error;
            }

            $update_stmt->close();
            $requirements_stmt->close();

            header('Location: ./viewJob.php');
        } else {
            $errors['database'] = "Error adding job: " . $stmt->error;
        }
    
        $stmt->close();
    }
}

$mysqli->close();
?>
