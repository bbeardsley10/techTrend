<?php
session_start();

$con = mysqli_connect('localhost', 'root', '', 'techtrend');

if (!$con) {
    echo "No Connection";
    exit; // Exit script if connection fails
}

$username = $_POST['Admin_Username'];
$password = $_POST['Admin_Password'];
$firstname = $_POST['First_Name'];
$lastname = $_POST['Last_Name'];
$adminRole = $_POST['Admin_Role'];

$quer = "SELECT * FROM admin WHERE `Admin_Username` = '$username' AND `Admin_Password` = '$password'";
$result = mysqli_query($con, $quer);
$num = mysqli_num_rows($result);

if ($num == 1) {
    $_SESSION['Admin_Username']= $username;

    header('location:admin-portal.html');
} else {
    header("Location: admin-registration.html");
    

}

?>