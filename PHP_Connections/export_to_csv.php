<?php
require 'db_connection.php';

// Output CSV headers
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=applicants.csv');
$output = fopen('php://output', 'w');

// Add CSV column headers
fputcsv($output, array('ID', 'Last Name', 'First Name', 'Middle Name', 'Sex', 'Address', 'Email', 'Contact Number', 'Course', 'Years of Experience', 'Hours of Training', 'Eligibility', 'List of Awards', 'Status'));

// Get sorting and filtering parameters from GET request
$sortColumn = isset($_GET['sort_column']) ? $_GET['sort_column'] : 'id';
$sortDirection = isset($_GET['sort_direction']) && $_GET['sort_direction'] === 'ASC' ? 'ASC' : 'DESC';
$jobTitleFilter = isset($_GET['job_title']) ? $_GET['job_title'] : '';
$positionFilter = isset($_GET['position']) ? $_GET['position'] : '';
$searchFilter = isset($_GET['search']) ? $_GET['search'] : '';
$statusFilter = isset($_GET['status']) ? $_GET['status'] : '';

// Validate sort column
$validSortColumns = ['id', 'lastname', 'firstname', 'middlename', 'sex', 'address', 'email', 'contact_number', 'course', 'years_of_experience', 'hours_of_training', 'eligibility', 'list_of_awards', 'status'];
if (!in_array($sortColumn, $validSortColumns)) {
    $sortColumn = 'id'; // Default to 'id' if invalid
}

// Prepare the SQL query with sorting and filtering
$sql = "SELECT a.id, a.lastname, a.firstname, a.middlename, a.sex, a.address, a.email, a.contact_number, 
               a.course, a.years_of_experience, a.hours_of_training, a.eligibility, a.list_of_awards, a.status
        FROM applicants a 
        LEFT JOIN job j ON a.job_id = j.job_id
        WHERE (a.lastname LIKE ? OR 
               a.firstname LIKE ? OR 
               a.email LIKE ? OR
               j.job_title LIKE ? OR
               j.position_or_unit LIKE ?)";

// Apply additional filters
$params = array_fill(0, 5, "%$searchFilter%");
if (!empty($jobTitleFilter)) {
    $sql .= " AND j.job_title = ?";
    $params[] = $jobTitleFilter;
}
if (!empty($positionFilter)) {
    $sql .= " AND j.position_or_unit = ?";
    $params[] = $positionFilter;
}
if (!empty($statusFilter)) {
    $sql .= " AND a.status = ?";
    $params[] = $statusFilter;
}

// Add sorting
$sql .= " ORDER BY $sortColumn $sortDirection";

// Prepare and execute the query
$stmt = $mysqli->prepare($sql);
$types = str_repeat('s', count($params));
if (!empty($params)) {
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
