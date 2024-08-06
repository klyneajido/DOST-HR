<?php

session_start();
include_once 'db_connection.php';

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    echo json_encode(['success' => false, 'message' => 'Not authenticated']);
    exit();
}

// Get POST data
$data = json_decode(file_get_contents('php://input'), true);
$announcement_id = $data['id'];
$password = $data['password'];

// Fetch admin details from the database
$username = $_SESSION['username'];
$query = "SELECT admin_id, name, password FROM admins WHERE username = ?";
$stmt = $mysqli->prepare($query);
$stmt->bind_param('s', $username);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
    $admin = $result->fetch_assoc();
    $admin_id_logged_in = $admin['admin_id'];
    $admin_name = $admin['name'];
    $hashed_password = $admin['password'];

    // Verify password
    if (password_verify($password, $hashed_password)) {
        // Fetch announcement details to log in history
        $announcement_query = "SELECT * FROM announcement_archive WHERE announcement_id = ?";
        $announcement_stmt = $mysqli->prepare($announcement_query);
        $announcement_stmt->bind_param('i', $announcement_id);
        $announcement_stmt->execute();
        $announcement_result = $announcement_stmt->get_result();

        if ($announcement_result->num_rows === 1) {
            $announcement = $announcement_result->fetch_assoc();
            $announcement_title = $announcement['title']; // Replace with actual column name if different

            // Proceed with deletion
            $delete_query = "DELETE FROM announcement_archive WHERE announcement_id = ?";
            $delete_stmt = $mysqli->prepare($delete_query);
            $delete_stmt->bind_param('i', $announcement_id);

            if ($delete_stmt->execute()) {
                // Record action in history
                $action = "Deleted announcement";
                $details = "Announcement Title: $announcement_title";
                $sql_history = "INSERT INTO history (action, details, date, user_id) VALUES (?, ?, NOW(), ?)";
                $stmt_history = $mysqli->prepare($sql_history);
                $stmt_history->bind_param("ssi", $action, $details, $admin_id_logged_in);

                if ($stmt_history->execute()) {
                    echo json_encode(['success' => true, 'message' => 'Announcement deleted and history recorded successfully']);
                } else {
                    echo json_encode(['success' => false, 'message' => 'Announcement deleted but failed to record history']);
                }
            } else {
                echo json_encode(['success' => false, 'message' => 'Failed to delete announcement']);
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'Announcement not found']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Incorrect password']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Admin not found']);
}
