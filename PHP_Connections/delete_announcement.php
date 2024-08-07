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
if (json_last_error() !== JSON_ERROR_NONE) {
    echo json_encode(['success' => false, 'message' => 'Invalid JSON input']);
    exit();
}

$announcement_id = isset($data['id']) ? $data['id'] : null;
$password = isset($data['password']) ? $data['password'] : null;

if ($announcement_id === null || $password === null) {
    echo json_encode(['success' => false, 'message' => 'Missing announcement ID or password']);
    exit();
}

// Fetch admin details from the database
$username = $_SESSION['username'];
$query = "SELECT admin_id, name, password FROM admins WHERE username = ?";
$stmt = $mysqli->prepare($query);
if (!$stmt) {
    echo json_encode(['success' => false, 'message' => 'Database query error: ' . $mysqli->error]);
    exit();
}
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
        if (!$announcement_stmt) {
            echo json_encode(['success' => false, 'message' => 'Database query error: ' . $mysqli->error]);
            exit();
        }
        $announcement_stmt->bind_param('i', $announcement_id);
        $announcement_stmt->execute();
        $announcement_result = $announcement_stmt->get_result();

        if ($announcement_result->num_rows === 1) {
            $announcement = $announcement_result->fetch_assoc();
            $announcement_title = $announcement['title']; // Replace with actual column name if different

            // Proceed with deletion
            $delete_query = "DELETE FROM announcement_archive WHERE announcement_id = ?";
            $delete_stmt = $mysqli->prepare($delete_query);
            if (!$delete_stmt) {
                echo json_encode(['success' => false, 'message' => 'Database query error: ' . $mysqli->error]);
                exit();
            }
            $delete_stmt->bind_param('i', $announcement_id);

            if ($delete_stmt->execute()) {
                // Record action in history
                $action = "Deleted announcement";
                $details = "Announcement Title: $announcement_title";
                $sql_history = "INSERT INTO history (action, details, date, user_id) VALUES (?, ?, NOW(), ?)";
                $stmt_history = $mysqli->prepare($sql_history);
                if (!$stmt_history) {
                    echo json_encode(['success' => false, 'message' => 'Database query error: ' . $mysqli->error]);
                    exit();
                }
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

// Close database connection
$mysqli->close();
?>
