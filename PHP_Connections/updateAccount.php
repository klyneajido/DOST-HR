<?php
include("checkUser.php");
include("db_connection.php");

if (!isset($_SESSION['username'])) {
    header('Location: ../login.php');
    exit();
}
$user_name = isset($_SESSION['username']) ? $_SESSION['username'] : 'Guest';
$profile_image_path = isset($_SESSION['profile_image']) ? $_SESSION['profile_image'] : 'assets/img/profiles/default-profile.png';

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
    $authority = $_POST['authority'];
    $newPassword = $_POST['newPassword'];
    $confirmPassword = $_POST['confirmPassword'];

    // Error message array
    $errors = [];

    // Validate password
    if ($newPassword !== $confirmPassword) {
        $errors['confirmPassword'] = "Passwords do not match.";
    } else {
        $passwordError = validatePassword($newPassword);
        if ($passwordError) {
            $errors['newPassword'] = $passwordError;
        }
    }

    // Check if the user is attempting to downgrade to admin while being the only superadmin
    if (empty($errors)) {
        // Check the current number of superadmins
        $sql = "SELECT COUNT(*) AS superadmin_count FROM admins WHERE authority = 'superadmin'";
        $result = $mysqli->query($sql);
        $row = $result->fetch_assoc();
        $superadmin_count = $row['superadmin_count'];

        // If the user is the only superadmin and is trying to change authority to admin
        if ($superadmin_count <= 1 && $authority === 'admin') {
            $errors['authority'] = "You cannot downgrade to admin as you are the only superadmin. Please ensure there is at least one superadmin.";
        } else {
            // Hash the new password
            $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

            // Prepare the SQL update statement
            $sql = "UPDATE admins SET name = ?, authority = ?, password = ? WHERE admin_id = ?";
            if ($stmt = $mysqli->prepare($sql)) {
                $stmt->bind_param("sssi", $name, $authority, $hashedPassword, $admin_id);

                if ($stmt->execute()) {
                    $success_message = "Account updated successfully.";
                } else {
                    $errors['general'] = "Error updating account: " . $mysqli->error;
                }
                $stmt->close();
            } else {
                $errors['general'] = "Error preparing statement: " . $mysqli->error;
            }
        }
    }

    // Redirect back to the edit page with errors or success message
    header("Location: ../editAccount.php?id=$admin_id&" . http_build_query([
        'success_message' => $success_message ?? '',
        'errors' => json_encode($errors)
    ]));
    exit();
}
?>
