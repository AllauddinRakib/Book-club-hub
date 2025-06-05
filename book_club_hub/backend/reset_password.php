<?php
require_once '../db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $token = $_POST['token'] ?? '';
    $password = $_POST['password'] ?? '';
    $confirm = $_POST['confirm_password'] ?? '';

    // Basic validations
    if (empty($token) || empty($password) || empty($confirm)) {
        die('❌ All fields are required.');
    }

    if ($password !== $confirm) {
        die('❌ Passwords do not match.');
    }

    if (strlen($password) < 8) {
        die('❌ Password must be at least 8 characters.');
    }

    // Check if token is valid and not expired
    if ($stmt = $conn->prepare("SELECT email FROM password_resets WHERE token = ? AND expires_at > NOW()")) {
        $stmt->bind_param("s", $token);
        $stmt->execute();
        $stmt->bind_result($email);

        if ($stmt->fetch()) {
            $stmt->close(); // ✅ Close statement after fetching

            // Hash the new password
            $hashed = password_hash($password, PASSWORD_DEFAULT);

            // Update the user's password
            if ($update = $conn->prepare("UPDATE users SET password = ? WHERE email = ?")) {
                $update->bind_param("ss", $hashed, $email);

                if ($update->execute()) {
                    $update->close();

                    // Delete the token record
                    if ($delete = $conn->prepare("DELETE FROM password_resets WHERE email = ?")) {
                        $delete->bind_param("s", $email);
                        $delete->execute();
                        $delete->close();
                    }

                    // Redirect to success page
                    header("Location: ../frontend/reset_success.html");
                    exit();
                } else {
                    $update->close();
                    echo "❌ Failed to update password.";
                }
            } else {
                echo "❌ Prepare failed (update): " . $conn->error;
            }

        } else {
            $stmt->close();
            echo "❌ Invalid or expired token.";
        }

    } else {
        die("❌ Prepare failed (token): " . $conn->error);
    }

} else {
    echo "❌ Invalid request method.";
}
?>