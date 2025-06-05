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
    header("Location: ../frontend/register.html");
} else {
    echo "OTP invalid or expired.";
}
?>