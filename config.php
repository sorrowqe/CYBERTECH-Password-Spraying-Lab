<?php
// Database configuration for CBRTCH Unhackable
define('DB_HOST', 'localhost');
define('DB_USER', 'cbrtch_user');
define('DB_PASS', 'cbrtch_pass123');
define('DB_NAME', 'cbrtch_db');

// Create database connection
function getDBConnection() {
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    
    return $conn;
}
?>
