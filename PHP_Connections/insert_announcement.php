<?php
session_start();
include_once 'db_connection.php';

if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit();
}

$user_name = isset($_SESSION['username']) ? $_SESSION['username'] : 'Guest';
$profile_image_path = isset($_SESSION['profile_image']) ? $_SESSION['profile_image'] : 'assets/img/profiles/default-profile.png';

$errors = [];
$max_description_length = 300; // Example maximum length

// Check if form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $title = $_POST['title'];
    $description = $_POST['description'];
    $link = $_POST['link'];
    $image_data = file_get_contents($_FILES['image']['tmp_name']);

    // Validate form data
    if (empty($title)) {
        $errors['title'] = "Title is required";
    }
    if (strlen($description) > $max_description_length) {
        $errors['description'] = "Description must not exceed {$max_description_length} characters.";
    }
    if (empty($link)) {
        $errors['link'] = "Link is required";
    }
    if (empty($image_data)) {
        $errors['image'] = "Image is required";
    }

    // If no errors, proceed with data insertion
    if (empty($errors)) {
        // Prepare SQL statement
        $sql = "INSERT INTO announcements (title, description_announcement, link, image_announcement, created_at) VALUES (?, ?, ?, ?, NOW())";
        $stmt = $mysqli->prepare($sql);

        if ($stmt) {
            // Bind parameters and execute
            $stmt->bind_param("ssss", $title, $description, $link, $image_data);

            // Execute SQL statement
            if ($stmt->execute()) {
                // Get the ID of the inserted announcement
                $announcement_id = $stmt->insert_id;

                // Record action in the history table
                $history_stmt = $mysqli->prepare("
                    INSERT INTO history (action, details, user_id, date) 
                    VALUES (?, ?, (SELECT admin_id FROM admins WHERE username = ?), NOW())
                ");
                $action = "Added Announcement";
                $details = "Announcement Title: $title";
                $history_stmt->bind_param("sss", $action, $details, $user_name);
                $history_stmt->execute();
                $history_stmt->close();

                header('Location: announcements.php?success=Announcement added successfully');
                exit();
            } else {
                $errors['database'] = "Error executing statement: " . $stmt->error;
            }
        } else {
            $errors['database'] = "Error preparing statement: " . $mysqli->error;
        }

        // Close statement
        $stmt->close();
    }
}
?>