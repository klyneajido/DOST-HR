<?php
session_start();
include_once 'PHP_Connections/db_connection.php';

if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit();
}

$user_name = isset($_SESSION['username']) ? $_SESSION['username'] : 'Guest';
$profile_image_path = isset($_SESSION['profile_image']) ? $_SESSION['profile_image'] : 'assets/img/profiles/default-profile.png';

// Default rows per page
$default_rows_per_page = 10;

// Get current page number and rows per page
$page = isset($_GET['applicants_page']) ? intval($_GET['applicants_page']) : 1;
$rows_per_page = isset($_GET['rows_per_page']) ? intval($_GET['rows_per_page']) : $default_rows_per_page;

// Sanitize page and rows per page
$page = max($page, 1);
$rows_per_page = max($rows_per_page, 1);

// Calculate offset
$offset = ($page - 1) * $rows_per_page;

// Query for total number of applicants
$total_query = "SELECT COUNT(*) as total FROM applicants";
$total_result = $mysqli->query($total_query);
$total_row = $total_result->fetch_assoc();
$total_applicants = $total_row['total'];
$total_pages = ceil($total_applicants / $rows_per_page);

// Query for applicants
$query = "SELECT a.id, a.lastname, a.firstname, a.middlename, a.sex, a.address, a.email, a.contact_number, a.course, a.years_of_experience, a.hours_of_training, a.eligibility, a.list_of_awards, a.status, 
                 a.application_letter, a.personal_data_sheet, a.performance_rating, a.eligibility_rating_license, 
                 a.transcript_of_records, a.certificate_of_employment, a.proof_of_trainings_seminars, 
                 a.proof_of_rewards, CONCAT(j.job_title, ' ', j.position_or_unit) AS job_title
          FROM applicants a 
          LEFT JOIN job j ON a.job_id = j.job_id
          LIMIT $offset, $rows_per_page";
$result = $mysqli->query($query);

$applicants = [];
while ($row = $result->fetch_assoc()) {
    $applicants[] = $row;
}
?>
