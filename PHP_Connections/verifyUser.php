<?php
session_start();
include_once 'db_connection.php';
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
    $user_authority = $user['authority']; 
} else {
    echo "User not found.";
    exit();
}
?>
