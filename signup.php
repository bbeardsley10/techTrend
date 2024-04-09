<?php
session_start();

$con = mysqli_connect('localhost', 'root', 'techTrend');

if (!$con) {
    echo "No Connection";
    exit; // Exit script if connection fails
}

$firstname = $_POST['First_Name'];
$lastname = $_POST['Last_Name'];
$adminID = $_POST['Admin_ID'];
$adminRole = $_POST['Admin_Role'];

$quer = "SELECT * FROM Admin WHERE `First_Name` = '$firstname' AND `Last_Name` = '$lastname'";
$result = mysqli_query($con, $quer);
$num = mysqli_num_rows($result);

if ($num > 0) {
    echo "Duplicate Detected";
} else {
    $query = "INSERT INTO Admin (`First_Name`, `Last_Name`, `Admin_ID`, `Admin_Role`) VALUES (?, ?, ?, ?)";
    $stmt = mysqli_prepare($con, $query);
    mysqli_stmt_bind_param($stmt, "ssss", $firstname, $lastname, $adminID, $adminRole);
    mysqli_stmt_execute($stmt);
    echo "Record inserted successfully";
    mysqli_stmt_close($stmt);
}

mysqli_close($con);
?>