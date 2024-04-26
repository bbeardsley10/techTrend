<?php
//Include the database connection file
include 'db_connection.php';
// Start session
session_start();


if (!$conn) {
    echo "No Connection";
    exit; // Exit script if connection fails
}

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $firstName = $_POST['first_name'];
    $lastName = $_POST['last_name'];
    $city = $_POST['city'];
    $state = $_POST['state'];
    $streetAddress = $_POST['street_address'];
    $zipCode = $_POST['zip_code'];
    $email = $_POST['email'];

    // Insert customer information into the database (without Payment_ID)
    $query = "INSERT INTO customer (First_Name, Last_Name, City, State, Street_Address, Zip_Code, Customer_Email) 
              VALUES ('$firstName', '$lastName', '$city', '$state', '$streetAddress', '$zipCode', '$email')";

    if (mysqli_query($conn, $query)) {
        // Customer information inserted successfully
        // Redirect to payment page
        header("Location: payment.php");
        exit();
    } else {
        // Error inserting customer information
        echo "Error: " . $query . "<br>" . mysqli_error($conn);
    }
} else {
    // If the form is not submitted, redirect the user to the checkout page
    header("Location: checkout.php");
    exit();
}
?>