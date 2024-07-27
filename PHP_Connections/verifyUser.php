<?php
include('db_connection.php');

if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit();
}

$username = $_SESSION['username'];
$user_name = isset($_SESSION['username']) ? $_SESSION['username'] : 'Guest';
$profile_image_path = isset($_SESSION['profile_image']) ? $_SESSION['profile_image'] : 'assets/img/profiles/default-profile.png';

// Ensure connection is valid
if ($mysqli->ping()) {
    $query = "SELECT authority FROM admins WHERE username = ?";
    if ($stmt = $mysqli->prepare($query)) {
        $stmt->bind_param('s', $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();
            $user_authority = $user['authority'];
        } else {
            echo "User not found.";
            exit();
        }
        $stmt->close();
    } else {
        echo "Failed to prepare statement: " . $mysqli->error;
        exit();
    }
} else {
    echo "Database connection is not valid.";
    exit();
}

?>
