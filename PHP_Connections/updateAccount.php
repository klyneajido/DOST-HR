<?php
include("checkUser.php");
include("db_connection.php");

if (!isset($_SESSION['username'])) {
    header('Location: ../login.php');
    exit();
}
$user_name = isset($_SESSION['username']) ? $_SESSION['username'] : 'Guest';
$profile_image_path = isset($_SESSION['profile_image']) ? $_SESSION['profile_image'] : 'assets/img/profiles/default-profile.png';

// Get the ID of the currently logged-in superadmin
$current_admin_id = $_SESSION['admin_id'];

// Function to validate password
function validatePassword($password) {
    if (strlen($password) < 8) {
        return "Password must be at least 8 characters long.";
    }
    if (!preg_match("/[0-9]/", $password)) {
        return "Password must include at least one number.";
    }
    if (!preg_match("/[!@#$%^&*(),.?\":{}|<>]/", $password)) {
        return "Password must include at least one special character.";
    }
    return null;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $admin_id = intval($_POST['admin_id']);
    $name = $_POST['name'];
    $newPassword = $_POST['newPassword'];
    $confirmPassword = $_POST['confirmPassword'];

    // Error message array
    $errors = [];

    // Check if password fields are empty
    if (!empty($newPassword) || !empty($confirmPassword)) {
        // Validate password
        if ($newPassword !== $confirmPassword) {
            $errors['confirmPassword'] = "Passwords do not match.";
        } else {
            $passwordError = validatePassword($newPassword);
            if ($passwordError) {
                $errors['newPassword'] = $passwordError;
            }
        }
    }

    if (empty($errors)) {
        // Prepare to update the record
        if (!empty($newPassword) && !empty($confirmPassword)) {
            // Hash the new password
            $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
            $sql = "UPDATE admins SET name = ?, password = ? WHERE admin_id = ?";
            if ($stmt = $mysqli->prepare($sql)) {
                $stmt->bind_param("ssi", $name, $hashedPassword, $admin_id);
            } else {
                $errors['general'] = "Error preparing statement: " . $mysqli->error;
            }
        } else {
            // Update name only
            $sql = "UPDATE admins SET name = ? WHERE admin_id = ?";
            if ($stmt = $mysqli->prepare($sql)) {
                $stmt->bind_param("si", $name, $admin_id);
            } else {
                $errors['general'] = "Error preparing statement: " . $mysqli->error;
            }
        }

        if (isset($stmt) && $stmt->execute()) {
            $success_message = "Account updated successfully.";
        } else {
            $errors['general'] = "Error updating account: " . $mysqli->error;
        }
        $stmt->close();
    }

    // Redirect back to the edit page with errors or success message
    header("Location: ../editAccount.php?id=$admin_id&" . http_build_query([
        'success_message' => $success_message ?? '',
        'errors' => json_encode($errors)
    ]));
    exit();
}
?>
