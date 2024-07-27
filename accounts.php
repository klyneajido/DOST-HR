<?php
// Start session
session_start();
include_once 'PHP_Connections/db_connection.php';

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit();
}

$username = $_SESSION['username'];

// Fetch user details
$query = "SELECT authority FROM admins WHERE username = ?";
$stmt = $mysqli->prepare($query);
$stmt->bind_param('s', $username);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
    $user = $result->fetch_assoc();
    $user_authority = $user['authority']; // Set the authority variable
} else {
    // User not found
    header('Location: login.php');
    exit();
}

// Check if user is a superadmin
if ($user_authority !== 'superadmin') {
    // Redirect non-superadmin users
    header('Location: index.php'); // or another appropriate page
    exit();
}

// Continue with the rest of your accounts page logic here
?>

<!DOCTYPE html>
<html>
<head>
    <title>Accounts - Admin Panel</title>
    <!-- Include CSS and JavaScript files -->
</head>
<body>
    <!-- Page content for superadmin users -->
    <h1>Welcome to the Accounts Page</h1>
    <!-- Your page content goes here -->
</body>
</html>
