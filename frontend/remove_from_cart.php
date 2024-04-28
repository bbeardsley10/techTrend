<?php
// Include the database connection file
include 'db_connection.php';
// Start session
session_start();

if (!$conn) {
    die("Failed to connect to the database");
}

// Check if product ID is provided in the URL
if (isset($_GET['product_id'])) {
    // Validate the product ID to ensure it's an integer
    $productId = filter_var($_GET['product_id'], FILTER_VALIDATE_INT);

    if ($productId === false) {
        echo "Invalid product ID.";
        exit();
    }

    // Check if the product exists in the cart
    if (isset($_SESSION['cart'][$productId])) {
        // If quantity is more than 1, decrease it by 1
        if ($_SESSION['cart'][$productId]['quantity'] > 1) {
            $_SESSION['cart'][$productId]['quantity'] -= 1;
        } else {
            // If quantity is 1 or less, remove the product from the cart
            unset($_SESSION['cart'][$productId]);
        }

        // Redirect back to the cart page
        header("Location: cart.php");
        exit();
    } else {
        echo "Product not found in the cart.";
    }
} else {
    echo "Invalid request. Product ID not provided.";
}