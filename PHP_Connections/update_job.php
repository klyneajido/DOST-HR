<?php

session_start();
include_once 'db_connection.php';

if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit();
}

$user_name = isset($_SESSION['username']) ? $_SESSION['username'] : 'Guest';
$profile_image_path = isset($_SESSION['profile_image']) ? $_SESSION['profile_image'] : 'assets/img/profiles/default-profile.png';

// Fetch user ID based on username
$user_query = "SELECT admin_id FROM admins WHERE username = ?";
$stmt_user = $mysqli->prepare($user_query);
$stmt_user->bind_param('s', $user_name);
$stmt_user->execute();
$result_user = $stmt_user->get_result();

if ($result_user->num_rows > 0) {
    $user = $result_user->fetch_assoc();
    $user_id = $user['admin_id'];
} else {
    header('Location: view_jobs.php?error=User not found.');
    exit();
}

$job_id = isset($_GET['job_id']) ? (int)$_GET['job_id'] : 0;

if ($job_id === 0) {
    header('Location: view_jobs.php');
    exit();
}

// Fetch existing job details
$query_job = "
    SELECT job_title, position_or_unit, description, department_id, salary, place_of_assignment, status, deadline
    FROM job
    WHERE job_id = ?
";
$stmt_job = $mysqli->prepare($query_job);
$stmt_job->bind_param('i', $job_id);
$stmt_job->execute();
$result_job = $stmt_job->get_result();

if ($result_job->num_rows === 1) {
    $job = $result_job->fetch_assoc();
} else {
    header('Location: view_jobs.php?error=Job not found.');
    exit();
}

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

// Fetch existing requirements for the job
$query_requirements = "
    SELECT requirement_type, requirement_text
    FROM job_requirements
    WHERE job_id = ?
";
$stmt_requirements = $mysqli->prepare($query_requirements);
$stmt_requirements->bind_param('i', $job_id);
$stmt_requirements->execute();
$result_requirements = $stmt_requirements->get_result();

$requirements = [
    'education' => [],
    'experience' => [],
    'duties' => []
];

while ($row = $result_requirements->fetch_assoc()) {
    $requirements[$row['requirement_type']][] = $row['requirement_text'];
}

$stmt_requirements->close();

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
    $monthly_salary = $_POST['salary'] ?? '';
    $status = $_POST['status'] ?? '';
    $deadline = $_POST['deadline'] ?? '';
    $description = $_POST['description'] ?? '';

    // Filter out empty values from arrays
    $education_requirement = array_filter($education_requirement, fn ($value) => !empty(trim($value)));
    $experience = array_filter($experience, fn ($value) => !empty(trim($value)));
    $training = array_filter($training, fn ($value) => !empty(trim($value)));
    $eligibility = array_filter($eligibility, fn ($value) => !empty(trim($value)));
    $duties_and_responsibilities = array_filter($duties_and_responsibilities, fn ($value) => !empty(trim($value)));

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
    if (empty($monthly_salary)) {
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
    if (empty($duties_and_responsibilities)) {
        $errors['duties_and_responsibilities'] = "At least one duty or responsibility is required";
    }
    if (empty($competencies)) {
        $errors['competencies'] = "At least one preferred competency requirement is required";
    }

    if (empty($errors)) {
        // Update job details
        $stmt = $mysqli->prepare("
            UPDATE job
            SET job_title = ?, position_or_unit = ?, description = ?, department_id = ?, salary = ?, place_of_assignment = ?, status = ?, deadline = ?, updated_at = NOW()
            WHERE job_id = ?
        ");
        $stmt->bind_param("ssssssssi", $job_title, $position, $description, $department_id, $monthly_salary, $place_of_assignment, $status, $deadline, $job_id);

        if ($stmt->execute()) {
            // Delete existing requirements
            $stmt_delete = $mysqli->prepare("DELETE FROM job_requirements WHERE job_id = ?");
            $stmt_delete->bind_param('i', $job_id);
            $stmt_delete->execute();
            $stmt_delete->close();

            // Insert updated requirements
            foreach ($education_requirement as $requirement) {
                $stmt_req = $mysqli->prepare("INSERT INTO job_requirements (job_id, requirement_text, requirement_type) VALUES (?, ?, 'education')");
                $stmt_req->bind_param("is", $job_id, $requirement);
                $stmt_req->execute();
                $stmt_req->close();
            }

            foreach ($experience as $requirement) {
                $stmt_req = $mysqli->prepare("INSERT INTO job_requirements (job_id, requirement_text, requirement_type) VALUES (?, ?, 'experience')");
                $stmt_req->bind_param("is", $job_id, $requirement);
                $stmt_req->execute();
                $stmt_req->close();
            }

            foreach ($training as $requirement) {
                $stmt_req = $mysqli->prepare("INSERT INTO job_requirements (job_id, requirement_text, requirement_type) VALUES (?, ?, 'training')");
                $stmt_req->bind_param("is", $job_id, $requirement);
                $stmt_req->execute();
                $stmt_req->close();
            }

            foreach ($eligibility as $requirement) {
                $stmt_req = $mysqli->prepare("INSERT INTO job_requirements (job_id, requirement_text, requirement_type) VALUES (?, ?, 'eligibility')");
                $stmt_req->bind_param("is", $job_id, $requirement);
                $stmt_req->execute();
                $stmt_req->close();
            }

            foreach ($duties_and_responsibilities as $requirement) {
                $stmt_req = $mysqli->prepare("INSERT INTO job_requirements (job_id, requirement_text, requirement_type) VALUES (?, ?, 'duties')");
                $stmt_req->bind_param("is", $job_id, $requirement);
                $stmt_req->execute();
                $stmt_req->close();
            }

            foreach ($competencies as $requirement) {
                $stmt_req = $mysqli->prepare("INSERT INTO job_requirements (job_id, requirement_text, requirement_type) VALUES (?, ?, 'competencies')");
                $stmt_req->bind_param("is", $job_id, $requirement);
                $stmt_req->execute();
                $stmt_req->close();
            }

            // Record action in the history table
            $history_stmt = $mysqli->prepare("
                INSERT INTO history (action, details, user_id, date)
                VALUES (?, ?, ?, NOW())
            ");
            $action = "Updated Job";
            $details = "Job Title: $job_title $position";
            $history_stmt->bind_param("ssi", $action, $details, $user_id);
            $history_stmt->execute();
            $history_stmt->close();

            $success = "Job updated successfully with requirements.";
            header('Location: job_details.php?job_id=' . $job_id);
            exit();
        } else {
            $errors['database'] = "Error updating job: " . $stmt->error;
        }

        $stmt->close();
    }
}

$mysqli->close();
?>
