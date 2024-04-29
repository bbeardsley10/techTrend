<?php
//Include the database connection file
include 'db_connection.php';
// Start session
session_start();

if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}

error_log("Form data: " . json_encode($_POST)); // Check POST data
// Get the username and password that were submitted
$username = trim($_POST['Customer_Username'] ?? '');
$password = $_POST['Customer_Password'] ?? '';



// Makes sure that the username and password fields aren't empty
if (empty($username) || empty($password)) {
    header('Location: signin.html?error=MissingCredentials');
    exit();
}

$quer = "SELECT * FROM customer WHERE `Customer_Username` = '$username' AND `Customer_Password` = '$password'";
$result = mysqli_query($conn, $quer);
$num = mysqli_num_rows($result);

if ($num == 1) {
    $_SESSION['Customer_Username']= $username;

    header('location: cart.php');
} else {
    header("Location: customer-registration.php");
    

}

?>
