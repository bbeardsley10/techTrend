<?php
//Include the database connection file
include 'db_connection.php';
// Start session
session_start();


if (!$conn) {
    echo "No Connection";
    exit; // Exit script if connection fails
}


echo "<h1>My Cart</h1>";

// Check if cart is empty
if (isset($_SESSION["cart"]) && !empty($_SESSION["cart"])) {
    $totalPrice = 0;
    // Loop through cart items and display them
    foreach ($_SESSION["cart"] as $productId => $product) {
        echo "<p>Name: " . $product["name"] . ", Price: $" . $product["price"] . ", Quantity: " . $product["quantity"];
        echo " <a href='remove_from_cart.php?product_id=$productId'>Remove</a></p>";
        $totalPrice += $product["price"] * $product["quantity"];
    }
    echo "<p>Total Price: $" . $totalPrice . "</p>";
    
    // Checkout button
    echo "<form action='checkout.php' method='post'>";
    echo "<input type='submit' value='Checkout'>";
    echo "</form>";
} else {
    echo "<p>Your cart is empty.</p>";
}
?>

<!-- Link to view home -->
<a href="index.php">Home</a>