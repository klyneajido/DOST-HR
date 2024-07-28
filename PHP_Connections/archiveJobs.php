<?php
session_start();
include_once 'db_connection.php';

// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    header('Location: ../login.php');
    exit();
}

// Get username from session
$username = $_SESSION['username'];
$archived_by_name = null;
$archived_by_admin_id = null;

// Begin transaction
$mysqli->begin_transaction();

try {
    // Fetch admin ID and name from username
    $sql_user = "SELECT admin_id, name FROM admins WHERE username = ?";
    $stmt_user = $mysqli->prepare($sql_user);
    $stmt_user->bind_param("s", $username);
    $stmt_user->execute();
    $result_user = $stmt_user->get_result();

    if ($result_user->num_rows > 0) {
        $admin = $result_user->fetch_assoc();
        $archived_by_admin_id = $admin['admin_id'];
        $archived_by_name = $admin['name'];
    } else {
        throw new Exception("User not found");
    }

    // Check if job_id is set in the query string
    if (!isset($_GET['job_id'])) {
        header('Location: ../viewJobs.php');
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

        // Update applicants table with job details
        $sql_update_applicants = "UPDATE applicants 
                                  SET job_title = ?, position_or_unit = ? 
                                  WHERE job_id = ?";
        $stmt_update_applicants = $mysqli->prepare($sql_update_applicants);
        $stmt_update_applicants->bind_param("ssi", 
            $job['job_title'], 
            $job['position_or_unit'], 
            $job_id
        );

        if (!$stmt_update_applicants->execute()) {
            throw new Exception("Error updating applicants with job details: " . $stmt_update_applicants->error);
        }

        // Insert job into job_archive
        $sql_insert = "INSERT INTO job_archive (job_title, position_or_unit, description, salary, department_id, place_of_assignment, status, created_at, updated_at, deadline, archived_by)
                       VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt_insert = $mysqli->prepare($sql_insert);
        $stmt_insert->bind_param("sssddssssss", 
            $job['job_title'], 
            $job['position_or_unit'], 
            $job['description'], 
            $job['salary'], 
            $job['department_id'], 
            $job['place_of_assignment'], 
            $job['status'], 
            $job['created_at'], 
            $job['updated_at'], 
            $job['deadline'], 
            $archived_by_name
        );

        if (!$stmt_insert->execute()) {
            throw new Exception("Error inserting job into archive: " . $stmt_insert->error);
        }

        // Fetch the last inserted job archive ID
        $archived_job_id = $mysqli->insert_id;

        // Fetch job requirements
        $sql_req_select = "SELECT * FROM job_requirements WHERE job_id = ?";
        $stmt_req_select = $mysqli->prepare($sql_req_select);
        $stmt_req_select->bind_param("i", $job_id);
        $stmt_req_select->execute();
        $result_req_select = $stmt_req_select->get_result();

        if ($result_req_select->num_rows > 0) {
            while ($req = $result_req_select->fetch_assoc()) {
                // Insert job requirements into job_requirements_archive table
                $sql_req_insert = "INSERT INTO job_requirements_archive (requirement_id, job_id, requirement_type, requirement_text, archived_at, jobarchive_id)
                                   VALUES (?, ?, ?, ?, NOW(), ?)";

                $stmt_req_insert = $mysqli->prepare($sql_req_insert);
                $stmt_req_insert->bind_param("iissi", 
                    $req['requirement_id'],  // Assuming requirement_id is needed
                    $archived_job_id,  // Use archived_job_id
                    $req['requirement_type'], 
                    $req['requirement_text'],
                    $archived_job_id // Use archived_job_id for the new foreign key
                );

                if (!$stmt_req_insert->execute()) {
                    throw new Exception("Error inserting job requirement into archive: " . $stmt_req_insert->error);
                }
            }
        }

        // Record action in history table
        $action = "Archived job";
        $details = "Job Title: {$job['job_title']}";
        $sql_history = "INSERT INTO history (action, details, date, user_id) VALUES (?, ?, NOW(), ?)";
        $stmt_history = $mysqli->prepare($sql_history);
        $stmt_history->bind_param("ssi", $action, $details, $archived_by_admin_id);
        if (!$stmt_history->execute()) {
            throw new Exception("Error recording action in history: " . $stmt_history->error);
        }

        // Delete job row from job table
        $sql_delete = "DELETE FROM job WHERE job_id = ?";
        $stmt_delete = $mysqli->prepare($sql_delete);
        $stmt_delete->bind_param("i", $job_id);
        if (!$stmt_delete->execute()) {
            throw new Exception("Error deleting job: " . $stmt_delete->error);
        }

        // Commit transaction
        $mysqli->commit();
        echo "Job archived successfully<br>";
        header('Location: ../viewJob.php?message=Job archived successfully');
    } else {
        throw new Exception("Job not found");
    }
} catch (Exception $e) {
    // Rollback transaction if any error occurs
    $mysqli->rollback();
    // Display the error message directly
    echo "Exception: " . $e->getMessage(); // Display exception message
    error_log("Exception: " . $e->getMessage()); // Log exception message
    header('Location: ../viewJob.php?error=' . urlencode($e->getMessage()));
}
?>
