<?php
session_start();

$con = mysqli_connect('localhost', 'root', '', 'techtrend2');

if (!$con) {
    echo "No Connection";
    exit; // Exit script if connection fails
}

$customerID = $_POST['Customer_ID'];
$firstname = $_POST['First_Name'];
$lastname = $_POST['Last_Name'];
$city = $_POST['City'];
$state = $_POST['State'];
$street_address = $_POST['Street_Address'];
$zip_code = $_POST['Zip_Code'];
$customer_email = $_POST['Customer_Email'];


$quer = "SELECT * FROM Customer WHERE `First_Name` = '$firstname' AND `Last_Name` = '$lastname'";
$result = mysqli_query($con, $quer);
$num = mysqli_num_rows($result);

if ($num > 0) {
    echo "Duplicate Detected";
} else {
    $query = "INSERT INTO Customer (Customer_ID, First_Name, Last_Name, City, State, Street_Address, Zip_Code, Customer_Email) VALUES (?, ?, ?,?,?,?,?,?)";
    $stmt = mysqli_prepare($con, $query);
    mysqli_stmt_bind_param($stmt, "ssssssss",$customerID, $firstname, $lastname, $city, $state, $street_address, $zip_code, $customer_email);
    mysqli_stmt_execute($stmt);
    echo "Account created successfully";
    mysqli_stmt_close($stmt);
}

mysqli_close($con);
?>