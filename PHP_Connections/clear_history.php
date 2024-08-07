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
$password = $data['password'];

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
        // Password is correct, proceed with clearing history
        $clear_query = "DELETE FROM history"; // Adjust this query based on your table and conditions
        $clear_stmt = $mysqli->prepare($clear_query);

        if ($clear_stmt->execute()) {
            // Redirect after clearing history
            echo json_encode(['success' => true, 'message' => 'Applicant deleted successfully']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to clear history']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Incorrect password']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Admin not found']);
}
