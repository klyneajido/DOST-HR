<?php
// Start session
session_start();
include_once 'PHP_Connections\db_connection.php';

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    // Redirect to login page if not logged in
    header('Location: login.php');
    exit();
}

// Get user's name from session
$archived_by = isset($_SESSION['username']) ? $_SESSION['username'] : 'Guest';

// Check if announcement_id is set
if (!isset($_GET['announcement_id'])) {
    // Redirect to announcements page if announcement_id is not set
    header('Location: announcements.php');
    exit();
}

// Get announcement_id from the URL
$announcement_id = $mysqli->real_escape_string($_GET['announcement_id']);

// Fetch the announcement to be archived
$query = "SELECT * FROM announcements WHERE announcement_id = $announcement_id";
$result = $mysqli->query($query);

if ($result && $result->num_rows > 0) {
    $announcement = $result->fetch_assoc();

    // Insert the announcement into the announcements_archive table
    $archive_query = "INSERT INTO announcement_archive (announcement_id, title, description_announcement, link, image_announcement, created_at, updated_at, archived_by) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $mysqli->prepare($archive_query);
    $stmt->bind_param(
        'isssssss',
        $announcement['announcement_id'],
        $announcement['title'],
        $announcement['description_announcement'],
        $announcement['link'],
        $announcement['image_announcement'],
        $announcement['created_at'],
        $announcement['updated_at'],
        $archived_by
    );

    if ($stmt->execute()) {
        // Delete the announcement from the announcements table
        $delete_query = "DELETE FROM announcements WHERE announcement_id = $announcement_id";
        if ($mysqli->query($delete_query)) {
            // Redirect to announcements page with success message
            header('Location: announcements.php?success=Announcement archived successfully.');
            exit();
        } else {
            // Redirect to announcements page with error message
            header('Location: announcements.php?error=Failed to delete announcement.');
            exit();
        }
    } else {
        // Redirect to announcements page with error message
        header('Location: announcements.php?error=Failed to archive announcement.');
        exit();
    }
} else {
    // Redirect to announcements page with error message
    header('Location: announcements.php?error=Announcement not found.');
    exit();
}
?>
