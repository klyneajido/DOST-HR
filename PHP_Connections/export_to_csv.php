<?php
require '../vendor/autoload.php'; // Path to Composer autoload file
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

require 'db_connection.php';

// Load the template
$templateFile = '../assets/Template.xlsx';
$spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($templateFile);
$sheet = $spreadsheet->getActiveSheet();

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
$sql = "SELECT a.job_title,a.position_or_unit, a.lastname, a.firstname, a.middlename, a.sex, a.address, a.email, a.contact_number, 
               a.course, a.years_of_experience, a.hours_of_training, a.eligibility, a.list_of_awards, a.status, a.application_date
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

// Fetch the data and write to the template
$rowNumber = 2; // Assuming headers are in row 1
while ($row = $result->fetch_assoc()) {
    $column = 1; // Starting column
    foreach ($row as $cell) {
        // Convert column number to letter
        $columnLetter = PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($column);
        $sheet->setCellValue($columnLetter . $rowNumber, $cell);
        $column++;
    }
    $rowNumber++;
}

// Save the populated file
$writer = new Xlsx($spreadsheet);
$filename = 'populated_template.xlsx';
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment; filename="' . $filename . '"');
$writer->save('php://output');
$mysqli->close();
?>
