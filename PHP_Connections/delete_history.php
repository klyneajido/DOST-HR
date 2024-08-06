<?php

session_start();
include_once 'db_connection.php';

header('Content-Type: application/json');

if (!isset($_SESSION['username'])) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit();
}

$username = $_SESSION['username'];

$data = json_decode(file_get_contents('php://input'), true);
$historyId = $data['id'];
$adminPassword = $data['password'];

if (!$historyId || !$adminPassword) {
    echo json_encode(['success' => false, 'message' => 'Invalid input']);
    exit();
}

$query = "SELECT password FROM admins WHERE username = ?";
$stmt = $mysqli->prepare($query);
$stmt->bind_param('s', $username);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 1) {
    $admin = $result->fetch_assoc();
    if (password_verify($adminPassword, $admin['password'])) {
        $deleteQuery = "DELETE FROM history WHERE id = ?";
        $deleteStmt = $mysqli->prepare($deleteQuery);
        $deleteStmt->bind_param('i', $historyId);
        if ($deleteStmt->execute()) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to delete history record']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Invalid password']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Admin not found']);
}

$mysqli->close();
