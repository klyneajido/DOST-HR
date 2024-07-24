<?php
session_start();
include_once 'db_connection.php';

if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $applicantId = $_POST['id'];

    // Prevent SQL injection
    $applicantId = mysqli_real_escape_string($mysqli, $applicantId);

    // Perform deletion query
    $deleteQuery = "DELETE FROM applicants WHERE id = '$applicantId'";

    if (mysqli_query($mysqli, $deleteQuery)) {
        echo "Applicant deleted successfully!";
    } else {
        echo "Error deleting applicant: " . mysqli_error($mysqli);
    }
} else {
    echo "Invalid request. Please provide an applicant ID.";
}
?>
