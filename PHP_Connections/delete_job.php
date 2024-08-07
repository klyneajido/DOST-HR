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
$job_id = $data['id'];
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
        // Fetch job name using the job_id
        $job_query = "SELECT job_title FROM job_archive WHERE jobarchive_id = ?";
        $job_stmt = $mysqli->prepare($job_query);
        $job_stmt->bind_param('i', $job_id);
        $job_stmt->execute();
        $job_result = $job_stmt->get_result();

        if ($job_result->num_rows === 1) {
            $job = $job_result->fetch_assoc();
            $job_name = $job['job_title'];

            // Proceed with deletion
            $delete_query = "DELETE FROM job_archive WHERE jobarchive_id = ?";
            $delete_stmt = $mysqli->prepare($delete_query);
            $delete_stmt->bind_param('i', $job_id);

            if ($delete_stmt->execute()) {
                // Record action in history
                $action = "Deleted archived job";
                $details = "Job Title: $job_name";
                $sql_history = "INSERT INTO history (action, details, date, user_id) VALUES (?, ?, NOW(), ?)";
                $stmt_history = $mysqli->prepare($sql_history);
                $stmt_history->bind_param("ssi", $action, $details, $admin_id);

                if ($stmt_history->execute()) {
                    echo json_encode(['success' => true, 'message' => 'Job deleted and history recorded successfully']);
                } else {
                    echo json_encode(['success' => false, 'message' => 'Job deleted but failed to record history']);
                }
            } else {
                echo json_encode(['success' => false, 'message' => 'Failed to delete job']);
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'Job not found']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Incorrect password']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Admin not found']);
}
