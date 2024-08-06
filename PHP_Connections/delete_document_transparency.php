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

    // Delete the document record from the database
    $sql = "DELETE FROM documents WHERE doc_id = ?";
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param("i", $documentId);
    if ($stmt->execute()) {
        // Redirect back to the documents page with a success message
        header('Location: ../view_transparency.php?delete_status=success');
        exit();
    } else {
        // Redirect back to the documents page with an error message
        header('Location: ../view_transparency.php?delete_status=failed&error=' . urlencode('Failed to delete document record.'));
        exit();
    }
} else {
    // Redirect back to the documents page with an error message
    header('Location: ../view_transparency.php?delete_status=failed&error=' . urlencode('No document ID provided.'));
    exit();
}
?>
