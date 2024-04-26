<?php
// Include the database connection file
include 'db_connection.php';
// Start session
session_start();

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $paymentAmount = $_POST['paymentAmount'];
    $paymentType = $_POST['paymentType'];
    $paymentDate = $_POST['paymentDate']; // No need to generate it again

    // Additional fields based on payment type
    if ($paymentType === "Credit/Debit Card") {
        $cardNumber = $_POST['cardNumber'];
        $expiryMonth = $_POST['expiryMonth'];
        $expiryYear = $_POST['expiryYear'];

        // Insert payment information into the database (Credit/Debit Card)
        $query = "INSERT INTO payment (Payment_Amount, Payment_Type, Payment_Date, Card_Number, Expiry_Month, Expiry_Year) 
                  VALUES ('$paymentAmount', '$paymentType', '$paymentDate', '$cardNumber', '$expiryMonth', '$expiryYear')";
    } elseif ($paymentType === "Gift Card") {
        $giftCardNumber = $_POST['giftCardNumber'];

        // Insert payment information into the database (Gift Card)
        $query = "INSERT INTO payment (Payment_Amount, Payment_Type, Payment_Date, Card_Number) 
                  VALUES ('$paymentAmount', '$paymentType', '$paymentDate', '$giftCardNumber')";
    }

    if (mysqli_query($conn, $query)) {
        // Payment information inserted successfully

        // Retrieve the generated Payment_ID
        $paymentId = mysqli_insert_id($conn);

        // Update the corresponding customer record with the actual Payment_ID
        $customerId = $_SESSION['customer_id'];
        $updateCustomerQuery = "UPDATE customer SET Payment_ID = '$paymentId' WHERE Customer_ID = '$customerId'";
        
        if (mysqli_query($conn, $updateCustomerQuery)) {
            // Customer record updated successfully
            // Redirect to order confirmation page
            header("Location: ../frontend/order_confirmation.php");
            exit();
        } else {
            // Error updating customer record
            echo "Error: " . $updateCustomerQuery . "<br>" . mysqli_error($conn);
        }
    } else {
        // Error inserting payment information
        echo "Error: " . $query . "<br>" . mysqli_error($conn);
    }
} else {
    // If the form is not submitted, redirect the user to the payment page
    header("Location: ../frontend/payment.php");
    exit();
}
?>
