<?php
include_once 'db_connection.php';

if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit();
}
$query = "SELECT firstname, lastname, application_date, job_title, position_or_unit FROM applicants WHERE status = 'interview' ORDER BY application_date ASC";
$result = $mysqli->query($query);

$upcoming_interviews = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $full_name = $row['firstname'] . ' ' . $row['lastname'];
        $formatted_date = date('D, M j, Y', strtotime($row['application_date']));
        $upcoming_interviews[] = [
            'full_name' => $full_name,
            'application_date' => $formatted_date,
            'job_title' => $row['job_title'],
            'position_or_unit' => $row['position_or_unit']
        ];
    }
}
?>