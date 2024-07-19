<?php
$db_host = 'localhost';
$db_user = 'root'; // Correct MySQL username
$db_password = ''; // MySQL password (leave empty if no password)
$db_name = 'dosthr';
$dsn = "mysql:host=$db_host;dbname=$db_name";

// Create connection
$mysqli = new mysqli($db_host, $db_user, $db_password, $db_name);

// Check connection
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
} 
$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES => false,
];

try {
    $pdo = new PDO($dsn, $db_user, $db_password, $options);
} catch (PDOException $e) {
    die("Error connecting to database: " . $e->getMessage());
}

