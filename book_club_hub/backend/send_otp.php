<?php
session_start();
require 'PHPMailer/PHPMailer.php';
require 'PHPMailer/SMTP.php';
require 'PHPMailer/Exception.php';
include '../db.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$email = $_POST['email'];
$otp = rand(100000, 999999);
$expires = date("Y-m-d H:i:s", strtotime("+5 minutes"));

// Save OTP to DB
$query = "INSERT INTO otp_temp (email, otp, expires_at)
          VALUES (?, ?, ?)
          ON DUPLICATE KEY UPDATE otp=?, expires_at=?";
$stmt = $conn->prepare($query);

if (!$stmt) {
    die("Database error: " . $conn->error);
}
$stmt->bind_param("sssss", $email, $otp, $expires, $otp, $expires);
$stmt->execute();
$stmt->close();

$_SESSION['email'] = $email;

// Send email via PHPMailer
$mail = new PHPMailer(true);

try {
    // SMTP Configuration
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com'; // Gmail SMTP
    $mail->SMTPAuth = true;
    $mail->Username = 'fmd4965@gmail.com';  
    $mail->Password = 'idmd rzuk curz upjm';      
    $mail->SMTPSecure = 'tls';
    $mail->Port = 587;

    // Email content
    $mail->setFrom('fmd4965@gmail.com', 'BookClubHub');
    $mail->addAddress($email);
    $mail->Subject = 'Your OTP Code';
    $mail->Body    = "Your OTP code is: $otp\nIt expires in 5 minutes.";

    $mail->send();
    echo "OTP sent successfully! Check your inbox.";
    echo "<br><a href='../frontend/otp_verify.html'>Click here to verify OTP</a>";

} catch (Exception $e) {
    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
}
?>