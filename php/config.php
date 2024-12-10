<?php
// Database configuration
$host = 'localhost';      // Database host
$user = 'root';           // Database username
$password = '';           // Database password
$dbname = 'votingsystem'; // Database name

// Create connection
$con = mysqli_connect($host, $user, $password, $dbname);

// Check connection
if (!$con) {
    // Log the error and display a generic message
    error_log("Database connection failed: " . mysqli_connect_error());
    die("Could not connect to the database. Please try again later.");
}

// Set the connection charset
if (!mysqli_set_charset($con, "utf8mb4")) {
    error_log("Error loading character set utf8mb4: " . mysqli_error($con));
    die("A database error occurred. Please try again later.");
}
?>
