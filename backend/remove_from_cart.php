<?php
session_start();

// Check if product ID is provided in the URL
if(isset($_GET['product_id'])) {
    $productId = $_GET['product_id'];
    
    // Check if the product exists in the cart
    if(isset($_SESSION['cart'][$productId])) {
        // If quantity is more than 1, decrease it by 1
        if($_SESSION['cart'][$productId]['quantity'] > 1) {
            $_SESSION['cart'][$productId]['quantity'] -= 1;
        } else {
            // If quantity is 1, remove the entire product from the cart
            unset($_SESSION['cart'][$productId]);
        }
        // Redirect back to the cart page
        header("Location: ../frontend/cart.php");
        exit();
    } else {
        echo "Product not found in the cart.";
    }
} else {
    echo "Invalid request. Product ID not provided.";
}
?>
