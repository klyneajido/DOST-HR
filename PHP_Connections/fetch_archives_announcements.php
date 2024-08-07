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
    return date(" F j, Y, g:i A", strtotime($datetime));
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

// Pagination parameters for Announcements
$announcements_limit = 6;
$announcements_page = isset($_GET['announcements_page']) ? intval($_GET['announcements_page']) : 1;
$announcements_offset = ($announcements_page - 1) * $announcements_limit;

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
$total_pages_announcements = ($total_announcements > 0) ? ceil($total_announcements / $announcements_limit) : 1;

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
