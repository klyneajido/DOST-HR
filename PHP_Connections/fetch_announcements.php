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

$success_message = isset($_GET['success']) ? htmlspecialchars($_GET['success']) : '';

// Check if search query is set
$search = isset($_GET['search']) ? $mysqli->real_escape_string($_GET['search']) : '';

// Check if sort order is set
$order = isset($_GET['order']) ? $_GET['order'] : 'desc'; // Default order descending

// Prepare SQL query
$sql = "SELECT a.announcement_id, a.title, a.description_announcement as announcement, a.link, a.image_announcement as image_shown, a.created_at, a.updated_at 
        FROM announcements a ";

if (!empty($search)) {
    $sql .= " WHERE a.title LIKE '%$search%' OR a.description_announcement LIKE '%$search%'";
}

$sql .= " ORDER BY a.created_at $order"; // Sort by created_at field and order by descending or ascending

$result = $mysqli->query($sql);

// Initialize an empty array to store announcements data
$announcements = [];

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $announcements[] = $row;
    }
} else {
    $errors['database'] = "No announcements found.";
}

?>