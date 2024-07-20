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
$username = $_SESSION['username'];
$archived_by_username = null;
$archived_by_admin_id = null;

// Begin transaction
$mysqli->begin_transaction();

try {
    // Fetch admin ID from username
    $sql_user = "SELECT admin_id FROM admins WHERE username = ?";
    $stmt_user = $mysqli->prepare($sql_user);
    $stmt_user->bind_param("s", $username);
    $stmt_user->execute();
    $result_user = $stmt_user->get_result();

    if ($result_user->num_rows > 0) {
        $admin = $result_user->fetch_assoc();
        $archived_by_admin_id = $admin['admin_id'];
        $archived_by_username = $username;
    } else {
        throw new Exception("User not found");
    }

    // Check if job_id is set in the query string
    if (!isset($_GET['job_id'])) {
        header('Location: ../viewJob.php');
        exit();
    }

    $job_id = intval($_GET['job_id']);

    // Select job from job table
    $sql_select = "SELECT * FROM job WHERE job_id = ?";
    $stmt_select = $mysqli->prepare($sql_select);
    $stmt_select->bind_param("i", $job_id);
    $stmt_select->execute();
    $result_select = $stmt_select->get_result();

    if ($result_select->num_rows > 0) {
        $job = $result_select->fetch_assoc();

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
            $job['place_of_assignment'], 
            $job['status'], 
            $job['proof'], 
            $job['updated_at'], 
            $job['deadline'], 
            $archived_by_username
        );
        $stmt_insert->execute();

        // Record action in history table
        $action = "Archived job";
        $details = "Job Title: {$job['job_title']}";
        $sql_history = "INSERT INTO history (action, details, date, user_id) VALUES (?, ?, NOW(), ?)";
        $stmt_history = $mysqli->prepare($sql_history);
        $stmt_history->bind_param("sss", $action, $details, $archived_by_admin_id);
        $stmt_history->execute();

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
