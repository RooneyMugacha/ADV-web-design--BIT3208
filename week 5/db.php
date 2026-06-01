<?php
// db.php - Database connection settings
$host = 'localhost';
$dbname = 'cars'; // As requested
$username = 'root'; // Change this if your DB user is different
$password = ''; // Change this if your DB password is not empty

try {
    // Create a new PDO instance
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    // Set PDO error mode to exception for better error handling
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // Set default fetch mode to associative arrays
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    // If connection fails, stop script and show error
    die("Database connection failed: " . $e->getMessage());
}
?>