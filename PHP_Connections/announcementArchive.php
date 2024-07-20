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

// Get user's username from session
$username = isset($_SESSION['username']) ? $_SESSION['username'] : 'Guest';

// Fetch user ID based on username
$user_query = "SELECT admin_id FROM admins WHERE username = ?";
$stmt_user = $mysqli->prepare($user_query);
$stmt_user->bind_param('s', $username);
$stmt_user->execute();
$result_user = $stmt_user->get_result();

if ($result_user->num_rows > 0) {
    $user = $result_user->fetch_assoc();
    $user_id = $user['admin_id'];
} else {
    // Redirect to announcements page with error message if user is not found
    header('Location: ../announcements.php?error=User not found.');
    exit();
}

// Check if announcement_id is set
if (!isset($_GET['announcement_id'])) {
    // Redirect to announcements page if announcement_id is not set
    header('Location: ../announcements.php');
    exit();
}

// Get announcement_id from the URL
$announcement_id = $mysqli->real_escape_string($_GET['announcement_id']);

// Fetch the announcement to be archived
$query = "SELECT * FROM announcements WHERE announcement_id = $announcement_id";
$result = $mysqli->query($query);

if ($result && $result->num_rows > 0) {
    $announcement = $result->fetch_assoc();

    // Insert the announcement into the announcement_archive table
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
        $username
    );

    if ($stmt->execute()) {
        // Log the action in the history table
        $action = "Archived Announcements";
        $details = "Archived announcement titled: \"" . htmlspecialchars($announcement['title']) . "\"";
        $log_query = "INSERT INTO history (user_id, action, details, date) VALUES (?, ?, ?, NOW())";
        $log_stmt = $mysqli->prepare($log_query);
        $log_stmt->bind_param('iss', $user_id, $action, $details);

        if ($log_stmt->execute()) {
            // Delete the announcement from the announcements table
            $delete_query = "DELETE FROM announcements WHERE announcement_id = $announcement_id";
            if ($mysqli->query($delete_query)) {
                // Redirect to announcements page with success message
                header('Location: ../announcements.php?success=Announcement archived successfully.');
                exit();
            } else {
                // Redirect to announcements page with error message
                header('Location: ../announcements.php?error=Failed to delete announcement.');
                exit();
            }
        } else {
            // Redirect to announcements page with error message for logging failure
            header('Location: ../announcements.php?error=Failed to log the action.');
            exit();
        }
    } else {
        // Redirect to announcements page with error message
        header('Location: ../announcements.php?error=Failed to archive announcement.');
        exit();
    }
} else {
    // Redirect to announcements page with error message
    header('Location: ../announcements.php?error=Announcement not found.');
    exit();
}
?>
