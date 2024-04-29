<?php
// Start session
session_start();
// Include the database connection file
include 'db_connection.php';

// Ensure the user is logged in
if (!isset($_SESSION['Customer_Username'])) {
    header("Location: login.php");
    exit(); // Redirect to login page if no user is logged in
}

// Get the customer's username that is currently logged in
$customerUsername = $_SESSION['Customer_Username'];

// Fetch Customer_ID from the customer table
$customerQuery = "SELECT Customer_ID FROM customer WHERE Customer_Username = ?";
$stmt = $conn->prepare($customerQuery);
$stmt->bind_param("s", $customerUsername);
$stmt->execute();
$result = $stmt->get_result();

if ($result && $result->num_rows > 0) {
    $customer = mysqli_fetch_assoc($result);
    $customer_ID = $customer['Customer_ID']; 

    // Check if the form is submitted
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        
        // Calculate the total price from the cart
        $totalPrice = 0;
        if (isset($_SESSION["cart"]) && !empty($_SESSION["cart"])) {
            foreach ($_SESSION["cart"] as $productId => $product) {
                $quantity = (int)($product["quantity"] ?? 0);
                $totalPrice += (float)($product["price"] * $quantity); 
            }
        } else {
            echo "Cart is empty, cannot process order.";
            exit();
        }

      
        $paymentType = $_POST['paymentType'];
        $paymentDate = $_POST['paymentDate'] ?? date("Y-m-d H:i:s"); 

        // Insert payment information into the payment table based on Customer_ID
        if ($paymentType === "Credit/Debit Card") {
            $cardNumber = $_POST['cardNumber'];
            $expiryMonth = $_POST['expiryMonth'];
            $expiryYear = $_POST['expiryYear'];

            $query = "INSERT INTO payment 
                      (Payment_Amount, Payment_Type, Payment_Date, Card_Number, Expiry_Month, Expiry_Year, Customer_ID) 
                      VALUES (?, ?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("dsssiii", $totalPrice, $paymentType, $paymentDate, $cardNumber, $expiryMonth, $expiryYear, $customer_ID);
        } elseif ($paymentType === "Gift Card") {
            $giftCardNumber = $_POST['giftCardNumber'];

            $query = "INSERT INTO payment 
                      (Payment_Amount, Payment_Type, Payment_Date, Card_Number, Customer_ID) 
                      VALUES (?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("dsssi", $totalPrice, $paymentType, $paymentDate, $giftCardNumber, $customer_ID);
        }

        if ($stmt->execute()) {
            $paymentId = $conn->insert_id; // Get the generated Payment_ID

            // Update the customer record with the new Payment_ID
            $updateCustomerQuery = "UPDATE customer 
                                    SET Payment_ID = ? 
                                    WHERE Customer_ID = ?";
            $stmt = $conn->prepare($updateCustomerQuery);
            $stmt->bind_param("ii", $paymentId, $customer_ID);

            if (!$stmt->execute()) {
                echo "Error updating customer with Payment_ID: " . mysqli_error($conn);
                exit();
            }

            // Process customer orders if the cart is not empty
            foreach ($_SESSION["cart"] as $productId => $product) {
                $quantity = (int)($product["quantity"] ?? 0);
                $orderAmount = (float)($product["price"] * $quantity); 

                // Insert into customer_order, with Customer_ID
                $orderQuery = "INSERT INTO customer_order 
                               (Order_Date, Order_Amount, Product_ID, Customer_ID) 
                               VALUES (?, ?, ?, ?)";
                $stmt = $conn->prepare($orderQuery);
                $stmt->bind_param("sdii", $paymentDate, $orderAmount, $productId, $customer_ID);

                if (!$stmt->execute()) {
                    echo "Error inserting customer order: " . mysqli_error($conn);
                    exit();
                }

                // Update product inventory
                $updateProductQuery = "UPDATE product 
                                       SET Product_Quantity = Product_Quantity - ? 
                                       WHERE Product_ID = ?";
                $stmt = $conn->prepare($updateProductQuery);
                $stmt->bind_param("ii", $quantity, $productId);

                if (!$stmt->execute()) {
                    echo "Error updating product inventory: " . mysqli_error($conn);
                    exit();
                }
            }

            // Clear the cart after successful inventory update
            unset($_SESSION["cart"]);

            // Redirect to the order confirmation page
            header("Location: order_confirmation.php");
            exit();
        } else {
            echo "Error inserting payment information: " . mysqli_error($conn);
            exit();
        }
    } else {
        header("Location: payment.php");
        exit(); // If form is not submitted, redirect to the payment page
    }
} else {
    die("Error fetching customer information: " . mysqli_error($conn));
}
