<?php
session_start();

// Redirect to login page if user is not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../frontend/login.html");
    exit();
}

// Fetch user details from session (with fallback values)
$firstName = $_SESSION["first_name"] ?? 'First Name';
$lastName = $_SESSION["last_name"] ?? 'Last Name';
$email = $_SESSION["email"] ?? 'example@example.com';
$bio = $_SESSION["bio"] ?? 'No bio available.';
$userId = $_SESSION["user_id"] ?? 'N/A';
$profilePic = $_SESSION["profile_pic"] ?? '../frontend/image/default.jpg';

// Adjust path if it's a relative path from uploads folder
if (!str_starts_with($profilePic, '../')) {
    $profilePic = '../backend/' . $profilePic;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>My Profile | Book Club Hub</title>
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=Roboto:wght@400;500&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="../frontend/css/profile.css"> 
  
</head>
<body>
  <a href="index.php" class="back-home">&larr; Back to Home</a>

  <div class="profile-container">
    <div class="profile-header">
      <img src="<?php echo htmlspecialchars($profilePic); ?>" alt="Profile Picture" class="profile-pic">
      <h1><?php echo htmlspecialchars($firstName . ' ' . $lastName); ?></h1>
    </div>
    
    <div class="profile-info">
      <p><strong>User ID:</strong> <?php echo htmlspecialchars($userId); ?></p>
      <p><strong>First Name:</strong> <?php echo htmlspecialchars($firstName); ?></p>
      <p><strong>Last Name:</strong> <?php echo htmlspecialchars($lastName); ?></p>
      <p><strong>Email:</strong> <?php echo htmlspecialchars($email); ?></p>
      <p><strong>Bio:</strong> <?php echo htmlspecialchars($bio); ?></p>
    </div>

    <div class="btn-group">
      <a href="../frontend/editprofile.html" class="btn">Edit Profile</a>
      <a href="../frontend/deleteprofile.html" class="btn">Delete Profile</a>
      <a href="logout.php" class="btn">Logout</a>
    </div>
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