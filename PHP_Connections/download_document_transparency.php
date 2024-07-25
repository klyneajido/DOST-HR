<?php
// Start session
session_start();
include_once 'db_connection.php';

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    // Redirect to login page if not logged in
    header('Location: ../login.php');
    exit();
}

// Validate input
if (!isset($_GET['id'])) {
    // Redirect or handle error
    header('Location: index.php'); // Redirect to dashboard or appropriate page
    exit();
}

// Sanitize the input
$documentId = $mysqli->real_escape_string($_GET['id']);

// Fetch document contents from the database
$sql = "SELECT * FROM documents WHERE doc_id = '$documentId'";
$result = $mysqli->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $documentName = $row['name']; // Assuming 'name' field contains the document name
    $documentContent = $row['content']; // Assuming 'content' field contains the document content

    // Set headers for download
    header('Content-Description: File Transfer');
    header('Content-Type: application/pdf'); // Adjust MIME type if different file types are used
    header('Content-Disposition: attachment; filename="' . $documentName . '"');
    header('Expires: 0');
    header('Cache-Control: must-revalidate');
    header('Pragma: public');
    header('Content-Length: ' . strlen($documentContent));
    echo $documentContent; // Output the document content directly
    exit;
} else {
    // Handle document not found error
    echo 'Document not found.';
}
?>
