<?php

session_start();

// Verify CSRF token
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
        // Handle CSRF token mismatch error
        die("CSRF token validation failed.");
    }

    // Regenerate session ID to prevent session fixation
    session_regenerate_id(true);

    // Initialize variables
    $username = "";
    $password = "";
    $errors = array();

    // Sanitize and validate input data
    $username = isset($_POST['username']) ? filter_var(trim($_POST['username']), FILTER_SANITIZE_STRING) : '';
    $password = isset($_POST['password']) ? filter_var(trim($_POST['password']), FILTER_SANITIZE_STRING) : '';

    // Validate username
    if (empty($username)) {
        $errors['username'] = "Username cannot be empty.";
    }

    // Validate password
    if (empty($password)) {
        $errors['password'] = "Password cannot be empty.";
    }

    // If no errors, proceed to authenticate user
    if (empty($errors)) {
        $response = validateUser($username, $password);

        if ($response['success']) {
            // Store user information in session upon successful login
            $_SESSION['username'] = $username;
            $_SESSION['name'] = $response['name'];

            // Redirect to index.php or any desired location
            header('Location: ../index.php');
            exit();
        } else {
            $errors['general'] = "Invalid username or password."; // General error message
        }
    }

    // If authentication fails, redirect back to login.php with errors
    $query_string = http_build_query(array(
        'errors' => $errors,
        'input_data' => array('username' => $username) // Only username needs to be repopulated
    ));
    header("Location: ../login.php?" . $query_string);
    exit();
}

function validateUser($username, $password)
{
    include_once 'db_connection.php';

    // Query to retrieve hashed password from the database
    $query = "SELECT * FROM admins WHERE username = ?";
    $stmt = $mysqli->prepare($query);

    if ($stmt === false) {
        return array('success' => false, 'error' => 'MySQL prepare error: ' . htmlspecialchars($mysqli->error));
    }

    $stmt->bind_param('s', $username);

    // Execute the query
    if (!$stmt->execute()) {
        return array('success' => false, 'error' => 'Execute failed: ' . htmlspecialchars($stmt->error));
    }

    // Store the result
    $result = $stmt->get_result();

    // Check if the user exists in the database
    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();

        // Verify the password
        if (password_verify($password, $row['password'])) {
            // Return success and user's name
            return array('success' => true, 'name' => $row['name']);
        } else {
            // Return login error
            return array('success' => false, 'error' => 'Invalid username or password.');
        }
    } else {
        // Return login error
        return array('success' => false, 'error' => 'Invalid username or password.');
    }
}
