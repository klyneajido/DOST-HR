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

// Get user's name from session
$user_name = isset($_SESSION['username']) ? $_SESSION['username'] : 'Guest';
$profile_image_path = isset($_SESSION['profile_image']) ? $_SESSION['profile_image'] : 'assets/img/profiles/default-profile.png';

$sql = "SELECT COUNT(*) as count FROM applicants";
$result = $mysqli->query($sql);
$applicant_count = 0;

if ($result) {
	$row = $result->fetch_assoc();
	$applicant_count = $row['count'];
} else {
	echo "Error retrieving applicant count: " . $mysqli->error;
}

$sql = "SELECT COUNT(*) as count FROM job";
$result = $mysqli->query($sql);
$job_count = 0;

if ($result) {
	$row = $result->fetch_assoc();
	$job_count = $row['count'];
} else {
	echo "Error retrieving job count: " . $mysqli->error;
}

$sql = "SELECT COUNT(*) as count FROM announcements";
$result = $mysqli->query($sql);
$announcement_count = 0;

if ($result) {
	$row = $result->fetch_assoc();
	$announcement_count = $row['count'];
} else {
	echo "Error retrieving announcement count: " . $mysqli->error;
}

//APPLICATION FILTER 
// Fetch job titles and their specific positions/units along with applicant counts
$query = "SELECT 
            j.job_id,
            j.job_title,
            j.position_or_unit,
            COUNT(a.id) as count
          FROM job j
          LEFT JOIN applicants a ON j.job_id = a.job_id
          GROUP BY j.job_id, j.job_title, j.position_or_unit";

$result = $mysqli->query($query);

// Initialize an array to store positions and counts
$positions = [];

// Process data into a structured array
while ($row = $result->fetch_assoc()) {
    $general_title = $row['job_title'];
    $general_id = $row['job_id'];
    $specific_position = $row['position_or_unit'];
    $count = $row['count'];

    // Check if the general_title already exists in $positions
    if (!isset($positions[$general_title])) {
        $positions[$general_title] = [
            'general_title' => $general_title,
            'total_count' => 0,
            'specific_positions' => []
        ];
    }

    // Increment total_count for the existing general_title
    $positions[$general_title]['total_count'] += $count;

    // Add specific_position under the general_title
    $positions[$general_title]['specific_positions'][] = [
        'specific_position' => $specific_position,
        'specific_count' => $count
    ];
}
?>