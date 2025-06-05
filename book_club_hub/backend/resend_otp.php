<?php
session_start();
require 'PHPMailer/PHPMailer.php';
require 'PHPMailer/SMTP.php';
require 'PHPMailer/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// 1. Check if email is stored in session
if (!isset($_SESSION['email'])) {
    echo "Email not set in session. Cannot resend OTP.";
    exit;
}

$email = $_SESSION['email'];
$otp = strval(rand(100000, 999999)); // Generate a 6-digit OTP as string

// 2. Connect to database
$conn = new mysqli("localhost", "root", "", "bookclubhubdb");
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

// 3. Update OTP and expiry in otp_temp table
$stmt = $conn->prepare("UPDATE otp_temp SET otp = ?, expires_at = DATE_ADD(NOW(), INTERVAL 5 MINUTE) WHERE email = ?");
if (!$stmt) {
    die("Prepare failed: " . $conn->error);
}

$stmt->bind_param("ss", $otp, $email);
$stmt->execute();

if ($stmt->affected_rows === 0) {
    // Email not found in otp_temp — insert new row
    $stmt->close();

    $stmt = $conn->prepare("INSERT INTO otp_temp (email, otp, expires_at) VALUES (?, ?, DATE_ADD(NOW(), INTERVAL 5 MINUTE))");
    if (!$stmt) {
        die("Prepare failed on insert: " . $conn->error);
    }
    $stmt->bind_param("ss", $email, $otp);
    $stmt->execute();
}

$stmt->close();
$conn->close();

// 4. Send OTP email using PHPMailer
$mail = new PHPMailer(true);

try {
    $mail->isSMTP();
    $mail->Host       = 'smtp.gmail.com'; 
    $mail->SMTPAuth   = true;
    $mail->Username   = 'fmd4965@gmail.com';  
    $mail->Password   = 'idmd rzuk curz upjm'; 
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port       = 587;

    $mail->setFrom('fmd4965@gmail.com', 'BookClubHub');
    $mail->addAddress($email);

    $mail->Subject = 'Your new OTP - Book Club Hub';
    $mail->Body    = "Your new OTP is: $otp\nThis OTP will expire in 5 minutes.";

    $mail->send();

    // Redirect or respond success
    header("Location: ../frontend/otp_verify.html?resent=1");
    exit;
} catch (Exception $e) {
    echo "Failed to send OTP email. Mailer Error: {$mail->ErrorInfo}";
}
?>