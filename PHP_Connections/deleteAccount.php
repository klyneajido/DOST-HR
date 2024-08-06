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
$admin_id = $data['admin_id'];
$password = $data['currentPassword'];

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
        // Fetch the admin's name to be deleted
        $admin_query = "SELECT name FROM admins WHERE admin_id = ?";
        $admin_stmt = $mysqli->prepare($admin_query);
        $admin_stmt->bind_param('i', $admin_id);
        $admin_stmt->execute();
        $admin_result = $admin_stmt->get_result();

        if ($admin_result->num_rows === 1) {
            $admin_to_delete = $admin_result->fetch_assoc();
            $admin_name_to_delete = $admin_to_delete['name'];

            // Proceed with deletion
            $delete_query = "DELETE FROM admins WHERE admin_id = ?";
            $delete_stmt = $mysqli->prepare($delete_query);
            $delete_stmt->bind_param('i', $admin_id);

            if ($delete_stmt->execute()) {
                // Record action in history
                $action = "Deleted admin account";
                $details = "Admin Name: $admin_name_to_delete";
                $sql_history = "INSERT INTO history (action, details, date, user_id) VALUES (?, ?, NOW(), ?)";
                $stmt_history = $mysqli->prepare($sql_history);
                $stmt_history->bind_param("ssi", $action, $details, $admin_id_logged_in);

                if ($stmt_history->execute()) {
                    echo json_encode(['success' => true, 'message' => 'Account deleted and history recorded successfully']);
                } else {
                    echo json_encode(['success' => false, 'message' => 'Account deleted but failed to record history']);
                }
            } else {
                echo json_encode(['success' => false, 'message' => 'Failed to delete account']);
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'Admin to delete not found']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Incorrect password']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Admin not found']);
}
