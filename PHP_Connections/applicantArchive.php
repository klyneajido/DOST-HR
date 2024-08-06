<?php

// Start session
session_start();
include_once 'db_connection.php';

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    header('Location: ../login.php');
    exit();
}

$username = $_SESSION['username'] ?? 'Guest';

$user_query = "SELECT admin_id FROM admins WHERE username = ?";
$stmt_user = $mysqli->prepare($user_query);
$stmt_user->bind_param('s', $username);
$stmt_user->execute();
$result_user = $stmt_user->get_result();

if ($result_user->num_rows > 0) {
    $user = $result_user->fetch_assoc();
    $user_id = $user['admin_id'];
} else {
    $_SESSION['error_message'] = 'User not found.';
    header('Location: ../applicants.php');
    exit();
}

if (!isset($_POST['applicant_id'])) {
    die('No applicant ID provided.');
}

$applicant_id = $mysqli->real_escape_string($_POST['applicant_id']);

$query = "SELECT * FROM applicants WHERE id = ?";
$stmt = $mysqli->prepare($query);
$stmt->bind_param('i', $applicant_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result && $result->num_rows > 0) {
    $applicant = $result->fetch_assoc();

    $archive_query = "INSERT INTO applicant_archive 
    (job_title, position_or_unit, plantilla, lastname, firstname, middlename, sex, address, email, contact_number, course, years_of_experience, hours_of_training, eligibility, list_of_awards, status, application_letter, personal_data_sheet, performance_rating, eligibility_rating_license, transcript_of_records, certificate_of_employment, proof_of_trainings_seminars, proof_of_rewards, job_id, application_date, interview_date, archived_by) 
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";


    $stmt_archive = $mysqli->prepare($archive_query);
    if (!$stmt_archive) {
        die('Prepare failed: ' . htmlspecialchars($mysqli->error));
    }

    $stmt_archive->bind_param(
        'ssssssssssssssssssssssssisss', // Adjust according to your column types
        $applicant['job_title'],
        $applicant['position_or_unit'],
        $applicant['plantilla'],
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
        $applicant['application_letter'],
        $applicant['personal_data_sheet'],
        $applicant['performance_rating'],
        $applicant['eligibility_rating_license'],
        $applicant['transcript_of_records'],
        $applicant['certificate_of_employment'],
        $applicant['proof_of_trainings_seminars'],
        $applicant['proof_of_rewards'],
        $applicant['job_id'],                // Integer
        $applicant['application_date'],      // String (date)
        $applicant['interview_date'],        // String (date)
        $username                           // String
    );
    

    if (!$stmt_archive->execute()) {
        die('Archive execute failed: ' . htmlspecialchars($stmt_archive->error));
    }

    $action = "Archived Applicant";
    $details = "Applicant name: " . $applicant['firstname'] . " " . $applicant['lastname'];
    $log_query = "INSERT INTO history (user_id, action, details, date) VALUES (?, ?, ?, NOW())";
    $log_stmt = $mysqli->prepare($log_query);
    if (!$log_stmt) {
        die('Prepare failed: ' . htmlspecialchars($mysqli->error));
    }

    $log_stmt->bind_param('iss', $user_id, $action, $details);
    if (!$log_stmt->execute()) {
        die('Log execute failed: ' . htmlspecialchars($log_stmt->error));
    }

    $delete_query = "DELETE FROM applicants WHERE id = ?";
    $stmt_delete = $mysqli->prepare($delete_query);
    if (!$stmt_delete) {
        die('Prepare failed: ' . htmlspecialchars($mysqli->error));
    }
    $stmt_delete->bind_param('i', $applicant_id);
    if ($stmt_delete->execute()) {
        $_SESSION['success_message'] = 'Applicant archived successfully.';
        header('Location: ../applicants.php');
        exit();
    } else {
        $_SESSION['error_message'] = 'Failed to delete applicant.';
        header('Location: ../applicants.php');
        exit();
    }
} else {
    $_SESSION['error_message'] = 'Applicant not found.';
    header('Location: ../applicants.php');
    exit();
}