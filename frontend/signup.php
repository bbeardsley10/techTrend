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

$quer = "SELECT * FROM admin WHERE `First_Name` = '$firstname' AND `Last_Name` = '$lastname'";
$result = mysqli_query($con, $quer);
$num = mysqli_num_rows($result);

if ($num > 0) {
    echo "Duplicate Detected";
} else {
    $query = "INSERT INTO Admin (First_Name, Last_Name, Admin_Username, Admin_Password, Admin_Role) VALUES (?,?, ?, ?, ?)";
    $stmt = mysqli_prepare($con, $query);
    mysqli_stmt_bind_param($stmt, "sssss", $firstname, $lastname, $username,$password, $adminRole);
    mysqli_stmt_execute($stmt);
    mysqli_close($con);
    //Account created successfully, set a session variable to indicate success
    $_SESSION['account_created'] = true;
    //Redirects to admin-registration-confirmation page
    header("Location: admin-portal.html");
    exit;

}

?>