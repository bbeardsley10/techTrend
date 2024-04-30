<?php
// Start session
session_start();
// Include the database connection file
include 'db_connection.php';

if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

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

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        
        // Calculate the total price from the cart
        $totalPrice = 0; // Initialize total price
        if (isset($_SESSION["cart"]) && !empty($_SESSION["cart"])) {
            foreach ($_SESSION["cart"] as $productId => $product) {
                $quantity = (int)($product["quantity"] ?? 0); // Ensure quantity is valid
                $price = (float)($product["price"]); // Ensure price is valid
                $totalPrice += ($price * $quantity); // Calculate total price
            }
        } else {
            echo "Cart is empty, cannot process order.";
            exit();
        }

        $paymentType = $_POST['paymentType'];
        $paymentDate = $_POST['paymentDate'] ?? date("Y-m-d H:i:s");

        // Insert payment information into the payment table
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
            $stmt->bind_param("dsssi", $totalPrice, $paymentType, $paymentDate, $giftCardNumber, $customer_ID);
        } else {
            echo "Invalid payment type.";
            exit();
        }

        // Execute the payment insertion
        if ($stmt->execute()) {
            $paymentId = $conn->insert_id; // Get the generated Payment_ID
            $_SESSION['Payment_ID'] = $paymentId;
            // Insert a single order into customer_order with the total price
            $orderQuery = "INSERT INTO customer_order 
                           (Order_Date, Order_Amount, Customer_ID) 
                           VALUES (?, ?, ?)";
            $stmt = $conn->prepare($orderQuery);
            $stmt->bind_param("sdi", $paymentDate, $totalPrice, $customer_ID); // Use calculated total price

            if (!$stmt->execute()) {
                echo "Error inserting customer order: " . $stmt->error;
                exit();
            }

           

            // Clear the cart after a successful operation
            unset($_SESSION["cart"]);
            $clearCartQuery = "TRUNCATE Table cart";
            $clearCartStmt = $conn->prepare($clearCartQuery);
            if ($clearCartStmt->execute()) {
                // Redirect to the order confirmation page
                header("Location: order_confirmation.php");
                exit();
            } else {
                echo "Error clearing cart: " . $conn->error;
                exit();
            }
            // Redirect to the order confirmation page
            header("Location: order_confirmation.php");
            exit();
        } else {
            echo "Error inserting payment information: " . $stmt->error;
            exit();
        }
    } else {
        header("Location: payment.php");
        exit(); // If the form is not submitted, redirect to the payment page
    }
} else {
    echo "Error fetching customer information: " . $stmt->error;
    exit();
}
