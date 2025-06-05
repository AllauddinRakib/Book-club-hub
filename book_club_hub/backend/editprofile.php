<?php
session_start();

// Redirect to login page if user is not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../frontend/login.html");
    exit();
}

// Database connection variables
$host = 'localhost';
$dbname = 'your_database_name';
$username = 'your_db_username';
$password = 'your_db_password';

// Initialize variables with session data
$firstName = $_SESSION["first_name"] ?? '';
$lastName = $_SESSION["last_name"] ?? '';
$email = $_SESSION["email"] ?? '';
$bio = $_SESSION["bio"] ?? '';
$userId = $_SESSION["user_id"] ?? '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Create database connection
        $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Sanitize and validate input
        $newFirstName = filter_input(INPUT_POST, 'firstName', FILTER_SANITIZE_STRING);
        $newLastName = filter_input(INPUT_POST, 'lastName', FILTER_SANITIZE_STRING);
        $newEmail = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
        $newBio = filter_input(INPUT_POST, 'bio', FILTER_SANITIZE_STRING);

        // Validate email
        if (!filter_var($newEmail, FILTER_VALIDATE_EMAIL)) {
            throw new Exception("Invalid email format");
        }

        // Update user in database
        $stmt = $conn->prepare("UPDATE users SET first_name = :firstName, last_name = :lastName, email = :email, bio = :bio WHERE user_id = :userId");
        $stmt->bindParam(':firstName', $newFirstName);
        $stmt->bindParam(':lastName', $newLastName);
        $stmt->bindParam(':email', $newEmail);
        $stmt->bindParam(':bio', $newBio);
        $stmt->bindParam(':userId', $userId);
        $stmt->execute();

        // Update session variables
        $_SESSION['first_name'] = $newFirstName;
        $_SESSION['last_name'] = $newLastName;
        $_SESSION['email'] = $newEmail;
        $_SESSION['bio'] = $newBio;

        // Redirect to profile page with success message
        header("Location: profile.php?success=1");
        exit();
    } catch (Exception $e) {
        $error = "Error updating profile: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Edit Profile | Book Club Hub</title>
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=Roboto:wght@400;500&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="../frontend/css/profile.css"> <!-- Reuse profile.css or create editprofile.css -->
</head>
<body>
  <div class="profile-container">
    <h1>Edit Your Profile</h1>
    
    <?php if (isset($error)): ?>
      <div class="error-message"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>
    
    <form action="editprofile.php" method="post" class="profile-form">
      <div class="form-group">
        <label for="firstName">First Name:</label>
        <input type="text" id="firstName" name="firstName" value="<?php echo htmlspecialchars($firstName); ?>" required>
      </div>
      
      <div class="form-group">
        <label for="lastName">Last Name:</label>
        <input type="text" id="lastName" name="lastName" value="<?php echo htmlspecialchars($lastName); ?>" required>
      </div>
      
      <div class="form-group">
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>" required>
      </div>
      
      <div class="form-group">
        <label for="bio">Bio:</label>
        <textarea id="bio" name="bio" rows="4"><?php echo htmlspecialchars($bio); ?></textarea>
      </div>
      
      <div class="btn-group">
        <button type="submit" class="btn">Save Changes</button>
        <a href="profile.php" class="btn cancel-btn">Cancel</a>
      </div>
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