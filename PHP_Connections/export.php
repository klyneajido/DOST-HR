<?php
include_once 'PHP_Connections/db_connection.php'; // Ensures $pdo is available

// Fetch data
$stmt = $pdo->query("SELECT * FROM applicants");
$data = $stmt->fetchAll();

// Define the filename with current date
$fileName = 'exported_data_' . date('Y-m-d') . '.csv';

// Open a file in write mode ('w')
$file = fopen($fileName, 'w');

// Save the column headers
if (!empty($data)) {
    fputcsv($file, array_keys($data[0]));
}

// Save the data rows
foreach ($data as $row) {
    fputcsv($file, $row);
}

// Close the file
fclose($file);

// Offer the file for download
header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename="' . basename($fileName) . '"');
header('Pragma: no-cache');
header('Expires: 0');
readfile($fileName);

// Clean up
unlink($fileName);
exit;
?>
