<?php
session_start(); // Start the session
include 'db_connection.php'; // Include the database connection

// Check if the database connection is successful
if (!$conn) {
    die("Failed to connect to the database");
}

// Check if a user is logged in
if (!isset($_SESSION['Customer_Username'])) { // If no user is logged in
    header("Location: customer-portal.html"); // Redirect to the customer portal
    exit();
}

echo "<h1>My Cart</h1>";

// Check if the cart exists and is not empty
if (isset($_SESSION["cart"]) && !empty($_SESSION["cart"])) {
    $totalPrice = 0;

    // Loop through cart items and display them
    foreach ($_SESSION["cart"] as $productId => $product) {
        $productName = htmlspecialchars($product["name"] ?? 'Unknown');
        $productPrice = (float)($product["price"] ?? 0);
        $productQuantity = (int)($product["quantity"] ?? 0);

        echo "<p>Name: $productName, Price: \$$productPrice, Quantity: $productQuantity";
        echo " <a href='remove_from_cart.php?product_id=" . htmlspecialchars($productId) . "'>Remove</a></p>";

        // Calculate total price
        if (is_numeric($productPrice) && is_numeric($productQuantity)) {
            $totalPrice += $productPrice * $productQuantity;
        }
    }

    echo "<p>Total Price: \$$totalPrice</p>";
    
    // Display Checkout button
    echo "<form action='payment.php' method='post'>";
    echo "<input type='hidden' name='totalPrice' value='" . htmlspecialchars($totalPrice) . "'>";
    echo "<input type='submit' value='Checkout'>";
    echo "</form>";
} else {
    // If the cart is empty, inform the user
    echo "<p>Your cart is empty.</p>";
}

?>

<!-- Link to view home -->
<a href="index.php">Home</a>
