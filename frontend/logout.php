<?php

// File that ersets the log in information after the order has been placed

include 'db_connection.php';
// logout.php
session_start();

if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

session_unset(); // Unset all session variables
session_destroy(); // Destroy the session
header("Location: index.php"); // Redirect to main menu
exit();
?>