<?php

session_start();
include_once 'db_connection.php';

if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit();
}

$user_name = isset($_SESSION['username']) ? $_SESSION['username'] : 'Guest';
$profile_image_path = isset($_SESSION['profile_image']) ? $_SESSION['profile_image'] : 'assets/img/profiles/default-profile.png';

$announcement_id = isset($_GET['announcement_id']) ? (int)$_GET['announcement_id'] : 0;

if ($announcement_id === 0) {
    header('Location: view_announcements.php');
    exit();
}

$sql = "SELECT * FROM announcements WHERE announcement_id = ?";
$stmt = $mysqli->prepare($sql);
$stmt->bind_param('i', $announcement_id);
$stmt->execute();
$result = $stmt->get_result();
$announcement = $result->fetch_assoc();

if (!$announcement) {
    header('Location: view_announcements.php');
    exit();
}

$errors = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $link = $_POST['link'];

    // Check if a new image is uploaded
    if (!empty($_FILES['image']['tmp_name'])) {
        $image_data = file_get_contents($_FILES['image']['tmp_name']);
    } else {
        $image_data = $announcement['image_announcement']; // Use the existing image if no new image is uploaded
    }

    // Validate input
    if (empty($title)) {
        $errors['title'] = "Title is required";
    }
    if (empty($description)) {
        $errors['description'] = "Description is required";
    }
    if (empty($link)) {
        $errors['link'] = "Link is required";
    }

    if (empty($errors)) {
        // Update announcement
        $sql_update = "UPDATE announcements SET title = ?, description_announcement = ?, image_announcement = ?, link = ?, updated_at = NOW() WHERE announcement_id = ?";
        $stmt_update = $mysqli->prepare($sql_update);
        $stmt_update->bind_param('ssssi', $title, $description, $image_data, $link, $announcement_id);

        if ($stmt_update->execute()) {
            // Record action in the history table
            $history_stmt = $mysqli->prepare("
                INSERT INTO history (action, details, user_id, date) 
                VALUES (?, ?, (SELECT admin_id FROM admins WHERE username = ?), NOW())
            ");
            $action = "Updated Announcement";
            $details = "Announcement Title: $title";
            $history_stmt->bind_param("sss", $action, $details, $user_name);
            $history_stmt->execute();
            $history_stmt->close();

            header('Location: view_announcements.php?success=Announcement updated successfully');
            exit();
        } else {
            $errors['database'] = "Error updating announcement: " . $mysqli->error;
        }
    }
}
