<?php

// Include the database connection
include_once 'db_connection.php';

// Start session
session_start();

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    // Redirect to login page if not logged in
    header('Location: login.php');
    exit();
}

// Initialize variables
$errors = [];
$success = "";

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the department ID
    $department_id = isset($_POST['department_id']) ? intval($_POST['department_id']) : 0;

    // Get the form data
    $name = isset($_POST['name']) ? trim($_POST['name']) : '';
    $location = isset($_POST['location']) ? trim($_POST['location']) : '';
    $username = $_SESSION['username'];  // Get the username from the session

    // Validate the form data
    if (empty($name)) {
        $errors[] = "Department Name is required.";
    }
    if (empty($location)) {
        $errors[] = "Location is required.";
    }

    // If no errors, update the department
    if (empty($errors)) {
        // Update the department details
        $query = "UPDATE department SET name = ?, location = ? WHERE department_id = ?";
        $stmt = $mysqli->prepare($query);
        $stmt->bind_param('ssi', $name, $location, $department_id);

        if ($stmt->execute()) {
            // Record action in history
            $action = "Updated department";
            $details = "New Name: $name, New Location: $location";
            $sql_history = "INSERT INTO history (action, details, date, user_id) VALUES (?, ?, NOW(), ?)";
            $stmt_history = $mysqli->prepare($sql_history);
            if ($stmt_history === false) {
                $errors[] = 'Error preparing history statement: ' . htmlspecialchars($mysqli->error);
            } else {
                // Fetch admin details from session
                $query_admin = "SELECT admin_id FROM admins WHERE username = ?";
                $stmt_admin = $mysqli->prepare($query_admin);
                $stmt_admin->bind_param('s', $username);
                $stmt_admin->execute();
                $result_admin = $stmt_admin->get_result();
                if ($result_admin->num_rows === 1) {
                    $admin = $result_admin->fetch_assoc();
                    $admin_id = $admin['admin_id'];

                    $stmt_history->bind_param("ssi", $action, $details, $admin_id);
                    if (!$stmt_history->execute()) {
                        $errors[] = 'Failed to record history: ' . htmlspecialchars($stmt_history->error);
                    } else {
                        $success = "Department updated successfully and history recorded.";
                    }
                } else {
                    $errors[] = 'Admin not found.';
                }

                $stmt_history->close();
            }

            header('Location: ../view_departments.php');
            exit();
        } else {
            $errors[] = "Failed to update department.";
        }

        $stmt->close();
    }

    $mysqli->close();
}
?>