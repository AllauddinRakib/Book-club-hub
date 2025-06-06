<?php

use PHPUnit\Framework\TestCase;

class BookClubTest extends TestCase
{
    private $pdo;

    protected function setUp(): void
    {
        $this->pdo = new PDO("mysql:host=localhost;dbname=book_club", "root", "");
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    // Test member registration through the join form
    public function testMemberRegistration()
    {
        $name = "Test Member";
        $email = "testmember@example.com";
        $club = "Fantasy";
        
        // Test the insert operation
        $stmt = $this->pdo->prepare("INSERT INTO members (name, email, club) VALUES (?, ?, ?)");
        $success = $stmt->execute([$name, $email, $club]);

        $this->assertTrue($success, "Member should be successfully registered");
        
        // Verify the data was inserted correctly
        $stmt = $this->pdo->prepare("SELECT * FROM members WHERE email = ?");
        $stmt->execute([$email]);
        $member = $stmt->fetch(PDO::FETCH_ASSOC);

        $this->assertEquals($name, $member['name']);
        $this->assertEquals($club, $member['club']);
        
        // Clean up
        $this->pdo->prepare("DELETE FROM members WHERE email = ?")->execute([$email]);
    }

    // Test form validation for empty fields
    public function testEmptyFormSubmission()
    {
        $name = ""; // Empty name
        $email = "test@example.com";
        $club = "Sci-Fi";
        
        $stmt = $this->pdo->prepare("INSERT INTO members (name, email, club) VALUES (?, ?, ?)");
        $success = $stmt->execute([$name, $email, $club]);
        
        // Your process_join.php should handle empty names by setting a default
        $stmt = $this->pdo->prepare("SELECT name FROM members WHERE email = ?");
        $stmt->execute([$email]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        $this->assertEquals("Guest", $result['name'], "Empty name should default to 'Guest'");
        
        // Clean up
        $this->pdo->prepare("DELETE FROM members WHERE email = ?")->execute([$email]);
    }

    // Test invalid email format handling
    public function testInvalidEmailSubmission()
    {
        $name = "Test User";
        $email = "invalid-email"; // Invalid format
        $club = "Fiction";
        
        $stmt = $this->pdo->prepare("INSERT INTO members (name, email, club) VALUES (?, ?, ?)");
        $success = $stmt->execute([$name, $email, $club]);
        
        // Verify the default email was set
        $stmt = $this->pdo->prepare("SELECT email FROM members WHERE name = ?");
        $stmt->execute([$name]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        $this->assertEquals("no@email.com", $result['email'], "Invalid email should default to 'no@email.com'");
        
        // Clean up
        $this->pdo->prepare("DELETE FROM members WHERE name = ?")->execute([$name]);
    }

    // Test retrieving all members of a specific club
    public function testClubMemberListing()
    {
        // Add test members
        $testMembers = [
            ["Fant