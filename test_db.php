<?php
include_once 'db_connection.php';

if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
} else {
    echo "Connected successfully";
}
?>
