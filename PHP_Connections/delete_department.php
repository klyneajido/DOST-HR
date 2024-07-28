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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the department ID from the POST request
    $department_id = isset($_POST['department_id']) ? intval($_POST['department_id']) : 0;

    // Prepare and execute the delete statement
    if ($department_id > 0) {
        $query = "DELETE FROM department WHERE department_id = ?";
        $stmt = $mysqli->prepare($query);
        $stmt->bind_param('i', $department_id);

        if ($stmt->execute()) {
            $_SESSION['success'] = "Department deleted successfully.";
        } else {
            $_SESSION['errors'] = ["Failed to delete department."];
        }

        $stmt->close();
    }
}

$mysqli->close();

// Redirect back to departments page
header('Location: ../departments.php');
exit();
?>
