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
$jobTitleFilter = isset($_GET['job_title']) ? $_GET['job_title'] : '';
$positionFilter = isset($_GET['position']) ? $_GET['position'] : '';
$searchFilter = isset($_GET['search']) ? $_GET['search'] : '';
$statusFilter = isset($_GET['status']) ? $_GET['status'] : '';

// Prepare the SQL query with sorting and filtering
$sql = "SELECT CONCAT(j.job_title, ' ', j.position_or_unit) AS job_title_position, 
               a.lastname, a.firstname, a.middlename, a.sex, a.address, a.email, a.contact_number, 
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

// Add sorting by job title first, then by ID
$sql .= " ORDER BY j.job_title ASC, a.id ASC";

// Prepare and execute the query
$stmt = $mysqli->prepare($sql);
$types = str_repeat('s', count($params));
if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$result = $stmt->get_result();

// Fetch job titles from the result
$jobTitleList = [];
while ($row = $result->fetch_assoc()) {
    if (!in_array($row['job_title_position'], $jobTitleList)) {
        $jobTitleList[] = $row['job_title_position'];
    }
}

// Set the job titles in the second row
$jobTitles = implode(', ', $jobTitleList);
$sheet->mergeCells('A2:N2');
$sheet->setCellValue('A2', $jobTitles);
$sheet->getStyle('A2')->applyFromArray($headerStyle);

// Add CSV column headers
$headers = ['Job Title', 'Last Name', 'First Name', 'Middle Name', 'Sex', 'Address', 'Email', 'Contact Number', 'Course', 'Years of Experience', 'Hours of Training', 'Eligibility', 'List of Awards', 'Status'];
$sheet->fromArray($headers, NULL, 'A3');
$sheet->getStyle('A3:N3')->applyFromArray($headerStyle);

// Set column widths to auto size
foreach (range('A', 'N') as $column) {
    $sheet->getColumnDimension($column)->setAutoSize(true);
}

// Re-execute the query to fetch data for export
$stmt->execute();
$result = $stmt->get_result();

// Fetch the data and write to Excel
$rowNumber = 4; // Start from row 4, since row 3 is for headers
while ($row = $result->fetch_assoc()) {
    $sheet->fromArray($row, NULL, 'A' . $rowNumber);
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
