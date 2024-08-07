<?php

// Start session
session_start();
include_once 'db_connection.php';

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    header('Location: ../login.php');
    exit();
}

function formatDate($date)
{
    return date("F j, Y, g:i A", strtotime($date));
}

function formatDateDeadline($date)
{
    // Set the fixed time to 5:00 PM
    $fixed_time = '17:00:00'; // 5:00 PM in 24-hour format

    // Combine the provided date with the fixed time
    $datetime = $date . ' ' . $fixed_time;

    // Convert the combined datetime string to a timestamp and format it
    return date("F j, Y, g:i A", strtotime($datetime));
}

$user_name = isset($_SESSION['username']) ? $_SESSION['username'] : 'Guest';
$profile_image_path = isset($_SESSION['profile_image']) ? $_SESSION['profile_image'] : 'assets/img/profiles/default-profile.png';

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

// Pagination parameters for Jobs
$jobs_limit = 10;
$jobs_page = isset($_GET['jobs_page']) ? intval($_GET['jobs_page']) : 1;
$jobs_offset = ($jobs_page - 1) * $jobs_limit;

// Query to fetch archived jobs
$query_archive = "
SELECT ja.job_title, 
       ja.position_or_unit, 
       ja.description,
       GROUP_CONCAT(CASE WHEN jra.requirement_type = 'education' THEN jra.requirement_text END SEPARATOR ', ') AS education_requirements,
       GROUP_CONCAT(CASE WHEN jra.requirement_type = 'experience' THEN jra.requirement_text END SEPARATOR ', ') AS experience_requirements,
       GROUP_CONCAT(CASE WHEN jra.requirement_type = 'training' THEN jra.requirement_text END SEPARATOR ', ') AS training_requirements,
       GROUP_CONCAT(CASE WHEN jra.requirement_type = 'eligibility' THEN jra.requirement_text END SEPARATOR ', ') AS eligibility_requirements,
       GROUP_CONCAT(CASE WHEN jra.requirement_type = 'duties' THEN jra.requirement_text END SEPARATOR ', ') AS duties_requirements,
       GROUP_CONCAT(CASE WHEN jra.requirement_type = 'competencies' THEN jra.requirement_text END SEPARATOR ', ') AS competencies_requirements,
       ja.salary,
       d.name AS department_name,
       ja.place_of_assignment,
       ja.status,
       ja.created_at,
       ja.updated_at,
       ja.deadline,
       ja.archived_by,
       ja.jobarchive_id
FROM job_archive ja
LEFT JOIN department d ON ja.department_id = d.department_id
LEFT JOIN job_requirements_archive jra ON ja.jobarchive_id = jra.jobarchive_id
GROUP BY ja.jobarchive_id
LIMIT ?, ?
";

$stmt_archive = $mysqli->prepare($query_archive);
$stmt_archive->bind_param('ii', $jobs_offset, $jobs_limit);
$stmt_archive->execute();
$result_archive = $stmt_archive->get_result();

// Get total number of archived jobs for pagination
$query_archive_count = "
    SELECT COUNT(*) AS total 
    FROM job_archive
";
$stmt_count = $mysqli->prepare($query_archive_count);
$stmt_count->execute();
$result_archive_count = $stmt_count->get_result();
$total_jobs = $result_archive_count->fetch_assoc()['total'];
$total_pages_jobs = ($total_jobs > 0) ? ceil($total_jobs / $jobs_limit) : 1;

// If the form is submitted, update the profile details
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];

    // Handle profile image upload
    if (!empty($_FILES['profile_image']['name'])) {
        $profile_image = addslashes(file_get_contents($_FILES['profile_image']['tmp_name']));
    } else {
        $profile_image = $admin['profile_image'];
    }

    $update_query = "UPDATE admins SET name = ?, email = ?, profile_image = ? WHERE username = ?";
    $update_stmt = $mysqli->prepare($update_query);
    $update_stmt->bind_param('ssss', $name, $email, $profile_image, $username);

    if ($update_stmt->execute()) {
        // Update session variables and show success message
        $_SESSION['name'] = $name;
        $_SESSION['email'] = $email;
        $_SESSION['profile_image'] = $profile_image;
        echo "<script>window.addEventListener('load', function() { $('#successModal').modal('show'); });</script>";
    } else {
        echo "Error updating profile.";
    }
}

?>
