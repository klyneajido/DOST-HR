<?php
// Start session
session_start();
include_once 'db_connection.php';

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    // Redirect to login page if not logged in
    header('Location: ../login.php');
    exit();
}

// Get user's username from session
$username = $_SESSION['username'] ?? 'Guest';

// Fetch user ID based on username
$user_query = "SELECT admin_id FROM admins WHERE username = ?";
$stmt_user = $mysqli->prepare($user_query);
$stmt_user->bind_param('s', $username);
$stmt_user->execute();
$result_user = $stmt_user->get_result();

if ($result_user->num_rows > 0) {
    $user = $result_user->fetch_assoc();
    $user_id = $user['admin_id'];
} else {
    // Redirect to applicants page with error message if user is not found
    header('Location: ../applicants.php?error=User not found.');
    exit();
}

// Check if applicant_id is set
if (!isset($_POST['applicant_id'])) {
    // Redirect to applicants page if applicant_id is not set
    header('Location: ../applicants.php');
    exit();
}

// Get applicant_id from the POST request
$applicant_id = $mysqli->real_escape_string($_POST['applicant_id']);

// Fetch the applicant to be archived
$query = "SELECT * FROM applicants WHERE id = ?";
$stmt = $mysqli->prepare($query);
$stmt->bind_param('i', $applicant_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result && $result->num_rows > 0) {
    $applicant = $result->fetch_assoc();

    // Prepare the archive query
$archive_query = "INSERT INTO applicant_archive 
    (job_title, position_or_unit, lastname, firstname, middlename, sex, address, email, contact_number, course, years_of_experience, hours_of_training, eligibility, list_of_awards, status, application_letter, personal_data_sheet, performance_rating, eligibility_rating_license, transcript_of_records, certificate_of_employment, proof_of_trainings_seminars, proof_of_rewards, job_id, application_date, interview_date, archived_by) 
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt_archive = $mysqli->prepare($archive_query);
    if (!$stmt_archive) {
        die('Prepare failed: ' . htmlspecialchars($mysqli->error));
    }

    // Bind parameters
    $stmt_archive->bind_param(
        'sssssssssssssssssssssssisss',
        $applicant['job_title'],
        $applicant['position_or_unit'],
        $applicant['lastname'],
        $applicant['firstname'],
        $applicant['middlename'],
        $applicant['sex'],
        $applicant['address'],
        $applicant['email'],
        $applicant['contact_number'],
        $applicant['course'],
        $applicant['years_of_experience'],
        $applicant['hours_of_training'],
        $applicant['eligibility'],
        $applicant['list_of_awards'],
        $applicant['status'],
        $applicant['application_letter'], // BLOB
        $applicant['personal_data_sheet'], // BLOB
        $applicant['performance_rating'], // BLOB
        $applicant['eligibility_rating_license'], // BLOB
        $applicant['transcript_of_records'], // BLOB
        $applicant['certificate_of_employment'], // BLOB
        $applicant['proof_of_trainings_seminars'], // BLOB
        $applicant['proof_of_rewards'], // BLOB
        $applicant['job_id'], // Integer
        $applicant['application_date'],
        $applicant['interview_date'],
        $username // String
    );

    if (!$stmt_archive->execute()) {
        die('Execute failed: ' . htmlspecialchars($stmt_archive->error));
    }

    // Log the action in the history table
    $action = "Archived Applicant";
    $details = "Applicant name: " . $applicant['firstname'] . " " . $applicant['lastname'];
    $log_query = "INSERT INTO history (user_id, action, details, date) VALUES (?, ?, ?, NOW())";
    $log_stmt = $mysqli->prepare($log_query);
    if (!$log_stmt) {
        die('Prepare failed: ' . htmlspecialchars($mysqli->error));
    }

    $log_stmt->bind_param('iss', $user_id, $action, $details);
    if (!$log_stmt->execute()) {
        die('Execute failed: ' . htmlspecialchars($log_stmt->error));
    }

    // Delete the applicant from the applicants table
    $delete_query = "DELETE FROM applicants WHERE id = ?";
    $stmt_delete = $mysqli->prepare($delete_query);
    if (!$stmt_delete) {
        die('Prepare failed: ' . htmlspecialchars($mysqli->error));
    }
    $stmt_delete->bind_param('i', $applicant_id);
    if ($stmt_delete->execute()) {
        // Redirect to applicants page with success message
        header('Location: ../applicants.php?success=Applicant archived successfully.');
        exit();
    } else {
        // Redirect to applicants page with error message
        header('Location: ../applicants.php?error=Failed to delete applicant.');
        exit();
    }
} else {
    // Redirect to applicants page with error message
    header('Location: ../applicants.php?error=Applicant not found.');
    exit();
}
?>
