<?php
// Database configuration
$db_host = 'localhost';      // Database host (usually localhost)
$db_name = 'driving_school'; // Database name
$db_user = 'root';           // Database username
$db_pass = '';               // Database password (default is empty for XAMPP/WAMP)

// Create connection
try {
    $conn = new PDO("mysql:host=$db_host;dbname=$db_name;charset=utf8mb4", $db_user, $db_pass);
    
    // Set PDO to throw exceptions on error
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Set default fetch mode to associative array
    $conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    
    // Connection successful
    // echo "Database connection established successfully";
} catch(PDOException $e) {
    // Connection failed
    die("Connection failed: " . $e->getMessage());
}
