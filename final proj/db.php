<?php
$servername = "localhost";  // Adjust for your MySQL server
$username = "root";         // Your MySQL username
$password = "";             // Your MySQL password
$dbname = "user_db";        // Database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
