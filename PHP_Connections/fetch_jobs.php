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

function formatDate($date) {
    return date("g:i A, F j, Y", strtotime($date));
}

function formatDateDeadline($date) {
    // Set the fixed time to 5:00 PM
    $fixed_time = '17:00:00'; // 5:00 PM in 24-hour format

    // Combine the provided date with the fixed time
    $datetime = $date . ' ' . $fixed_time;

    // Convert the combined datetime string to a timestamp and format it
    return date("g:i A, F j, Y", strtotime($datetime));
}

// Get user's name from session
$user_name = isset($_SESSION['username']) ? $_SESSION['username'] : 'Guest';
$profile_image_path = isset($_SESSION['profile_image']) ? $_SESSION['profile_image'] : 'assets/img/profiles/default-profile.png';

// Check if search query is set
$search = isset($_GET['search']) ? $mysqli->real_escape_string($_GET['search']) : '';

// Check if status filter is set
$status_filter = isset($_GET['status']) ? $mysqli->real_escape_string($_GET['status']) : '';

// Pagination setup
$limit = 6; // Number of items per page
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// Prepare SQL query
$sql = "SELECT j.job_id, j.job_title, j.position_or_unit, j.description, d.name as department_name, j.place_of_assignment, d.abbrev, j.salary, j.status, j.created_at, j.updated_at, j.deadline 
        FROM job j
        INNER JOIN department d ON j.department_id = d.department_id
        WHERE 1=1"; // Use WHERE 1=1 to simplify appending additional conditions

if (!empty($search)) {
    $sql .= " AND (j.job_title LIKE '%$search%' OR d.name LIKE '%$search%' OR d.abbrev LIKE '%$search%' OR j.status LIKE '%$search%')";
}

if (!empty($status_filter)) {
    $sql .= " AND j.status = '$status_filter'";
}

// Add order by clause to sort by created_at or updated_at in descending order
$sql .= " ORDER BY j.updated_at DESC, j.created_at DESC";

// Add pagination to the query
$sql .= " LIMIT $limit OFFSET $offset";

$result = $mysqli->query($sql);

// Fetch total number of jobs for pagination
$total_result = $mysqli->query("SELECT COUNT(*) as total FROM job j INNER JOIN department d ON j.department_id = d.department_id WHERE 1=1" . (empty($search) ? "" : " AND (j.job_title LIKE '%$search%' OR d.name LIKE '%$search%' OR d.abbrev LIKE '%$search%' OR j.status LIKE '%$search%')") . (empty($status_filter) ? "" : " AND j.status = '$status_filter'"));
$total_row = $total_result->fetch_assoc();
$total_jobs = $total_row['total'];
$total_pages = ceil($total_jobs / $limit);

// Initialize an empty array to store jobs data
$jobs = [];

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        // Truncate description to 100 characters
        $max_description_length = 100;
        $description = htmlspecialchars($row['description']);
        if (strlen($description) > $max_description_length) {
            $description = substr($description, 0, $max_description_length) . '...';
        }
        $row['description'] = $description;
        $jobs[] = $row;
    }
} else {
    $errors['database'] = "No jobs found.";
}
?>
