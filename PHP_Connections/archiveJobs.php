<?php
// Start session
session_start();
include_once 'db_connection.php';

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    // Redirect to login page if not logged in
    header('Location: login.php');
    exit();
}

// Get username from session
$archived_by = $_SESSION['username'];

// Check if job_id is set in the query string
if (!isset($_GET['job_id'])) {
    header('Location: ../viewJob.php');
    exit();
}

$job_id = intval($_GET['job_id']);

// Begin transaction
$mysqli->begin_transaction();

try {
    // Select job from job table
    $sql_select = "SELECT * FROM job WHERE job_id = ?";
    $stmt_select = $mysqli->prepare($sql_select);
    $stmt_select->bind_param("i", $job_id);
    $stmt_select->execute();
    $result_select = $stmt_select->get_result();

    if ($result_select->num_rows > 0) {
        $job = $result_select->fetch_assoc();

        // Debugging: Print job details
        error_log("Job details: " . print_r($job, true));

        // Insert job into job_archive table
        $sql_insert = "INSERT INTO job_archive (jobarchive_id, job_title, position_or_unit, description, education_requirement, experience_or_training, duties_and_responsibilities, salary, department_id, place_of_assignment, status, proof, created_at, deadline, archived_by, updated_at)
                       VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())";
        $stmt_insert = $mysqli->prepare($sql_insert);
        $stmt_insert->bind_param("issssssdissssss", 
            $job['job_id'], 
            $job['job_title'], 
            $job['position_or_unit'], 
            $job['description'], 
            $job['education_requirement'], 
            $job['experience_or_training'], 
            $job['duties_and_responsibilities'], 
            $job['salary'], 
            $job['department_id'], 
            $job['place_of_assignment'], // Fixed column name
            $job['status'], 
            $job['proof'], 
            $job['updated_at'], 
            $job['deadline'], 
            $archived_by
        );
        $stmt_insert->execute();

        // Debugging: Print the insert statement and bound values
        error_log("Insert Statement: " . $sql_insert);
        error_log("Bound Values: " . print_r([$job['job_id'], $job['job_title'], $job['position_or_unit'], $job['description'], $job['education_requirement'], $job['experience_or_training'], $job['duties_and_responsibilities'], $job['salary'], $job['department_id'], $job['place_of_assignment'], $job['status'], $job['proof'], $job['created_at'], $job['updated_at'], $job['deadline'], $archived_by], true));

        // Delete job from job table
        $sql_delete = "DELETE FROM job WHERE job_id = ?";
        $stmt_delete = $mysqli->prepare($sql_delete);
        $stmt_delete->bind_param("i", $job_id);
        $stmt_delete->execute();

        // Commit transaction
        $mysqli->commit();
        header('Location: ../viewJob.php?message=Job archived successfully');
    } else {
        throw new Exception("Job not found");
    }
} catch (Exception $e) {
    // Rollback transaction if any error occurs
    $mysqli->rollback();
    header('Location: ../viewJob.php?error=' . urlencode($e->getMessage()));
}
?>
