<?php
session_start();
include '../db.php';

// Ensure email and password are already set in session
if (!isset($_SESSION['email']) || !isset($_SESSION['hashed_password'])) {
    die("Session expired. Please verify your email and set your password again.");
}

$email = $_SESSION['email'];
$password = $_SESSION['hashed_password'];

$firstname = $_POST['firstname'];
$lastname = $_POST['lastname'];
$bio = $_POST['bio'];
$user_id = $_POST['user_id'];

// Handle image upload
$upload_dir = '../image/';
if (!is_dir($upload_dir)) {
    mkdir($upload_dir, 0755, true);
}

$filename = time() . '_' . basename($_FILES["profile_picture"]["name"]);
$target_file = $upload_dir . $filename;

if (move_uploaded_file($_FILES["profile_picture"]["tmp_name"], $target_file)) {
    $profile_path = $target_file;
} else {
    die("Image upload failed.");
}

// Save everything to `users` table
$stmt = $conn->prepare("INSERT INTO users (email, password, first_name, last_name, bio, user_id, profile_pic) VALUES (?, ?, ?, ?, ?, ?, ?)");

if (!$stmt) {
    die("Prepare failed: " . $conn->error);
}

$stmt->bind_param("sssssss", $email, $password, $firstname, $lastname, $bio, $user_id, $profile_path);

if ($stmt->execute()) {
    session_destroy();
    echo "<html><head><meta http-equiv='refresh' content='3;url=../frontend/register_success.html'></head><body>";

    echo "</body></html>";
} else {
    echo "Registration failed: " . $stmt->error;
}
$stmt->close();
?>