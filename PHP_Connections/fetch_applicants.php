<?php
session_start();
include_once 'PHP_Connections/db_connection.php';

if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit();
}

$user_name = isset($_SESSION['username']) ? $_SESSION['username'] : 'Guest';
$profile_image_path = isset($_SESSION['profile_image']) ? $_SESSION['profile_image'] : 'assets/img/profiles/default-profile.png';

$query = "SELECT a.id, a.lastname, a.firstname, a.middlename, a.sex, a.address, a.email, a.contact_number, a.course, a.years_of_experience, a.hours_of_training, a.eligibility, a.list_of_awards, a.status, 
                 a.application_letter, a.personal_data_sheet, a.performance_rating, a.eligibility_rating_license, 
                 a.transcript_of_records, a.certificate_of_employment, a.proof_of_trainings_seminars, 
                 a.proof_of_rewards, CONCAT(j.job_title, ' ', j.position_or_unit) AS job_title
          FROM applicants a 
          LEFT JOIN job j ON a.job_id = j.job_id";
$result = mysqli_query($mysqli, $query);

$applicants = [];
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $applicants[] = $row;
    }
}
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $applicantId = $_POST['id'];

    // Prevent SQL injection
    $applicantId = mysqli_real_escape_string($mysqli, $applicantId);

    // Perform deletion query
    $deleteQuery = "DELETE FROM applicants WHERE id = '$applicantId'";

    if (mysqli_query($mysqli, $deleteQuery)) {
        echo "Applicant deleted successfully!";
    } else {
        echo "Error deleting applicant: " . mysqli_error($mysqli);
    }
} else {
    echo "Invalid request. Please provide an applicant ID.";
}
?>

