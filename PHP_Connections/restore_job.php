<?php

session_start();
include_once 'db_connection.php';

if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit();
}

if (isset($_GET['id'])) {
    $jobarchive_id = intval($_GET['id']); // Use intval to ensure it's an integer

    // Define the default department ID
    $default_department_id = 5;

    // Begin transaction
    $mysqli->begin_transaction();

    try {
        // Fetch the job details from the job_archive table
        $fetch_query = "SELECT job_title, position_or_unit, description, salary, department_id, place_of_assignment, status, created_at, deadline 
                        FROM job_archive 
                        WHERE jobarchive_id = ?";
        $stmt = $mysqli->prepare($fetch_query);
        $stmt->bind_param('i', $jobarchive_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result && $result->num_rows === 1) {
            $job = $result->fetch_assoc();

            // Check if the department still exists
            $check_department_query = "SELECT COUNT(*) AS dept_count FROM department WHERE department_id = ?";
            $stmt_check_dept = $mysqli->prepare($check_department_query);
            $stmt_check_dept->bind_param('i', $job['department_id']);
            $stmt_check_dept->execute();
            $result_check_dept = $stmt_check_dept->get_result();
            $dept_row = $result_check_dept->fetch_assoc();
            $stmt_check_dept->close();

            if ($dept_row['dept_count'] == 0) {
                // Set department_id to the default value if the department does not exist
                $job['department_id'] = $default_department_id;
            }

            // Set the current timestamp for updated_at
            $current_time = date("Y-m-d H:i:s");

            // Insert the job details into the job table
            $insert_query = "
                INSERT INTO job (job_title, position_or_unit, description, salary, department_id, place_of_assignment, status, created_at, updated_at, deadline)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
            ";
            $stmt_insert = $mysqli->prepare($insert_query);
            $stmt_insert->bind_param(
                'ssssssssis',
                $job['job_title'],
                $job['position_or_unit'],
                $job['description'],
                $job['salary'],
                $job['department_id'],
                $job['place_of_assignment'],
                $job['status'],
                $job['created_at'],
                $current_time, // Use current timestamp for updated_at
                $job['deadline']
            );

            if ($stmt_insert->execute()) {
                $new_job_id = $mysqli->insert_id; // Get the new job ID

                // Restore job requirements from job_requirements_archive
                $fetch_req_query = "SELECT requirement_id, requirement_type, requirement_text FROM job_requirements_archive WHERE jobarchive_id = ?";
                $stmt_req = $mysqli->prepare($fetch_req_query);
                $stmt_req->bind_param('i', $jobarchive_id);
                $stmt_req->execute();
                $result_req = $stmt_req->get_result();

                while ($req = $result_req->fetch_assoc()) {
                    $insert_req_query = "INSERT INTO job_requirements (id, job_id, requirement_type, requirement_text)
                                         VALUES (?, ?, ?, ?)";
                    $stmt_req_insert = $mysqli->prepare($insert_req_query);
                    $stmt_req_insert->bind_param(
                        'iiss',
                        $req['requirement_id'],
                        $new_job_id, // Use the new job ID
                        $req['requirement_type'],
                        $req['requirement_text']
                    );

                    if (!$stmt_req_insert->execute()) {
                        throw new Exception("Error inserting job requirement: " . $stmt_req_insert->error);
                    }
                }

                // Delete the job from the job_archive table
                $delete_query = "DELETE FROM job_archive WHERE jobarchive_id = ?";
                $stmt_delete = $mysqli->prepare($delete_query);
                $stmt_delete->bind_param('i', $jobarchive_id);

                if ($stmt_delete->execute()) {
                    // Delete the job requirements from the job_requirements_archive table
                    $delete_req_query = "DELETE FROM job_requirements_archive WHERE jobarchive_id = ?";
                    $stmt_req_delete = $mysqli->prepare($delete_req_query);
                    $stmt_req_delete->bind_param('i', $jobarchive_id);

                    if ($stmt_req_delete->execute()) {
                        // Commit transaction
                        $mysqli->commit();
                        header('Location: ../view_archives_jobs.php?tab=jobs&msg=restored');
                        exit();
                    } else {
                        throw new Exception("Error deleting archived job requirements: " . $stmt_req_delete->error);
                    }
                } else {
                    throw new Exception("Error deleting archived job: " . $stmt_delete->error);
                }
            } else {
                throw new Exception("Error restoring job: " . $stmt_insert->error);
            }
        } else {
            throw new Exception("Job not found in archive.");
        }
    } catch (Exception $e) {
        // Rollback transaction if any error occurs
        $mysqli->rollback();
        $_SESSION['error'] = $e->getMessage();
        header('Location: ../view_archives_jobs.php?tab=jobs&msg=error');
        exit();
    }
} else {
    $_SESSION['error'] = "ID parameter is missing.";
    header('Location: ../view_archives_jobs.php?tab=jobs&msg=error');
    exit();
}
