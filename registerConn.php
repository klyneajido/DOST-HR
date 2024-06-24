<?php
// Get the form data
$name = $_POST['name'];
$email = $_POST['email'];
$password = $_POST['password'];
$confirmPassword = $_POST['confirmPassword'];

// Validate form data (add more validation as needed)
if (empty($name) || empty($email) || empty($password) || empty($confirmPassword)) {
    die('Please fill all the fields.');
}

if ($password !== $confirmPassword) {
    die('Passwords do not match.');
}

// Hash the password
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

// Database connection
$conn = new mysqli('localhost', 'root', '', 'dosthr');

// Check connection
if ($conn->connect_error) {
    die('Connection failed: ' . $conn->connect_error);
}

// Prepare and bind
$stmt = $conn->prepare("INSERT INTO admin (name, email, password) VALUES (?, ?, ?)");
$stmt->bind_param("sss", $name, $email, $hashed_password);

// Execute the statement
if ($stmt->execute()) {
    header('Location: login.html');
    exit();
} else {
    echo "Error: " . $stmt->error;
}

// Close the statement and connection
$stmt->close();
$conn->close();
?>
