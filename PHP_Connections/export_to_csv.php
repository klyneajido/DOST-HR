<?php
require '../vendor/autoload.php'; // Path to Composer autoload file
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

require 'db_connection.php';

// Create new Spreadsheet object
$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

// Set header styles
$headerStyle = [
    'font' => [
        'bold' => true,
        'size' => 12,
        'color' => ['rgb' => 'FFFFFF']
    ],
    'fill' => [
        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
        'startColor' => ['rgb' => '244062']
    ],
    'alignment' => [
        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER
    ],
    'borders' => [
        'allBorders' => [
            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
            'color' => ['rgb' => '000000']
        ]
    ]
];

// Set date of exportation
$dateExported = date('F d, Y');

// Merge and center the first row
$sheet->mergeCells('A1:N1');
$sheet->setCellValue('A1', 'Human Resource and Management Office List of Applicants - ' . $dateExported);
$sheet->getStyle('A1')->applyFromArray($headerStyle);

// Get sorting and filtering parameters from GET request
$jobTitleFilter = isset($_GET['job_title']) ? $_GET['job_title'] : '';
$positionFilter = isset($_GET['position']) ? $_GET['position'] : '';
$searchFilter = isset($_GET['search']) ? $_GET['search'] : '';
$statusFilter = isset($_GET['status']) ? $_GET['status'] : '';

// Prepare the SQL query with sorting and filtering
$sql = "SELECT 
            a.job_title AS job_title_position, 
            a.lastname, a.firstname, a.middlename, a.sex, a.address, a.email, a.contact_number, 
            a.course, a.years_of_experience, a.hours_of_training, a.eligibility, a.list_of_awards, a.status
        FROM applicants a 
        WHERE (a.lastname LIKE ? OR 
               a.firstname LIKE ? OR 
               a.email LIKE ? OR
               a.job_title LIKE ?)";

// Apply additional filters
$params = array_fill(0, 4, "%$searchFilter%");
if (!empty($jobTitleFilter)) {
    $sql .= " AND a.job_title = ?";
    $params[] = $jobTitleFilter;
}
if (!empty($positionFilter)) {
    $sql .= " AND a.position_or_unit = ?";
    $params[] = $positionFilter;
}
if (!empty($statusFilter)) {
    $sql .= " AND a.status = ?";
    $params[] = $statusFilter;
}

// Add sorting by job title first, then by ID
$sql .= " ORDER BY a.job_title ASC, a.id ASC";

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
    $sheet->fromArray(array_values($row), NULL, 'A' . $rowNumber);
    $rowNumber++;
}

// Apply border to all data rows
$dataRange = 'A1:N' . ($rowNumber - 1);
$sheet->getStyle($dataRange)->applyFromArray([
    'borders' => [
        'allBorders' => [
            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
            'color' => ['rgb' => '000000']
        ]
    ]
]);

// Generate filename with applicant name and date
$applicantName = 'applicants'; // Default or placeholder name, adjust if necessary
$filename = "{$applicantName}_{$dateExported}.xlsx";

// Save Excel file
$writer = new Xlsx($spreadsheet);
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment; filename="' . $filename . '"');
$writer->save('php://output');
$mysqli->close();
?>
