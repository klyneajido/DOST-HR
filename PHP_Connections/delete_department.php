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
        // If no jobs are associated, delete the department
        $delete_query = "DELETE FROM department WHERE department_id = ?";
        $stmt = $mysqli->prepare($delete_query);
        $stmt->bind_param('i', $department_id);

        if ($stmt->execute()) {
            $_SESSION['success'] = "Department deleted successfully.";
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
?>
