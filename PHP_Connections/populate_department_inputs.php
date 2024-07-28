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
$department = [];
$errors = [];
$success = "";

// Get the department ID from the query parameter
$department_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($department_id > 0) {
    // Fetch the department data from the database
    $query = "SELECT * FROM department WHERE department_id = ?";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param('i', $department_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $department = $result->fetch_assoc();
    } else {
        $errors[] = "Department not found.";
    }

    $stmt->close();
} else {
    $errors[] = "Invalid department ID.";
}

$mysqli->close();
?>