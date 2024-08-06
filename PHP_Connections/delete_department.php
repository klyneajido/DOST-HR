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

// Check if the department_id is set
if (isset($_POST['department_id'])) {
    $department_id = intval($_POST['department_id']);
    $username = $_SESSION['username'];

    // Check if there are jobs associated with this department
    $check_query = "SELECT COUNT(*) AS job_count FROM job WHERE department_id = ?";
    $stmt = $mysqli->prepare($check_query);
    $stmt->bind_param('i', $department_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $stmt->close();

    if ($row['job_count'] > 0) {
        $_SESSION['error'] = "Cannot delete department. There are job posts associated with this department. Delete the jobs first.";
    } else {
        // Fetch department details
        $select_query = "SELECT name FROM department WHERE department_id = ?";
        $stmt = $mysqli->prepare($select_query);
        $stmt->bind_param('i', $department_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $department = $result->fetch_assoc();
        $department_name = $department['name'];
        $stmt->close();

        // If no jobs are associated, delete the department
        $delete_query = "DELETE FROM department WHERE department_id = ?";
        $stmt = $mysqli->prepare($delete_query);
        $stmt->bind_param('i', $department_id);

        if ($stmt->execute()) {
            // Record action in history
            $action = "Deleted department";
            $details = "Department Name: $department_name";
            $sql_history = "INSERT INTO history (action, details, date, user_id) VALUES (?, ?, NOW(), ?)";
            $stmt_history = $mysqli->prepare($sql_history);
            if ($stmt_history === false) {
                $_SESSION['error'] = 'Error preparing history statement: ' . htmlspecialchars($mysqli->error);
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
                        $_SESSION['error'] = 'Failed to record history: ' . htmlspecialchars($stmt_history->error);
                    } else {
                        $_SESSION['success'] = "Department deleted successfully and history recorded.";
                    }
                } else {
                    $_SESSION['error'] = 'Admin not found.';
                }

                $stmt_history->close();
            }
        } else {
            $_SESSION['error'] = "Failed to delete department.";
        }

        $stmt->close();
    }
} else {
    $_SESSION['error'] = "Invalid request.";
}

$mysqli->close();

header('Location: ../departments.php');
exit();
