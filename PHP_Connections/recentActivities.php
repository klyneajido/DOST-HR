<?php

include_once 'db_connection.php';

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit();
}

// Query to fetch recent activities with admin details
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

    // Format timestamps
    foreach ($recent_activities as &$activity) {
        $date = new DateTime($activity['timestamp']);
        $activity['formatted_timestamp'] = $date->format('F j, Y, g:i A'); // Format as "June 28, 2024, 8:30 AM"
    }
    unset($activity); // Unset reference to avoid unexpected issues
} else {
    echo "Error fetching recent activities: " . $mysqli->error;
    exit();
}
