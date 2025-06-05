<?php
session_start();
$isLoggedIn = isset($_SESSION['user_email']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Club Hub</title>
    <link rel="stylesheet" href="../frontend/css/home.css">
</head>
<body>
    <!-- Header -->
    <header class="main-header">
        <div class="container">
            <h1 class="logo">Book Club Hub</h1>
            <nav class="auth-links">
                <?php if ($isLoggedIn): ?>
                    <a href="profile.php" class="btn btn-outline">Profile</a>
                    
                    <a href="../frontend/join_a_club.html" class="btn btn-primary">JOIN CLUB</a>
                    <a href="logout.php" class="btn btn-primary">Logout</a>
                <?php else: ?>
                    <a href="../frontend/login.html" class="btn btn-outline">Login</a>
                    <a href="../frontend/register.html" class="btn btn-primary">Register</a>
                <?php endif; ?>
            </nav>
        </div>
    </header>

    <!-- Hero Section -->
    <section class="hero">
        <div class="container">
            <h2>Welcome to Your Literary Community</h2>
            <p>Connect with fellow book lovers, discover new genres, and track your reading journey with our vibrant community.</p>
        </div>
    </section>

    <!-- Genre Cards -->
    <section class="featured-genres">
        <div class="container">
            <h3>Explore Our Genres</h3>
            <div class="genre-grid">
                <a href="../frontend/fiction.html" class="genre-card fiction">
                    <h4>Fiction</h4>
                    <p>Explore imaginary worlds and compelling characters</p>
                </a>
                <a href="../frontend/sci-fi.html" class="genre-card scifi">
                    <h4>Sci-Fi</h4>
                    <p>Journey through futuristic landscapes</p>
                </a>
                <a href="../frontend/romance.html" class="genre-card romance">
                    <h4>Romance</h4>
                    <p>Fall in love with timeless stories</p>
                </a>
                <a href="../frontend/fantasy.html" class="genre-card fantasy">
                    <h4>Fantasy</h4>
                    <p>Enter realms of magic and adventure</p>
                </a>
            </div>
        </div>
    </section>

    <!-- Footer -->
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