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
$applicant_id = $data['id'];
$password = $data['password'];

// Fetch admin details from the database
$username = $_SESSION['username'];
$query = "SELECT admin_id, password FROM admins WHERE username = ?";
$stmt = $mysqli->prepare($query);
$stmt->bind_param('s', $username);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
    $admin = $result->fetch_assoc();
    $admin_id = $admin['admin_id'];
    $hashed_password = $admin['password'];

    // Verify password
    if (password_verify($password, $hashed_password)) {
        // Fetch applicant details using the applicant_id
        $applicant_query = "SELECT firstname, lastname FROM applicant_archive WHERE applicantarchive_id = ?";
        $applicant_stmt = $mysqli->prepare($applicant_query);
        $applicant_stmt->bind_param('i', $applicant_id);
        $applicant_stmt->execute();
        $applicant_result = $applicant_stmt->get_result();

        if ($applicant_result->num_rows === 1) {
            $applicant = $applicant_result->fetch_assoc();
            $full_name = $applicant['firstname'] . ' ' . $applicant['lastname'];

            // Proceed with deletion
            $delete_query = "DELETE FROM applicant_archive WHERE applicantarchive_id = ?";
            $delete_stmt = $mysqli->prepare($delete_query);
            $delete_stmt->bind_param('i', $applicant_id);

            if ($delete_stmt->execute()) {
                // Record action in history
                $action = "Deleted archived applicant";
                $details = "Applicant Name: $full_name";
                $sql_history = "INSERT INTO history (action, details, date, user_id) VALUES (?, ?, NOW(), ?)";
                $stmt_history = $mysqli->prepare($sql_history);
                $stmt_history->bind_param("ssi", $action, $details, $admin_id);

                if ($stmt_history->execute()) {
                    echo json_encode(['success' => true, 'message' => 'Applicant deleted and history recorded successfully']);
                } else {
                    echo json_encode(['success' => false, 'message' => 'Applicant deleted but failed to record history']);
                }
            } else {
                echo json_encode(['success' => false, 'message' => 'Failed to delete applicant']);
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'Applicant not found']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Incorrect password']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Admin not found']);
}
