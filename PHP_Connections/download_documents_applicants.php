<?php
include_once 'db_connection.php';

if (isset($_GET['id'])) {
    $applicantId = mysqli_real_escape_string($mysqli, $_GET['id']);

    // Fetch applicant details along with job title and names from the applicants table
    $query = "SELECT lastname, firstname, job_title, application_letter, personal_data_sheet, 
                     performance_rating, eligibility_rating_license, transcript_of_records, 
                     certificate_of_employment, proof_of_trainings_seminars, proof_of_rewards
              FROM applicants WHERE id = '$applicantId'";
    $result = mysqli_query($mysqli, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);

        // Define folder name based on applicant details
        $folderName = "{$row['lastname']}_{$row['firstname']}_{$row['job_title']}";

        // Create a ZIP file to store all documents
        $zip = new ZipArchive();
        $zipFileName = tempnam(sys_get_temp_dir(), 'zip');
        if ($zip->open($zipFileName, ZipArchive::CREATE) === TRUE) {

            // Add files to the ZIP
            $files = [
                'application_letter' => $row['application_letter'],
                'personal_data_sheet' => $row['personal_data_sheet'],
                'performance_rating' => $row['performance_rating'],
                'eligibility_rating_license' => $row['eligibility_rating_license'],
                'transcript_of_records' => $row['transcript_of_records'],
                'certificate_of_employment' => $row['certificate_of_employment'],
                'proof_of_trainings_seminars' => $row['proof_of_trainings_seminars'],
                'proof_of_rewards' => $row['proof_of_rewards']
            ];

            foreach ($files as $label => $fileData) {
                if (!empty($fileData)) {
                    // Define file path within the ZIP with applicant's name
                    $filePath = "$folderName/{$row['lastname']}_{$row['firstname']}_{$label}.pdf"; // Assuming PDF format
                    // Add the file to the ZIP
                    $zip->addFromString($filePath, $fileData);
                }
            }

            // Close the ZIP file
            $zip->close();

            // Send the ZIP file to the browser for download
            header('Content-Type: application/zip');
            header('Content-Disposition: attachment; filename="' . $folderName . '.zip"');
            header('Content-Length: ' . filesize($zipFileName));
            readfile($zipFileName);

            // Clean up temporary file
            unlink($zipFileName);
        } else {
            echo "Failed to create ZIP file.";
        }
    } else {
        // Debug: Log the error
        error_log("Applicant query failed. Applicant ID: $applicantId. Error: " . mysqli_error($mysqli));
        echo "Failed to retrieve applicant data.";
    }
} else {
    echo "No applicant ID provided.";
}
?>
