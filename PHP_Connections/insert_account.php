<?php

include("db_connection.php");

session_start();

if (!isset($_SESSION['username'])) {
    header('Location: ../login.php');
    exit();
}

$user_name = $_SESSION['username'];
$profile_image_path = isset($_SESSION['profile_image']) ? $_SESSION['profile_image'] : 'assets/img/profiles/default-profile.png';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirmPassword'];
    $authority = $_POST['authority'];

    // Error message array
    $errors = [];

    // Validate input
    if (empty($name)) {
        $errors['name'] = "Name is required.";
    }
    if (empty($username)) {
        $errors['username'] = "Username is required.";
    }
    if (empty($email)) {
        $errors['email'] = "Email is required.";
    }
    if (empty($password)) {
        $errors['password'] = "Password is required.";
    }
    if ($password !== $confirmPassword) {
        $errors['confirmPassword'] = "Passwords do not match.";
    }

    // Check if username or email already exists
    if (empty($errors)) {
        $sql = "SELECT 1 FROM admins WHERE username = ? OR email = ?";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("ss", $username, $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $errors['username'] = "Username or email already exists.";
        }
        $stmt->close();
    }

    // Handle file upload
    if (empty($errors)) {
        if (!empty($_FILES['profilePicture']['tmp_name'])) {
            $file_name = $_FILES['profilePicture']['name'];
            $file_tmp = $_FILES['profilePicture']['tmp_name'];
            $file_size = $_FILES['profilePicture']['size'];
            $file_type = $_FILES['profilePicture']['type'];
            $file_error = $_FILES['profilePicture']['error'];

            // Example directory where images are stored
            $upload_dir = 'uploads/';

            // Check file type and size, handle errors
            $allowed_types = array('jpg', 'jpeg', 'png', 'gif');
            $file_extension = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

            if (!in_array($file_extension, $allowed_types)) {
                $errors['general'] = "Invalid file type. Only JPG, JPEG, PNG, and GIF files are allowed.";
            } elseif ($file_size > 2097152) { // 2MB
                $errors['general'] = "File size exceeds maximum limit (2MB).";
            } elseif ($file_error !== UPLOAD_ERR_OK) {
                $errors['general'] = "File upload error: " . $file_error;
            } else {
                // Move uploaded file to destination directory
                if (move_uploaded_file($file_tmp, $upload_dir . $file_name)) {
                    $profile_image_path = $upload_dir . $file_name;
                } else {
                    $errors['general'] = "Failed to upload file.";
                }
            }
        } else {
            // Default profile image path
            $profile_image_path = 'assets/img/profiles/default-profile.png';
        }
    }

    // If no errors, proceed with adding the account
    if (empty($errors)) {
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
        $sql = "INSERT INTO admins (name, username, email, password, authority, profile_image) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("ssssss", $name, $username, $email, $hashedPassword, $authority, $profile_image_path);

        if ($stmt->execute()) {
            header("Location: ../add_account.php?success_message=Account added successfully.");
        } else {
            header("Location: ../add_account.php?errors=" . urlencode(json_encode(['general' => "Error adding account."])));
        }
        $stmt->close();
    } else {
        header("Location: ../add_account.php?errors=" . urlencode(json_encode($errors)));
    }
    exit();
} else {
    header("Location: ../add_account.php");
    exit();
}
