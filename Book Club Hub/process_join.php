<?php
// Enable error reporting for development (disable in production)
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Database credentials
$host = 'localhost';
$db   = 'book_club';
$user = 'root';
$pass = '';
$charset = 'utf8mb4';

// PDO setup
$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (PDOException $e) {
    die("
        <h2> Database connection failed:</h2>
        <p>" . $e->getMessage() . "</p>
    ");
}

// Handle both POST and GET requests
$name  = htmlspecialchars(trim($_REQUEST['name'] ?? 'Guest'));
$email = htmlspecialchars(trim($_REQUEST['email'] ?? 'no@email.com'));
$club  = htmlspecialchars(trim($_REQUEST['club'] ?? 'Unknown Club'));

// Allow empty or default fallback values
if (empty($name))  $name  = 'Guest';
if (empty($email)) $email = 'no@email.com';
if (empty($club))  $club  = 'General Interest';

try {
    $stmt = $pdo->prepare("INSERT INTO members (name, email, club) VALUES (?, ?, ?)");
    $stmt->execute([$name, $email, $club]);

    echo "
        <h2>Hey! You're In!</h2>
        <p>Welcome, <strong>$name</strong>! You've been added to <strong>$club</strong>.</p>
        <p><a href='join_a_club.html'>← Join another club</a></p>
    ";
} catch (PDOException $e) {
    // Optional: log error to a file instead of showing to user
    // error_log($e->getMessage(), 3, 'errors.log');
    echo "
        <h2> Something went wrong.</h2>
        <p>But don’t worry — try again later.</p>
        <p><a href='join_a_club.html'>← Back to form</a></p>
    ";
}
?>




