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
function formatDate($date) {
    return date("F j, Y, g:i A", strtotime($date));
}
function formatDateDeadline($date) {
    // Set the fixed time to 5:00 PM
    $fixed_time = '17:00:00'; // 5:00 PM in 24-hour format

    // Combine the provided date with the fixed time
    $datetime = $date . ' ' . $fixed_time;

    // Convert the combined datetime string to a timestamp and format it
    return date("F j, Y, g:i A", strtotime($datetime));
}

// Get user's name from session
$user_name = isset($_SESSION['username']) ? $_SESSION['username'] : 'Guest';
$profile_image_path = isset($_SESSION['profile_image']) ? $_SESSION['profile_image'] : 'assets/img/profiles/default-profile.png';

// Check if search query is set
$search = isset($_GET['search']) ? $mysqli->real_escape_string($_GET['search']) : '';

// Prepare SQL query
$sql = "SELECT j.job_id, j.job_title, j.position_or_unit, j.description, d.name as department_name, j.place_of_assignment, d.abbrev, j.salary, j.status, j.created_at, j.updated_at, j.deadline 
        FROM job j
        INNER JOIN department d ON j.department_id = d.department_id";

if (!empty($search)) {
    $sql .= " WHERE j.job_title LIKE '%$search%' OR d.name LIKE '%$search%' OR d.abbrev LIKE '%$search%'";
}

$result = $mysqli->query($sql);

// Initialize an empty array to store jobs data
$jobs = [];

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $jobs[] = $row;
    }
} else {
    $errors['database'] = "No jobs found.";
}

// Get job ID from query string
$job_id = isset($_GET['job_id']) ? intval($_GET['job_id']) : 0;

if ($job_id <= 0) {
    die('Invalid job ID.');
}

// Prepare SQL query to fetch job details
$sql = "SELECT j.job_id, j.job_title, j.position_or_unit, j.description, d.name as department_name, j.place_of_assignment, d.abbrev, j.salary, j.status, j.created_at, j.updated_at, j.deadline 
        FROM job j
        INNER JOIN department d ON j.department_id = d.department_id
        WHERE j.job_id = ?";

$stmt = $mysqli->prepare($sql);
$stmt->bind_param('i', $job_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die('Job not found.');
}

$job = $result->fetch_assoc();

// Fetch job requirements
$sql = "SELECT requirement_text, requirement_type FROM job_requirements WHERE job_id = ?";
$stmt = $mysqli->prepare($sql);
$stmt->bind_param('i', $job_id);
$stmt->execute();
$requirements_result = $stmt->get_result();

$requirements = [];
while ($req = $requirements_result->fetch_assoc()) {
    $requirements[$req['requirement_type']][] = $req['requirement_text'];
}