<?php

session_start();
include_once 'db_connection.php';

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit();
}
$user_name = $_SESSION['username'];
$profile_image_path = isset($_SESSION['profile_image']) ? $_SESSION['profile_image'] : 'assets/img/profiles/default-profile.png';

function formatDate($date)
{
    return date("F j, Y, g:i A", strtotime($date));
}

// Fetch job titles
$job_titles_query = "SELECT DISTINCT job_title FROM job WHERE status != 'archived'";
$job_titles_result = $mysqli->query($job_titles_query);
$job_titles = [];
while ($row = $job_titles_result->fetch_assoc()) {
    $job_titles[] = $row['job_title'];
}

// Fetch positions
$positions_query = "SELECT DISTINCT position_or_unit FROM job WHERE status != 'archived'";
$positions_result = $mysqli->query($positions_query);
$positions = [];
while ($row = $positions_result->fetch_assoc()) {
    $positions[] = $row['position_or_unit'];
}

// Fetch job statuses
$job_statuses_query = "SELECT DISTINCT status FROM job WHERE status != 'archived'";
$job_statuses_result = $mysqli->query($job_statuses_query);
$job_statuses = [];
while ($row = $job_statuses_result->fetch_assoc()) {
    $job_statuses[] = $row['status'];
}

// Get URL parameters
$search_query = isset($_GET['search']) ? mysqli_real_escape_string($mysqli, $_GET['search']) : '';
$job_title_filter = isset($_GET['job_title']) ? mysqli_real_escape_string($mysqli, $_GET['job_title']) : '';
$position_filter = isset($_GET['position']) ? mysqli_real_escape_string($mysqli, $_GET['position']) : '';
$status_filter = isset($_GET['status']) ? mysqli_real_escape_string($mysqli, $_GET['status']) : '';
$job_status_filter = isset($_GET['job_status']) ? mysqli_real_escape_string($mysqli, $_GET['job_status']) : '';

// Default rows per page
$default_rows_per_page = 10;
$page = isset($_GET['applicants_page']) ? intval($_GET['applicants_page']) : 1;
$rows_per_page = isset($_GET['rows_per_page']) ? intval($_GET['rows_per_page']) : $default_rows_per_page;
$page = max($page, 1);
$rows_per_page = max($rows_per_page, 1);
$offset = ($page - 1) * $rows_per_page;

// Query for total number of applicants with filters
$total_query = "SELECT COUNT(*) as total 
                FROM applicants a 
                LEFT JOIN job j ON a.job_id = j.job_id
                WHERE 
                    (a.lastname LIKE ? OR 
                     a.firstname LIKE ? OR 
                     a.email LIKE ? OR
                     a.job_title LIKE ? OR
                     a.position_or_unit LIKE ?)";

$params = array_fill(0, 5, "%$search_query%");

// Apply additional filters
if ($job_title_filter) {
    $total_query .= " AND a.job_title = ?";
    $params[] = $job_title_filter;
}
if ($position_filter) {
    $total_query .= " AND a.position_or_unit = ?";
    $params[] = $position_filter;
}
if ($status_filter) {
    $total_query .= " AND a.status = ?";
    $params[] = $status_filter;
}
if ($job_status_filter) {
    $total_query .= " AND j.status = ?";
    $params[] = $job_status_filter;
}

$stmt = $mysqli->prepare($total_query);
$types = str_repeat('s', count($params));
if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$total_result = $stmt->get_result();
$total_row = $total_result->fetch_assoc();
$total_applicants = $total_row['total'];
$total_pages = ceil($total_applicants / $rows_per_page);

// Query for applicants with filters, sorted by application date
$query = "SELECT a.id, a.lastname, a.firstname, a.middlename, a.plantilla, a.sex, a.address, a.email, a.contact_number, 
                 a.course, a.years_of_experience, a.hours_of_training, a.eligibility, a.list_of_awards, 
                 a.status, a.application_letter, a.personal_data_sheet, a.performance_rating, 
                 a.eligibility_rating_license, a.transcript_of_records, a.certificate_of_employment, 
                 a.proof_of_trainings_seminars, a.proof_of_rewards, a.job_title, a.position_or_unit, a.application_date, a.interview_date,
                 j.status as job_status
          FROM applicants a 
          LEFT JOIN job j ON a.job_id = j.job_id
          WHERE 
              (a.lastname LIKE ? OR 
               a.firstname LIKE ? OR 
               a.email LIKE ? OR
               a.job_title LIKE ? OR
               a.position_or_unit LIKE ?)";

$params = array_fill(0, 5, "%$search_query%");

// Apply additional filters
if ($job_title_filter) {
    $query .= " AND a.job_title = ?";
    $params[] = $job_title_filter;
}
if ($position_filter) {
    $query .= " AND a.position_or_unit = ?";
    $params[] = $position_filter;
}
if ($status_filter) {
    $query .= " AND a.status = ?";
    $params[] = $status_filter;
}
if ($job_status_filter) {
    $query .= " AND j.status = ?";
    $params[] = $job_status_filter;
}

// Order by application_date in descending order and apply pagination
$query .= " ORDER BY a.application_date DESC LIMIT ?, ?";
$params[] = $offset;
$params[] = $rows_per_page;

$stmt = $mysqli->prepare($query);
$types = str_repeat('s', count($params) - 2) . 'ii'; // Adjust for offset and limit
if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$result = $stmt->get_result();

$applicants = [];
while ($row = $result->fetch_assoc()) {
    $applicants[] = $row;
}
