<?php

// Start session
session_start();
//Include the database connection file
include 'db_connection.php';



if (!$conn) {
    echo "No Connection";
    exit; // Exit script if connection fails
}
// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $totalPrice = $_POST['totalPrice'];
    
    // Retrieve form data
    $paymentType = $_POST['paymentType'];
    $paymentDate = $_POST['paymentDate']; // No need to generate it again

    // Additional fields based on payment type
    if ($paymentType === "Credit/Debit Card") {
        $cardNumber = $_POST['cardNumber'];
        $expiryMonth = $_POST['expiryMonth'];
        $expiryYear = $_POST['expiryYear'];

        // Insert payment information into the database (Credit/Debit Card)
        $query = "INSERT INTO payment (Payment_Amount, Payment_Type, Payment_Date, Card_Number, Expiry_Month, Expiry_Year) 
                  VALUES ('$totalPrice', '$paymentType', '$paymentDate', '$cardNumber', '$expiryMonth', '$expiryYear')";
    } elseif ($paymentType === "Gift Card") {
        $giftCardNumber = $_POST['giftCardNumber'];

        // Insert payment information into the database (Gift Card)
        $query = "INSERT INTO payment (Payment_Amount, Payment_Type, Payment_Date, Card_Number) 
                  VALUES ('$totalPrice', '$paymentType', '$paymentDate', '$giftCardNumber')";
    }

    if (mysqli_query($conn, $query)) {
        $paymentId = mysqli_insert_id($conn);

        // Update the Customer record with the new Payment_ID
        $customerUsername = $_SESSION['Customer_Username'];
        $updateCustomerQuery = "UPDATE Customer SET Payment_ID = '$paymentId' WHERE Customer_Username = '$customerUsername'";

        if (mysqli_query($conn, $updateCustomerQuery)) {
            // Update product inventory based on the items in the cart
            if (isset($_SESSION["cart"]) && !empty($_SESSION["cart"])) {
                foreach ($_SESSION["cart"] as $productId => $product) {
                    $quantity = (int) $product["quantity"];

                    // Update the product table to reduce the quantity
                    $updateProductQuery = "UPDATE product SET Product_Quantity = Product_Quantity - $quantity WHERE Product_ID = '$productId'";

                    if (!mysqli_query($conn, $updateProductQuery)) {
                        echo "Error updating product inventory: " . mysqli_error($conn);
                        exit();
                    }
                }
            }

            // Clear the cart from the session after successful inventory update
            unset($_SESSION["cart"]);

            // Redirect to the order confirmation page
            header("Location: order_confirmation.php");
            exit();
        } else {
            echo "Error updating customer record: " . mysqli_error($conn);
        }
    } else {
        echo "Error inserting payment information: " . mysqli_error($conn);
    }
} else {
    header("Location: payment.php");
    exit(); // If the form is not submitted, redirect to the payment page
}
?>