<?php
session_start();

// Redirect to login page if not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../frontend/login.html");
    exit();
}

// Database connection
$servername = "localhost";
$dbname = "bookclubhubdb";
$username = "root";
$password = "";

$userId = $_SESSION['user_id'] ?? '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['confirm_delete'])) {
        try {
            // Connect to database
            $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // Delete user from 'users' table
            $stmt = $conn->prepare("DELETE FROM users WHERE user_id = :userId");
            $stmt->bindParam(':userId', $userId);
            $stmt->execute();

            // Log user out
            session_unset();
            session_destroy();

            // Redirect to goodbye page
            header("Location: ../frontend/goodbye.html");
            exit();

        } catch (Exception $e) {
            // Optional: Redirect to error page or profile with a query param
            header("Location: ../frontend/profile.html?error=delete_failed");
            exit();
        }

    } elseif (isset($_POST['cancel'])) {
        // User clicked "No, Keep My Account"
        header("Location: ../frontend/profile.html");
        exit();
    }
}

// If the request wasn't POST, redirect back to profile
header("Location: ../frontend/profile.html");
exit();
?>

