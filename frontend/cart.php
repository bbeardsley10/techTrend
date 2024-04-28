<?php
session_start();
// Include the database connection file
include 'db_connection.php';
// Start session


if (!$conn) {
    die("Failed to connect to the database");
}


echo "<h1>My Cart</h1>";

// Check if the cart exists and is not empty
if (isset($_SESSION["cart"]) && !empty($_SESSION["cart"])) {
    $totalPrice = 0;

    // Loop through cart items and display them
    foreach ($_SESSION["cart"] as $productId => $product) {
        // Sanitize output to prevent XSS
        $productName = htmlspecialchars($product["name"]);
        $productPrice = number_format((float) $product["price"], 2);
        $productQuantity = (int) $product["quantity"];

        echo "<p>Name: $productName, Price: \$$productPrice, Quantity: $productQuantity";
        echo " <a href='remove_from_cart.php?product_id=" . htmlspecialchars($productId) . "'>Remove</a></p>";

        // Calculate total price
        $totalPrice += $productPrice * $productQuantity;
    }

    echo "<p>Total Price: \$$totalPrice</p>";
    
    // Display Checkout button
    echo "<form action='payment.php' method='post'>";
    echo "<input type='hidden' name='totalPrice' value='" . htmlspecialchars($totalPrice) . "'>";
    echo "<input type='submit' value='Checkout'>";
    echo "</form>";
} else {
    // If cart is empty, inform the user
    echo "<p>Your cart is empty.</p>";
}

?>

<!-- Link to view home -->
<a href="index.php">Home</a>
