<?php
$db_host = 'localhost';
$db_user = 'root'; // Correct MySQL username
$db_password = ''; // MySQL password (leave empty if no password)
$db_name = 'DOSTHR';

// Create connection
$mysqli = new mysqli($db_host, $db_user, $db_password, $db_name);

// Check connection
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}
