<?php
session_start();
include_once 'PHP_Connections/db_connection.php';

if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit();
}

if (isset($_GET['id'])) {
    $jobarchive_id = $mysqli->real_escape_string($_GET['id']);
    
    // Fetch the job details from the job_archive table
    $fetch_query = "SELECT job_title, position_or_unit, description, education_requirement, experience_or_training, duties_and_responsibilities, salary, department_id, place_of_assignment, status, created_at, updated_at, deadline 
                    FROM job_archive 
                    WHERE jobarchive_id = '$jobarchive_id'";
    $result = $mysqli->query($fetch_query);

    if ($result && $result->num_rows === 1) {
        $job = $result->fetch_assoc();

        // Insert the job details into the job table (excluding archived_by)
        $insert_query = "
            INSERT INTO job (job_title, position_or_unit, description, education_requirement, experience_or_training, duties_and_responsibilities, salary, department_id, place_of_assignment, status, created_at, updated_at, deadline)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ";
        $stmt = $mysqli->prepare($insert_query);
        $stmt->bind_param(
            'ssssssdssssss',
            $job['job_title'],
            $job['position_or_unit'],
            $job['description'],
            $job['education_requirement'],
            $job['experience_or_training'],
            $job['duties_and_responsibilities'],
            $job['salary'],
            $job['department_id'],
            $job['place_of_assignment'],
            $job['status'],
            $job['created_at'],
            $job['updated_at'],
            $job['deadline']
        );

        if ($stmt->execute()) {
            // Delete the job from the job_archive table
            $delete_query = "DELETE FROM job_archive WHERE jobarchive_id = '$jobarchive_id'";
            if ($mysqli->query($delete_query)) {
                header('Location: archive.php?tab=jobs&msg=restored');
                exit();
            } else {
                echo "Error deleting archived job: " . $mysqli->error;
            }
        } else {
            echo "Error restoring job: " . $stmt->error;
        }
    } else {
        echo "Job not found in archive.";
    }
} else {
    echo "ID parameter is missing.";
}
?>
