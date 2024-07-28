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

// Get user's name and profile image path from session
$user_name = isset($_SESSION['username']) ? $_SESSION['username'] : 'Guest';
$profile_image_path = isset($_SESSION['profile_image']) ? $_SESSION['profile_image'] : 'assets/img/profiles/default-profile.png';

// Initialize search variable
$search = isset($_GET['search']) ? trim($_GET['search']) : '';

// Prepare the query with optional search condition
$query = "SELECT * FROM department";
if ($search) {
    $query .= " WHERE name LIKE ? OR location LIKE ?";
}

// Fetch departments from the database
$stmt = $mysqli->prepare($query);
if ($search) {
    $search_param = '%' . $search . '%';
    $stmt->bind_param('ss', $search_param, $search_param);
}
$stmt->execute();
$result = $stmt->get_result();
$departments = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $departments[] = $row;
    }
} else {
    $departments = [];
}

$stmt->close();
$mysqli->close();
?>
