<?php

session_start();
include_once 'db_connection.php';

// Check if user is logged in
if (!isset($_SESSION['username']) || !isset($_GET['id'])) {
    header('Location: ../login.php');
    exit();
}

// Get user details from session
$user_id = $_SESSION['user_id'];
$username = $_SESSION['username'];

// Get announcement ID from GET request
$announcement_id = intval($_GET['id']);

// Prepare SQL to archive the announcement
$query_archive = "UPDATE announcements SET archived = 1 WHERE id = ?";
$stmt_archive = $mysqli->prepare($query_archive);
$stmt_archive->bind_param('i', $announcement_id);

// Execute archive query and check for success
if ($stmt_archive->execute()) {
    // Prepare SQL to insert log into history table
    $action = "Archived Announcement";
    $details = "User '$username' archived announcement ID $announcement_id";
    $query_log = "INSERT INTO history (user_id, action, details) VALUES (?, ?, ?)";
    $stmt_log = $mysqli->prepare($query_log);
    $stmt_log->bind_param('iss', $user_id, $action, $details);

    // Execute log query and check for success
    if ($stmt_log->execute()) {
        echo "Announcement archived and action logged successfully.";
    } else {
        echo "Announcement archived but failed to log action.";
    }
} else {
    echo "Failed to archive announcement.";
}

// Close statements and connection
$stmt_archive->close();
$stmt_log->close();
$mysqli->close();
