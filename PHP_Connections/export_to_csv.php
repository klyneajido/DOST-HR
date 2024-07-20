<?php
require 'db_connection.php';

header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=applicants.csv');
$output = fopen('php://output', 'w');

// Add CSV column headers
fputcsv($output, array('ID', 'Last Name', 'First Name', 'Middle Name', 'Sex', 'Address', 'Email', 'Contact Number', 'Course', 'Years of Experience', 'Hours of Training', 'Eligibility', 'List of Awards'));

// Get sorting and filtering parameters from GET request
$sortColumn = isset($_GET['sort_column']) ? $_GET['sort_column'] : 'id';
$sortDirection = isset($_GET['sort_direction']) && $_GET['sort_direction'] === 'ASC' ? 'ASC' : 'DESC';
$jobTitleFilter = isset($_GET['job_title']) ? $_GET['job_title'] : '';
$positionFilter = isset($_GET['position']) ? $_GET['position'] : '';
$searchFilter = isset($_GET['search']) ? $_GET['search'] : '';

// Validate sort column
$validSortColumns = ['id', 'lastname', 'firstname', 'middlename', 'sex', 'address', 'email', 'contact_number', 'course', 'years_of_experience', 'hours_of_training', 'eligibility', 'list_of_awards'];
if (!in_array($sortColumn, $validSortColumns)) {
    $sortColumn = 'id'; // Default to 'id' if invalid
}

// Prepare the SQL query with sorting and filtering
$sql = "SELECT id, lastname, firstname, middlename, sex, address, email, contact_number, course, years_of_experience, hours_of_training, eligibility, list_of_awards FROM applicants";

// Apply filters if provided
$filters = [];
$params = [];
if (!empty($jobTitleFilter)) {
    $filters[] = "job_title = ?";
    $params[] = $jobTitleFilter;
}
if (!empty($positionFilter)) {
    $filters[] = "position = ?";
    $params[] = $positionFilter;
}
if (!empty($searchFilter)) {
    $filters[] = "(lastname LIKE ? OR firstname LIKE ? OR middlename LIKE ? OR address LIKE ? OR email LIKE ?)";
    $searchTerm = "%$searchFilter%";
    $params[] = $searchTerm;
    $params[] = $searchTerm;
    $params[] = $searchTerm;
    $params[] = $searchTerm;
    $params[] = $searchTerm;
}
if (!empty($filters)) {
    $sql .= ' WHERE ' . implode(' AND ', $filters);
}

// Add sorting
$sql .= " ORDER BY $sortColumn $sortDirection";

$stmt = $mysqli->prepare($sql);

if (!empty($params)) {
    $types = str_repeat('s', count($params));
    $stmt->bind_param($types, ...$params);
}

$stmt->execute();
$result = $stmt->get_result();

// Fetch the data and write to CSV
while ($row = $result->fetch_assoc()) {
    fputcsv($output, $row);
}

fclose($output);
$mysqli->close();
?>
