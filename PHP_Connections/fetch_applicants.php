<?php
session_start();
include_once 'db_connection.php';

// Check if the user is logged in
if (isset($_SESSION['username'])) {
    $user_name = $_SESSION['username'];
    $profile_image_path = isset($_SESSION['profile_image']) ? $_SESSION['profile_image'] : 'assets/img/profiles/default-profile.png';
} else {
    $user_name = 'Guest'; // Default value if user is not logged in
    $profile_image_path = 'assets/img/profiles/default-profile.png';
}
// Fetch job titles
$job_titles_query = "SELECT DISTINCT job_title FROM job";
$job_titles_result = $mysqli->query($job_titles_query);
$job_titles = [];
while ($row = $job_titles_result->fetch_assoc()) {
    $job_titles[] = $row['job_title'];
}

// Fetch positions
$positions_query = "SELECT DISTINCT position_or_unit FROM job";
$positions_result = $mysqli->query($positions_query);
$positions = [];
while ($row = $positions_result->fetch_assoc()) {
    $positions[] = $row['position_or_unit'];
}

// Get URL parameters
$search_query = isset($_GET['search']) ? mysqli_real_escape_string($mysqli, $_GET['search']) : '';
$job_title_filter = isset($_GET['job_title']) ? mysqli_real_escape_string($mysqli, $_GET['job_title']) : '';
$position_filter = isset($_GET['position']) ? mysqli_real_escape_string($mysqli, $_GET['position']) : '';

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
                    (a.lastname LIKE '%$search_query%' OR 
                     a.firstname LIKE '%$search_query%' OR 
                     a.email LIKE '%$search_query%' OR
                     j.job_title LIKE '%$search_query%' OR
                     j.position_or_unit LIKE '%$search_query%')";

if ($job_title_filter) {
    $total_query .= " AND j.job_title = '$job_title_filter'";
}
if ($position_filter) {
    $total_query .= " AND j.position_or_unit = '$position_filter'";
}

$total_result = $mysqli->query($total_query);
$total_row = $total_result->fetch_assoc();
$total_applicants = $total_row['total'];
$total_pages = ceil($total_applicants / $rows_per_page);

// Query for applicants with filters
$query = "SELECT a.id, a.lastname, a.firstname, a.middlename, a.sex, a.address, a.email, a.contact_number, 
                 a.course, a.years_of_experience, a.hours_of_training, a.eligibility, a.list_of_awards, 
                 a.status, a.application_letter, a.personal_data_sheet, a.performance_rating, 
                 a.eligibility_rating_license, a.transcript_of_records, a.certificate_of_employment, 
                 a.proof_of_trainings_seminars, a.proof_of_rewards, j.job_title, j.position_or_unit
          FROM applicants a 
          LEFT JOIN job j ON a.job_id = j.job_id
          WHERE 
              (a.lastname LIKE '%$search_query%' OR 
               a.firstname LIKE '%$search_query%' OR 
               a.email LIKE '%$search_query%' OR
               j.job_title LIKE '%$search_query%' OR
               j.position_or_unit LIKE '%$search_query%')";

if ($job_title_filter) {
    $query .= " AND j.job_title = '$job_title_filter'";
}
if ($position_filter) {
    $query .= " AND j.position_or_unit = '$position_filter'";
}

$query .= " LIMIT $offset, $rows_per_page";

$result = $mysqli->query($query);

$applicants = [];
while ($row = $result->fetch_assoc()) {
    $applicants[] = $row;
}
?>
