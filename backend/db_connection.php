<?php
// Database configuration (replace with your details)
$servername = "localhost";
$username = "root";
$password = "";
$database = "tech_trend";

// Create connection
$conn = mysqli_connect($servername, $username, $password, $database);

// Check connection
if (!$conn) {
  die("Connection failed: " . mysqli_connect_error());
}

// Set charset to UTF-8
mysqli_set_charset($conn, "utf8mb4");

// Optional variable
define("DB_CONNECTION", $conn);
?>
