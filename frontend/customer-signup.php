<?php
session_start();

$con = mysqli_connect('localhost', 'root', '', 'techtrend');

if (!$con) {
    echo "No Connection";
    exit; // Exit script if connection fails
}

$username = $_POST['Customer_Username'];
$password = $_POST['Customer_Password'];
$firstname = $_POST['First_Name'];
$lastname = $_POST['Last_Name'];
$street_address = $_POST['Street_Address'];
$city = $_POST['City'];
$state = $_POST['State'];
$zip_code = $_POST['Zip_Code'];
$customer_email = $_POST['Customer_Email'];


$quer = "SELECT * FROM Customer WHERE `Customer_Username` = '$username'";
$result = mysqli_query($con, $quer);
$num = mysqli_num_rows($result);

if ($num > 0) {
    // Set session variable with error message
    $_SESSION['registration_error'] = "The username is already taken!";
    // Redirect back to the registration page
    header("Location: customer-registration.php");
    exit;
 } 
 
 
 // Check for duplicate email
$email_query = "SELECT * FROM Customer WHERE Customer_Email = ?";
$stmt = mysqli_prepare($con, $email_query);
mysqli_stmt_bind_param($stmt, "s", $customer_email);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$num = mysqli_num_rows($result);

if ($num > 0) {
    // Email is already linked to an account
    $_SESSION['email_error'] = "That email is already linked to an account!";
    header("Location: customer-registration.php");
    exit;
}

// If no duplicates are found insert the new account
$insert_query = "INSERT INTO Customer (Customer_Username, Customer_Password, First_Name, Last_Name, Street_Address, City, State, Zip_Code, Customer_Email) 
                  VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
$stmt = mysqli_prepare($con, $insert_query);
mysqli_stmt_bind_param($stmt, "sssssssss", $username, $password, $firstname, $lastname, $street_address, $city, $state, $zip_code, $customer_email);
mysqli_stmt_execute($stmt);

// Redirect to customer portal after successful insertion
header("Location: customer-portal.html");
exit; // Ensure the script stops after redirection

mysqli_stmt_close($stmt);
mysqli_close($con);
?>