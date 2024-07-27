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
$query = "SELECT password FROM admins WHERE username = ?";
$stmt = $mysqli->prepare($query);
$stmt->bind_param('s', $username);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
    $admin = $result->fetch_assoc();
    $hashed_password = $admin['password'];

    // Verify password
    if (password_verify($password, $hashed_password)) {
        // Password is correct, proceed with deletion
        $delete_query = "DELETE FROM admins WHERE admin_id = ?";
        $delete_stmt = $mysqli->prepare($delete_query);
        $delete_stmt->bind_param('i', $admin_id);

        if ($delete_stmt->execute()) {
            echo json_encode(['success' => true, 'message' => 'Account deleted successfully']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to delete account']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Incorrect password']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Admin not found']);
}
?>