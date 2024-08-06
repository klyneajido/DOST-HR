<?php

include_once("db_connection.php");

// Enable error reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = isset($_POST['id']) ? intval($_POST['id']) : '';
    $status = isset($_POST['status']) ? $mysqli->real_escape_string($_POST['status']) : '';

    if (!empty($id) && !empty($status)) {
        $query = "UPDATE applicants SET status='$status' WHERE id=$id";

        if ($mysqli->query($query)) {
            echo 'Status updated successfully';
        } else {
            echo 'Error updating status: ' . $mysqli->error;
        }
    } else {
        echo 'Missing ID or Status';
    }

    $mysqli->close();
} else {
    echo 'Invalid request method';
}
