<?php

session_start();
include_once 'db_connection.php';  // Ensure this path is correct

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    // Redirect to login page if not logged in
    header('Location: login.php');
    exit();
}

$user_name = $_SESSION['username'];  // Get username from session

// Initialize error message variable
$errorMsg = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_FILES['document']) && $_FILES['document']['error'] == 0) {
        $fileTmp = $_FILES['document']['tmp_name'];
        $fileName = $_FILES['document']['name'];

        // Read file content into a variable
        $fileContent = file_get_contents($fileTmp);

        // Prepare and execute SQL statement
        try {
            $stmt = $mysqli->prepare("INSERT INTO documents (name, content) VALUES (?, ?)");
            $stmt->bind_param("ss", $fileName, $fileContent); // 's' for string, 'b' for blob

            if ($stmt->execute()) {
                // File uploaded successfully

                // Record action in the history table
                $history_stmt = $mysqli->prepare("
                    INSERT INTO history (action, details, user_id, date) 
                    VALUES (?, ?, (SELECT admin_id FROM admins WHERE username = ?), NOW())
                ");
                $action = "Uploaded Document";
                $details = "Document Name: $fileName";
                $history_stmt->bind_param("sss", $action, $details, $user_name);
                $history_stmt->execute();
                $history_stmt->close();

                header('Location: ../view_transparency.php?upload_status=success');
                exit();
            } else {
                // Failed to upload file
                $errorMsg = 'Failed to upload file.';
            }
        } catch (mysqli_sql_exception $e) {
            // Handle database exception
            $errorMsg = 'Database error: ' . $e->getMessage();
        }
    } else {
        // No file uploaded or there was an error uploading the file
        $errorMsg = 'No file uploaded or there was an error uploading the file.';
    }
} else {
    // Invalid request method
    $errorMsg = 'Invalid request method.';
}
// Redirect back to view_transparency.php with error message as query parameter
header('Location: ../view_transparency.php?upload_status=failed&error=' . urlencode($errorMsg));
exit();
?>
