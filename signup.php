<?php
session_start();

$con = mysqli_connect('localhost', 'root', '', 'tech_trend');

if (!$con) {
    echo "No Connection";
    exit; // Exit script if connection fails
}

$firstname = $_POST['first-name'];
$lastname = $_POST['last-name'];
$adminID = $_POST['admin-id'];
$adminRole = $_POST['admin-role'];

$quer = "SELECT * FROM Admin WHERE `first-name` = '$firstname' AND `last-name` = '$lastname'";
$result = mysqli_query($con, $quer);
$num = mysqli_num_rows($result);

if ($num > 0) {
    echo "Duplicate Detected";
} else {
    $query = "INSERT INTO Admin (`first-name`, `last-name`, `admin-id`, `admin-role`) VALUES (?, ?, ?, ?)";
    $stmt = mysqli_prepare($con, $query);
    mysqli_stmt_bind_param($stmt, "ssss", $firstname, $lastname, $adminID, $adminRole);
    mysqli_stmt_execute($stmt);
    echo "Record inserted successfully";
    mysqli_stmt_close($stmt);
}

mysqli_close($con);
?>