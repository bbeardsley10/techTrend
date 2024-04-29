<?php
// logout.php
session_start();

include 'db_connection.php';
session_unset(); // Unset all session variables
session_destroy(); // Destroy the session
header("Location: index.php"); // Redirect to main menu
exit();
?>