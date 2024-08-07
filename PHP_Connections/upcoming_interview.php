<?php
// session_start();
include_once 'db_connection.php';
// Redirect to login page if the user is not logged in
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit();
}

// Updated query to select interview_date instead of application_date
$query = "SELECT firstname, lastname, interview_date, job_title, position_or_unit FROM applicants WHERE status = 'interview' ORDER BY interview_date ASC";
$result = $mysqli->query($query);

$upcoming_interviews = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $full_name = $row['firstname'] . ' ' . $row['lastname'];
        // Check if interview_date is NULL or empty
        if (empty($row['interview_date'])) {
            $formatted_date = 'No interview date yet';
        } else {
            // Format interview_date if it is not NULL
            $formatted_date = date('D, M j, Y', strtotime($row['interview_date']));
        }
        $upcoming_interviews[] = [
            'full_name' => $full_name,
            'interview_date' => $formatted_date,
            'job_title' => $row['job_title'],
            'position_or_unit' => $row['position_or_unit']
        ];
    }
}
