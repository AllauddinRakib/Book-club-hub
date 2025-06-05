<?php
session_start();
include '../db.php';

$email = $_SESSION['email'];
$otp = $_POST['otp'];

$stmt = $conn->prepare("SELECT otp, expires_at FROM otp_temp WHERE email=?");
$stmt->bind_param("s", $email);
$stmt->execute();
$stmt->bind_result($stored_otp, $expires);
$stmt->fetch();
$stmt->close();

if ($otp === $stored_otp && new DateTime() < new DateTime($expires)) {
    $_SESSION['verified'] = true;

    // ✅ Generate secure token
    $token = bin2hex(random_bytes(32));
    $expires_at = date('Y-m-d H:i:s', strtotime('+15 minutes'));

    // Save token in password_resets table
    $stmt = $conn->prepare("INSERT INTO password_resets (email, token, expires_at)
                            VALUES (?, ?, ?)
                            ON DUPLICATE KEY UPDATE token=?, expires_at=?");
    $stmt->bind_param("sssss", $email, $token, $expires_at, $token, $expires_at);
    $stmt->execute();
    $stmt->close();

    // ✅ Redirect to token-based reset password page
    header("Location: ../frontend/resetpassword.php?token=$token");
    exit;
} else {
    echo "❌ OTP invalid or expired.";
}
?>