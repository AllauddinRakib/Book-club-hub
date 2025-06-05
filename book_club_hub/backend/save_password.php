<?php
session_start();
include '../db.php';

if (!isset($_SESSION['verified']) || $_SESSION['verified'] !== true) {
    die("Access denied. Please verify your email first.");
}

$email = $_SESSION['email'];
$password = $_POST['password'];
$confirm_password = $_POST['confirm_password'];

if ($password !== $confirm_password) {
    die("Passwords do not match.");
}

$hashed_password = password_hash($password, PASSWORD_DEFAULT);
$_SESSION['hashed_password'] = $hashed_password; // Store temporarily

// Continue to personal info step
header("Location: ../frontend/personal_info.html");