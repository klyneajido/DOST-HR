<?php
// Start session
session_start();

// Include the database connection file
require 'db_connection.php';

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    // Redirect to login page if not logged in
    header('Location: ../login.php');
    exit();
}

// Get user's name and profile image from session
$user_name = $_SESSION['username'];
$profile_image_path = isset($_SESSION['profile_image']) ? $_SESSION['profile_image'] : 'assets/img/profiles/default-profile.png';

// Initialize an array to store errors
$errors = [];
$success = '';

// Check if the form has been submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Retrieve form data
    $name = trim($_POST['name']);
    $location = trim($_POST['location']);

    // Validate form data
    if (empty($name)) {
        $errors[] = 'Department name is required.';
    }

    if (empty($location)) {
        $errors[] = 'Location is required.';
    }

    // If there are no errors, proceed with inserting the data into the database
    if (empty($errors)) {
        // Begin transaction
        $mysqli->begin_transaction();

        try {
            // Insert department into the database
            $stmt = $mysqli->prepare("INSERT INTO department (name, location) VALUES (?, ?)");
            if ($stmt === false) {
                throw new Exception('Error preparing statement: ' . htmlspecialchars($mysqli->error));
            }

            $stmt->bind_param("ss", $name, $location);
            if (!$stmt->execute()) {
                throw new Exception('Failed to add department: ' . htmlspecialchars($stmt->error));
            }
            
            // Get the last inserted ID for history logging
            $department_id = $mysqli->insert_id;

            // Record action in history
            $action = "Added new department";
            $details = "Department Name: $name";
            $sql_history = "INSERT INTO history (action, details, date, user_id) VALUES (?, ?, NOW(), ?)";
            $stmt_history = $mysqli->prepare($sql_history);
            if ($stmt_history === false) {
                throw new Exception('Error preparing history statement: ' . htmlspecialchars($mysqli->error));
            }

            // Fetch admin details from session
            $username = $_SESSION['username'];
            $query = "SELECT admin_id FROM admins WHERE username = ?";
            $stmt_admin = $mysqli->prepare($query);
            $stmt_admin->bind_param('s', $username);
            $stmt_admin->execute();
            $result_admin = $stmt_admin->get_result();
            if ($result_admin->num_rows === 1) {
                $admin = $result_admin->fetch_assoc();
                $admin_id = $admin['admin_id'];

                $stmt_history->bind_param("ssi", $action, $details, $admin_id);
                if (!$stmt_history->execute()) {
                    throw new Exception('Failed to record history: ' . htmlspecialchars($stmt_history->error));
                }
            } else {
                throw new Exception('Admin not found.');
            }

            // Commit transaction
            $mysqli->commit();

            $success = 'Department added and history recorded successfully.';
        } catch (Exception $e) {
            // Rollback transaction if any error occurs
            $mysqli->rollback();
            $errors[] = $e->getMessage();
        }
    }
}
?>
