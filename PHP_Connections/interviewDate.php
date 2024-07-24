<?php
session_start();
include_once 'db_connection.php'; // Adjust the path as needed

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    echo json_encode(['success' => false, 'message' => 'Not authenticated']);
    exit();
}

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve form data
    $applicant_id = isset($_POST['applicant_id']) ? intval($_POST['applicant_id']) : 0;
    $interview_date = isset($_POST['interview_date']) ? $_POST['interview_date'] : '';

    // Validate the interview_date format if needed
    if ($applicant_id > 0 && !empty($interview_date)) {
        // Prepare and execute the update query
        $query = "UPDATE applicants SET interview_date = ? WHERE id = ?";
        if ($stmt = $mysqli->prepare($query)) {
            $stmt->bind_param('si', $interview_date, $applicant_id);
            if ($stmt->execute()) {
                // Redirect back to the page after successful update
                header('Location: ../applicants.php'); // Change 'applicants.php' to the page you want to redirect to
                exit();
            } else {
                echo "Error updating record: " . $mysqli->error;
            }
        } else {
            echo "Error preparing statement: " . $mysqli->error;
        }
    } else {
        echo "Invalid data provided.";
    }
} else {
    echo "Invalid request method.";
}

// Close the database connection
$mysqli->close();
?>
