<?php
$errors = array();
$input_data = array();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    include_once 'db_connection.php';

    // Validate and sanitize input
    $name = isset($_POST['name']) ? trim($_POST['name']) : '';
    $username = isset($_POST['username']) ? trim($_POST['username']) : '';
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';
    $confirmPassword = isset($_POST['confirmPassword']) ? $_POST['confirmPassword'] : '';

    // Validate name
    if (empty($name)) {
        $errors['name'] = "Name cannot be empty.";
    } else {
        $input_data['name'] = $name; // Store valid name for repopulating form
    }

    // Validate username
    if (empty($username)) {
        $errors['username'] = "Username cannot be empty.";
    } else {
        // Check if username already exists
        $sql = "SELECT admin_id FROM admins WHERE username = ?";
        $stmt = $mysqli->prepare($sql);
        if ($stmt) {
            $stmt->bind_param("s", $username);
            $stmt->execute();
            $stmt->store_result();
            if ($stmt->num_rows > 0) {
                $errors['username'] = "Username already exists.";
            }
            $stmt->close();
        } else {
            $errors['general'] = "Error in preparing statement: " . $mysqli->error;
        }

        $input_data['username'] = $username; // Store valid username for repopulating form
    }

    // Validate email
    if (empty($email)) {
        $errors['email'] = "Email cannot be empty.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = "Invalid email format.";
    } else {
        // Check if email already exists
        $sql = "SELECT admin_id FROM admins WHERE email = ?";
        $stmt = $mysqli->prepare($sql);
        if ($stmt) {
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $stmt->store_result();
            if ($stmt->num_rows > 0) {
                $errors['email'] = "Email already exists.";
            }
            $stmt->close();
        } else {
            $errors['general'] = "Error in preparing statement: " . $mysqli->error;
        }

        $input_data['email'] = $email; // Store valid email for repopulating form
    }

    // Validate password
    if (empty($password)) {
        $errors['password'] = "Password cannot be empty.";
    } elseif (strlen($password) < 8 || !preg_match('/[0-9]/', $password) || !preg_match('/[\W_]/', $password)) {
        $errors['password'] = "Password must be at least 8 characters long and include at least one number and one special character.";
    }

    // Validate confirm password
    if ($password !== $confirmPassword) {
        $errors['confirmPassword'] = "Passwords do not match.";
    }

    if (empty($errors)) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Insert new user into database
        $sql = "INSERT INTO admins (name, username, password, email) VALUES (?, ?, ?, ?)";
        $stmt = $mysqli->prepare($sql);

        if ($stmt) {
            $stmt->bind_param("ssss", $name, $username, $hashed_password, $email);

            if ($stmt->execute()) {
                // Registration successful, redirect to login page
                header("Location: ../login.php?success=1");
                exit(); // Ensure script termination after redirect
            } else {
                $errors['general'] = "Error: " . $stmt->error;
            }

            $stmt->close();
        } else {
            $errors['general'] = "Error in preparing statement: " . $mysqli->error;
        }

        $mysqli->close();
    } else {
        // Store errors and input data in query string for register.php
        $query_string = http_build_query(array(
            'errors' => $errors,
            'input_data' => $input_data
        ));
        header("Location: ../register.php?" . $query_string);
        exit();
    }
}
?>
