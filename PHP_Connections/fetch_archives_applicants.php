<?php

// Start session
session_start();
include_once 'db_connection.php';

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    header('Location: ../login.php');
    exit();
}

// Function to format date
function formatDate($date)
{
    return date("F j, Y, g:i A", strtotime($date));
}

// Get user's username from session
$username = $_SESSION['username'];

// Fetch admin details from the database
$query = "SELECT name, username, email, profile_image FROM admins WHERE username = ?";
$stmt = $mysqli->prepare($query);
$stmt->bind_param('s', $username);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 1) {
    $admin = $result->fetch_assoc();
} else {
    echo "User not found.";
    exit();
}

// If the form is submitted, update the profile details
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];
    if (!empty($_FILES['profile_image']['name'])) {
        $profile_image = addslashes(file_get_contents($_FILES['profile_image']['tmp_name']));
    } else {
        $profile_image = $admin['profile_image'];
    }

    $update_query = "UPDATE admins SET name = ?, email = ?, profile_image = ? WHERE username = ?";
    $update_stmt = $mysqli->prepare($update_query);
    $update_stmt->bind_param('ssss', $name, $email, $profile_image, $username);
    if ($update_stmt->execute()) {
        $_SESSION['name'] = $name;
        $_SESSION['email'] = $email;
        $_SESSION['profile_image'] = $profile_image;
        echo "<script>window.addEventListener('load', function() { $('#successModal').modal('show'); });</script>";
    } else {
        echo "Error updating profile.";
    }
}

// Search input for applicants
$search_applicant = isset($_GET['search_applicant']) ? trim($_GET['search_applicant']) : '';

// Pagination parameters for Applicants
$applicants_limit = 10;
$applicants_page = isset($_GET['applicants_page']) ? intval($_GET['applicants_page']) : 1;
$applicants_offset = ($applicants_page - 1) * $applicants_limit;

// SQL query to search within applicant_archive with pagination
$query_applicant_archive = "
    SELECT applicantarchive_id, job_title, position_or_unit, plantilla, lastname, firstname, middlename, sex, address, email, contact_number, course, years_of_experience, hours_of_training, eligibility, list_of_awards, status, application_letter, personal_data_sheet, performance_rating, eligibility_rating_license, transcript_of_records, certificate_of_employment, proof_of_trainings_seminars, proof_of_rewards, job_id, application_date, interview_date, archived_by
    FROM applicant_archive
    WHERE job_title LIKE ? OR lastname LIKE ? OR firstname LIKE ?
    LIMIT ?, ?
";
$search_applicant_term = '%' . $search_applicant . '%';
$stmt_applicant = $mysqli->prepare($query_applicant_archive);
$stmt_applicant->bind_param('sssii', $search_applicant_term, $search_applicant_term, $search_applicant_term, $applicants_offset, $applicants_limit);
$stmt_applicant->execute();
$result_applicant_archive = $stmt_applicant->get_result();

// Get total number of matching applicants for pagination
$query_applicant_count = "
    SELECT COUNT(*) AS total
    FROM applicant_archive
    WHERE job_title LIKE ? OR lastname LIKE ? OR firstname LIKE ?
";
$stmt_count_applicant = $mysqli->prepare($query_applicant_count);
$stmt_count_applicant->bind_param('sss', $search_applicant_term, $search_applicant_term, $search_applicant_term);
$stmt_count_applicant->execute();
$result_applicant_count = $stmt_count_applicant->get_result();
$total_applicants = $result_applicant_count->fetch_assoc()['total'];
$total_pages_applicants = ceil($total_applicants / $applicants_limit);

?>
