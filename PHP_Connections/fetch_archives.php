

<?php
//eyyo 
// Start session
session_start();
include_once 'db_connection.php';

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    header('Location: ../login.php');
    exit();
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

// Get search input if present
$search = isset($_GET['search']) ? trim($_GET['search']) : '';

// Pagination parameters for Jobs
$jobs_limit = 10;
$jobs_page = isset($_GET['jobs_page']) ? intval($_GET['jobs_page']) : 1;
$jobs_offset = ($jobs_page - 1) * $jobs_limit;

// Pagination parameters for Announcements
$announcements_limit = 6;
$announcements_page = isset($_GET['announcements_page']) ? intval($_GET['announcements_page']) : 1;
$announcements_offset = ($announcements_page - 1) * $announcements_limit;

// Modified query to join job_archive with department to get department name and paginate results
// SQL query to join job_archive with job_requirements_archive and department
$query_archive = "
SELECT ja.job_title, 
       ja.position_or_unit, 
       ja.description,
       GROUP_CONCAT(CASE WHEN jra.requirement_type = 'education' THEN jra.requirement_text END SEPARATOR ', ') AS education_requirements,
       GROUP_CONCAT(CASE WHEN jra.requirement_type = 'experience' THEN jra.requirement_text END SEPARATOR ', ') AS experience_requirements,
       GROUP_CONCAT(CASE WHEN jra.requirement_type = 'duties' THEN jra.requirement_text END SEPARATOR ', ') AS duties_and_responsibilities,
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
WHERE ja.job_title LIKE ? OR ja.description LIKE ?
GROUP BY ja.jobarchive_id
LIMIT ?, ?
";

$search_term = '%' . $search . '%';
$stmt_archive = $mysqli->prepare($query_archive);
$stmt_archive->bind_param('ssii', $search_term, $search_term, $jobs_offset, $jobs_limit);
$stmt_archive->execute();
$result_archive = $stmt_archive->get_result();

// Get total number of archived jobs for pagination
$query_archive_count = "
    SELECT COUNT(*) AS total 
    FROM job_archive 
    WHERE job_title LIKE ? OR description LIKE ?
";
$stmt_count = $mysqli->prepare($query_archive_count);
$stmt_count->bind_param('ss', $search_term, $search_term);
$stmt_count->execute();
$result_archive_count = $stmt_count->get_result();
$total_jobs = $result_archive_count->fetch_assoc()['total'];
$total_pages_jobs = ceil($total_jobs / $jobs_limit);

// Fetch paginated archived announcements
$query_announcement_archive = "
    SELECT * FROM announcement_archive
    LIMIT ?, ?
";
$stmt_announcement = $mysqli->prepare($query_announcement_archive);
$stmt_announcement->bind_param('ii', $announcements_offset, $announcements_limit);
$stmt_announcement->execute();
$result_announcement_archive = $stmt_announcement->get_result();

// Get total number of archived announcements for pagination
$query_announcement_count = "SELECT COUNT(*) AS total FROM announcement_archive";
$result_announcement_count = $mysqli->query($query_announcement_count);
$total_announcements = $result_announcement_count->fetch_assoc()['total'];
$total_pages_announcements = ceil($total_announcements / $announcements_limit);

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

// Get search input if present (for announcements)
$search_announcement = isset($_GET['search_announcement']) ? trim($_GET['search_announcement']) : '';

// Modified query to search within announcement_archive
$query_announcement_archive = "
    SELECT * FROM announcement_archive
    WHERE title LIKE ? OR description_announcement LIKE ?
    LIMIT ?, ?
";
$search_announcement_term = '%' . $search_announcement . '%';
$stmt_announcement = $mysqli->prepare($query_announcement_archive);
$stmt_announcement->bind_param('ssii', $search_announcement_term, $search_announcement_term, $announcements_offset, $announcements_limit);
$stmt_announcement->execute();
$result_announcement_archive = $stmt_announcement->get_result();

// Get total number of matching announcements for pagination
$query_announcement_count = "
    SELECT COUNT(*) AS total
    FROM announcement_archive
    WHERE title LIKE ? OR description_announcement LIKE ?
";
$stmt_count_announcement = $mysqli->prepare($query_announcement_count);
$stmt_count_announcement->bind_param('ss', $search_announcement_term, $search_announcement_term);
$stmt_count_announcement->execute();
$result_announcement_count = $stmt_count_announcement->get_result();
$total_announcements = $result_announcement_count->fetch_assoc()['total'];
$total_pages_announcements = ceil($total_announcements / $announcements_limit);

?>