<?php
$token = $_GET['token'] ?? '';
if (empty($token)) {
    die('❌ Invalid or missing token.');
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Reset Password | Book Club Hub</title>
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=Roboto:wght@400;500&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
  <div class="container">
    <h2>Reset Your Password</h2>

    <form action="../backend/reset_password.php" method="POST">
      <!-- Hidden token passed from email link -->
      <input type="hidden" name="token" value="<?php echo htmlspecialchars($token); ?>" />

      <div class="form-group">
        <label for="password">New Password</label>
        <input type="password" name="password" id="password" required />
      </div>

      <div class="form-group">
        <label for="confirm_password">Confirm New Password</label>
        <input type="password" name="confirm_password" id="confirm_password" required />
      </div>

      <button type="submit" class="btn">Reset Password</button>
    </form>
  </div>

  <footer class="main-footer">
    <div class="container">
      <div class="footer-links">
        <a href="#">Terms & Conditions</a>
        <a href="#">Privacy Policy</a>
        <a href="#">About Us</a>
        <a href="#">Contact Us</a>
      </div>
      <p class="copyright">&copy; 2025 Book Club Hub. All rights reserved.</p>
    </div>
  </footer>
</body>
</html>