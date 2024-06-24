<?php
$errors = array();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    include_once 'db_connection.php';

    // Validate and sanitize input
    $name = isset($_POST['name']) ? $_POST['name'] : '';
    $username = isset($_POST['username']) ? $_POST['username'] : '';
    $email = isset($_POST['email']) ? $_POST['email'] : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';
    $confirmPassword = isset($_POST['confirmPassword']) ? $_POST['confirmPassword'] : '';

    if (empty($name)) {
        $errors['name'] = "Name cannot be empty.";
    }

    if (empty($username)) {
        $errors['username'] = "Username cannot be empty.";
    }

    if (empty($email)) {
        $errors['email'] = "Email cannot be empty.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = "Invalid email format.";
    }

    if (empty($password)) {
        $errors['password'] = "Password cannot be empty.";
    }

    if ($password !== $confirmPassword) {
        $errors['confirmPassword'] = "Passwords do not match.";
    }

    if (empty($errors)) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Prepare and execute SQL statement
        $sql = "INSERT INTO admins (name, username, password, email) VALUES (?, ?, ?, ?)";
        $stmt = $mysqli->prepare($sql);

        if ($stmt) {
            $stmt->bind_param("ssss", $name, $username, $hashed_password, $email);

            if ($stmt->execute()) {
                // Registration successful, redirect to login page
                header("Location: login.php");
                exit(); // Ensure script termination after redirect
            } else {
                $errors['general'] = "Error: " . $stmt->error;
            }

            $stmt->close();
        } else {
            $errors['general'] = "Error in preparing statement: " . $mysqli->error;
        }

        $mysqli->close();
    }
}
?>