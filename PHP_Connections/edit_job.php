<?php
session_start();
include_once 'db_connection.php';

if (!isset($_SESSION['username'])) {
    header('Location: ../login.php');
    exit();
}
$user_name = isset($_SESSION['username']) ? $_SESSION['username'] : 'Guest';
$profile_image_path = isset($_SESSION['profile_image']) ? $_SESSION['profile_image'] : 'assets/img/profiles/default-profile.png';

$job_id = isset($_GET['job_id']) ? (int)$_GET['job_id'] : 0;

if ($job_id === 0) {
    header('Location: viewJob.php');
    exit();
}

$sql = "SELECT * FROM job WHERE job_id = ?";
$stmt = $mysqli->prepare($sql);
$stmt->bind_param('i', $job_id);
$stmt->execute();
$result = $stmt->get_result();
$job = $result->fetch_assoc();

if (!$job) {
    header('Location: viewJob.php');
    exit();
}

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

if ($_SERVER["REQUEST_METHOD"] == "POST") {
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

    if (empty($errors)) {
        $sql = "UPDATE job SET job_title = ?, position_or_unit = ?, department_id = ?, salary = ?, status = ?, description = ?, education_requirement = ?, experience_or_training = ?, duties_and_responsibilities = ?, place_of_assignment = ?, deadline = ?, updated_at = NOW()  WHERE job_id = ?";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param('ssidsssssssi',$job_title,  $position, $department_id, $monthly_salary, $status, $description, $educationrequirement, $experienceortraining, $dutiesandresponsibilities, $placeofassignment, $deadline, $job_id);

        if ($stmt->execute()) {
            header('Location: viewJob.php?success=Job updated successfully');
            exit();
        } else {
            $errors['database'] = "Error updating job: " . $mysqli->error;
        }
    }
}
?>