<?php
session_start();
require '../db.php';

// Ensure user is logged in
if (!isset($_SESSION['user_email'])) {
    header("Location: /login.html");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = $_SESSION['user_email']; // original email to identify user

    // Get and sanitize inputs
    $newEmail = trim($_POST['email']);
    $firstName = trim($_POST['firstname']);
    $lastName = trim($_POST['lastname']);
    $bio = trim($_POST['bio']);
    $password = trim($_POST['password']);
    $genres = isset($_POST['genres']) ? implode(", ", $_POST['genres']) : "";

    // Prepare SQL query with or without password
    if (!empty($password)) {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $query = "UPDATE users SET first_name = ?, last_name = ?, email = ?, password = ?, bio = ?, genres = ? WHERE email = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("sssssss", $firstName, $lastName, $newEmail, $hashedPassword, $bio, $genres, $email);
    } else {
        $query = "UPDATE users SET first_name = ?, last_name = ?, email = ?, bio = ?, genres = ? WHERE email = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ssssss", $firstName, $lastName, $newEmail, $bio, $genres, $email);
    }

    if ($stmt->execute()) {
        // Update session email if changed
        $_SESSION['user_email'] = $newEmail;

        // Redirect to profile
        header("Location: profile.php");
        exit();
    } else {
        echo "Failed to update profile.";
    }

    $stmt->close();
    $conn->close();
} else {
    echo "Invalid request.";
}
?>