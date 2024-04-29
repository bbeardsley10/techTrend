<?php
// Start session
session_start();

// Include the database connection file
include 'db_connection.php';



if (!$conn) {
    echo "No Connection";
    exit; // Exit script if connection fails
}

// Retrieve POST data from the form
$username = $_POST['Customer_Username'];
$password = $_POST['Customer_Password']; 
$firstname = $_POST['First_Name'];
$lastname = $_POST['Last_Name'];
$street_address = $_POST['Street_Address'];
$city = $_POST['City'];
$state = $_POST['State'];
$zip_code = $_POST['Zip_Code'];
$customer_email = $_POST['Customer_Email'];

// Check for duplicate usernames
$query = "SELECT * FROM customer WHERE `Customer_Username` = ?";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "s", $username);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$num = mysqli_num_rows($result);

if ($num > 0) {
    // Username already exists
    $_SESSION['registration_error'] = "The username is already taken!";
    header("Location: customer-registration.php");
    exit; // Redirect back with an error
}

// Check for duplicate emails
$email_query = "SELECT * FROM customer WHERE Customer_Email = ?";
$stmt = mysqli_prepare($conn, $email_query);
mysqli_stmt_bind_param($stmt, "s", $customer_email);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$num = mysqli_num_rows($result);

if ($num > 0) {
    // Email is already linked to an account
    $_SESSION['registration_error'] = "That email is already linked to an account!";
    header("Location: customer-registration.php");
    exit; // Redirect back with an error
}

// Insert the new customer record
$insert_query = "INSERT INTO customer (Customer_Username, Customer_Password, First_Name, Last_Name, Street_Address, City, State, Zip_Code, Customer_Email) 
                  VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
$stmt = mysqli_prepare($conn, $insert_query);
mysqli_stmt_bind_param($stmt, "sssssssss", $username, $password, $firstname, $lastname, $street_address, $city, $state, $zip_code, $customer_email);
mysqli_stmt_execute($stmt);

if (mysqli_stmt_affected_rows($stmt) > 0) {
    // If successful, get the auto-incremented Customer_ID
    $newCustomerID = mysqli_insert_id($conn);

    // Store the Customer_ID in the session for future reference
    $_SESSION['customer_ID'] = $newCustomerID;

    // Store the Customer_ID in the database table to ensure linkage
    $update_query = "UPDATE customer SET customer_ID = ? WHERE Customer_Username = ?";
    $stmt = mysqli_prepare($conn, $update_query);
    mysqli_stmt_bind_param($stmt, "is", $newCustomerID, $username);
    mysqli_stmt_execute($stmt);

    // Redirect to a customer portal after successful registration
    header("Location: customer-portal.html");
    exit;
} else {
    // Handle errors during the insert operation
    $_SESSION['registration_error'] = "Error creating account: " . mysqli_error($conn);
    header("Location: customer-registration.php");
    exit;
}

// Close the statement and database connection
mysqli_stmt_close($stmt);
mysqli_close($conn);
