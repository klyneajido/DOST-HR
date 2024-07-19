<?php
include_once 'PHP_Connections/db_connection.php';

if (isset($_GET['id'])) {
    $applicantId = mysqli_real_escape_string($mysqli, $_GET['id']);

    // Fetch applicant details
    $query = "SELECT lastname, firstname, job_id, application_letter, personal_data_sheet, 
                     performance_rating, eligibility_rating_license, transcript_of_records, 
                     certificate_of_employment, proof_of_trainings_seminars, proof_of_rewards
              FROM applicants WHERE id = '$applicantId'";
    $result = mysqli_query($mysqli, $query);

    if ($result) {
        $row = mysqli_fetch_assoc($result);

        // Fetch job title from the job table
        $jobId = $row['job_id'];
        $jobQuery = "SELECT job_title FROM job WHERE job_id = '$jobId'"; // Adjust column name here if needed
        $jobResult = mysqli_query($mysqli, $jobQuery);

        if ($jobResult) {
            $jobRow = mysqli_fetch_assoc($jobResult);
            $jobTitle = $jobRow['job_title'];

            // Define folder name based on applicant details
            $folderName = "{$row['lastname']}_{$row['firstname']}_{$jobTitle}";
            $folderPath = "/Users/User/Desktop/uploads/$folderName/";

            // Create the folder if it does not exist
            if (!file_exists($folderPath)) {
                mkdir($folderPath, 0777, true);
            }

            // Create files for each document
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
                    // Define file path
                    $filePath = $folderPath . $label . '.pdf'; // Assuming PDF format
                    // Save the file to the folder
                    file_put_contents($filePath, $fileData);
                }
            }

            // Provide a link to the created folder
            echo "Documents have been saved to: $folderPath";
        } else {
            echo "Failed to retrieve job title.";
        }
    } else {
        echo "Failed to retrieve applicant data.";
    }
} else {
    echo "No applicant ID provided.";
}
?>

