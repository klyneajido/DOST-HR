<?php
// Start session and include database connection
session_start();
include_once 'db_connection.php';

// Check if the request method is GET and the announcement ID is set
if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['id'])) {
    $announcement_id = intval($_GET['id']);

    // Fetch the announcement from the archive table
    $query_fetch = "SELECT * FROM announcement_archive WHERE announcement_id = ?";
    $stmt_fetch = $mysqli->prepare($query_fetch);
    $stmt_fetch->bind_param('i', $announcement_id);
    $stmt_fetch->execute();
    $result_fetch = $stmt_fetch->get_result();

    if ($result_fetch->num_rows == 1) {
        $announcement = $result_fetch->fetch_assoc();

        // Insert the announcement back into the main announcements table
        $query_restore = "
            INSERT INTO announcements (title, description_announcement, link, image_announcement, created_at, updated_at)
            VALUES (?, ?, ?, ?, ?, ?)
        ";
        $stmt_restore = $mysqli->prepare($query_restore);
        $stmt_restore->bind_param(
            'ssssss',
            $announcement['title'],
            $announcement['description_announcement'],
            $announcement['link'],
            $announcement['image_announcement'],
            $announcement['created_at'],
            $announcement['updated_at']
        );

        if ($stmt_restore->execute()) {
            // Delete the announcement from the archive table
            $query_delete = "DELETE FROM announcement_archive WHERE announcement_id = ?";
            $stmt_delete = $mysqli->prepare($query_delete);
            $stmt_delete->bind_param('i', $announcement_id);
            $stmt_delete->execute();

            header('Location: ../archive.php?restored=1');
        } else {
            header('Location: ../archive.php?error=1');
        }
    } else {
        header('Location: ../archive.php?notfound=1');
    }
} else {
    header('Location: ../archive.php?invalid=1');
}
?>
