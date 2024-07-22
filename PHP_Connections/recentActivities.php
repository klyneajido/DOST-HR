<?php
include_once 'db_connection.php';

if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit();
}

$query = "
    SELECT h.action AS activity, h.date AS timestamp, h.details, a.name, a.profile_image 
    FROM history h
    JOIN admins a ON h.user_id = a.admin_id
    ORDER BY h.date DESC
    LIMIT 5
";

$result = $mysqli->query($query);

if ($result) {
    $recent_activities = $result->fetch_all(MYSQLI_ASSOC);
} else {
    echo "Error fetching recent activities: " . $mysqli->error;
    exit();
}
?>