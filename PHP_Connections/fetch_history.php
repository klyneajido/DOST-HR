<?php

// Start session
session_start();
include_once 'db_connection.php';

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit();
}

function formatDate($date)
{
    return date("F j, Y, g:i A", strtotime($date));
}
$user_name = isset($_SESSION['username']) ? $_SESSION['username'] : 'Guest';
$profile_image_path = isset($_SESSION['profile_image']) ? $_SESSION['profile_image'] : 'assets/img/profiles/default-profile.png';
$username = $_SESSION['username'];

// Fetch admin details
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

// Handle form submission for profile update
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

// Pagination setup
$items_per_page = 10;
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
$offset = ($page - 1) * $items_per_page;

// Get sort order
$sort_order = isset($_GET['sort']) && $_GET['sort'] === 'asc' ? 'ASC' : 'DESC';

// Handle filters
$search_term = isset($_GET['search']) ? $mysqli->real_escape_string($_GET['search']) : '';
$admin_id = isset($_GET['admin_id']) ? intval($_GET['admin_id']) : 0;
$action_filter = isset($_GET['action']) ? $mysqli->real_escape_string($_GET['action']) : '';

// Prepare SQL query with filters
$history_query = "SELECT h.*, a.name AS admin_name 
                  FROM history h 
                  JOIN admins a ON h.user_id = a.admin_id 
                  WHERE h.action LIKE ? ";
if ($admin_id > 0) {
    $history_query .= "AND h.user_id = ? ";
}
if (!empty($action_filter)) {
    $history_query .= "AND h.action = ? ";
}
$history_query .= "ORDER BY h.date $sort_order 
                  LIMIT $items_per_page OFFSET $offset";

$stmt = $mysqli->prepare($history_query);
$search_like = '%' . $search_term . '%';
$params = [$search_like];
if ($admin_id > 0) {
    $params[] = $admin_id;
}
if (!empty($action_filter)) {
    $params[] = $action_filter;
}
$stmt->bind_param(str_repeat('s', count($params)), ...$params);
$stmt->execute();
$history_result = $stmt->get_result();

// Count total records for pagination
$count_query = "SELECT COUNT(*) AS total FROM history WHERE action LIKE ? ";
if ($admin_id > 0) {
    $count_query .= "AND user_id = ? ";
}
if (!empty($action_filter)) {
    $count_query .= "AND action = ? ";
}
$stmt = $mysqli->prepare($count_query);
$params = [$search_like];
if ($admin_id > 0) {
    $params[] = $admin_id;
}
if (!empty($action_filter)) {
    $params[] = $action_filter;
}
$stmt->bind_param(str_repeat('s', count($params)), ...$params);
$stmt->execute();
$count_result = $stmt->get_result();
$total_rows = $count_result->fetch_assoc()['total'];
$total_pages = ceil($total_rows / $items_per_page);
