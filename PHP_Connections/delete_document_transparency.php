<?php
// Start session
session_start();
include_once 'db_connection.php';

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    // Redirect to login page if not logged in
    header('Location: login.php');
    exit();
}

// Check if document ID is provided
if (isset($_GET['id'])) {
    $documentId = $_GET['id'];

    // Fetch document details to delete the file from the server
    $sql = "SELECT * FROM documents WHERE doc_id = ?";
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param("i", $documentId);
    $stmt->execute();
    $result = $stmt->get_result();
    $document = $result->fetch_assoc();

    if ($document) {
        // Delete the document file from the server
        $filePath = 'path/to/document/files/' . $document['file_name']; // Adjust the path as needed
        if (file_exists($filePath)) {
            unlink($filePath);
        }

        // Delete the document record from the database
        $sql = "DELETE FROM documents WHERE doc_id = ?";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("i", $documentId);
        if ($stmt->execute()) {
            // Redirect back to the documents page with a success message
            header('Location: ../transparency.php?delete_status=success');
            exit();
        } else {
            // Redirect back to the documents page with an error message
            header('Location: ../transparency.php?delete_status=failed&error=' . urlencode('Failed to delete document record.'));
            exit();
        }
    } else {
        // Redirect back to the documents page with an error message
        header('Location: ../transparency.php?delete_status=failed&error=' . urlencode('Document not found.'));
        exit();
    }
} else {
    // Redirect back to the documents page with an error message
    header('Location: ../transparency.php?delete_status=failed&error=' . urlencode('No document ID provided.'));
    exit();
}
?>
