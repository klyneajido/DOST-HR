<?php

session_start();
include_once 'db_connection.php';

// Ensure that the request method is POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Retrieve POST parameters
    $applicant_id = isset($_POST['applicant_id']) ? $_POST['applicant_id'] : '';
    $plantilla = isset($_POST['plantilla']) ? $_POST['plantilla'] : '';

    // Validate inputs
    if (empty($applicant_id)) {
        echo 'Error: Applicant ID is required.';
        exit();
    }

    // Prepare SQL query
    $query = "UPDATE applicants SET plantilla = ? WHERE id = ?";
    $stmt = $mysqli->prepare($query);

    // Bind parameters
    $stmt->bind_param('si', $plantilla, $applicant_id);

    // Execute the query
    if ($stmt->execute()) {
        echo 'Success';
    } else {
        echo 'Error: ' . $mysqli->error;
    }

    // Close statement
    $stmt->close();
} else {
    echo 'Invalid request method.';
}

// Close database connection
$mysqli->close();
