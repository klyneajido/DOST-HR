<?php
session_start();

$username = "";
$password = "";
$errors = array();
$input_data = array();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize input data
    $username = isset($_POST['username']) ? trim($_POST['username']) : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';

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
            $_SESSION['username'] = $username;
            $_SESSION['name'] = $response['name']; // Store user's name in the session
            header('Location: ../index.php');
            exit();
        } else {
            $errors['general'] = $response['error'];
        }
    }

    // Store errors and input data in query string for login.php
    $query_string = http_build_query(
        array(
            'errors' => $errors,
            'input_data' => array('username' => $username) // Only username needs to be repopulated
        )
    );
    header("Location: ../login.php?" . $query_string);
    exit();
}

function validateUser($username, $password) {
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

