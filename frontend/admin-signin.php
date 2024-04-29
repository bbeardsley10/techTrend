<?php
// Start session
session_start();

//Include the database connection file
include 'db_connection.php';



if (!$conn) {
    echo "No Connection";
    exit; // Exit script if connection fails
}

$username = $_POST['Admin_Username'];
$password = $_POST['Admin_Password'];

$quer = "SELECT * FROM admin WHERE `Admin_Username` = '$username' AND `Admin_Password` = '$password'";
$result = mysqli_query($conn, $quer);
$num = mysqli_num_rows($result);

if ($num == 1) {
    $_SESSION['Admin_Username']= $username;

    header('location:admin-menu.php');
    exit;
} else {
    header("Location: admin-registration.php");
    exit;

}

?>