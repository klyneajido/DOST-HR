<?php
session_start();
include_once 'db_connection.php';

if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['id'])) {
    $announcement_id = intval($_GET['id']);

    // Debug: Log the ID received
    error_log("Restoring announcement ID: " . $announcement_id);

    $query_fetch = "SELECT * FROM announcement_archive WHERE announcement_id = ?";
    $stmt_fetch = $mysqli->prepare($query_fetch);
    $stmt_fetch->bind_param('i', $announcement_id);
    $stmt_fetch->execute();
    $result_fetch = $stmt_fetch->get_result();

    if ($result_fetch->num_rows == 1) {
        $announcement = $result_fetch->fetch_assoc();

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
            $query_delete = "DELETE FROM announcement_archive WHERE announcement_id = ?";
            $stmt_delete = $mysqli->prepare($query_delete);
            $stmt_delete->bind_param('i', $announcement_id);
            $stmt_delete->execute();

            header('Location: ../archive.php?tab=jobs&msg=restored');
        } else {
            error_log("Error restoring announcement: " . $stmt_restore->error);
            header('Location: ../archive.php?tab=jobs&msg=error');
        }
    } else {
        header('Location: ../archive.php?tab=jobs&msg=notfound');
    }
} else {
    header('Location: ../archive.php?tab=jobs&msg=invalid');
}
?>
