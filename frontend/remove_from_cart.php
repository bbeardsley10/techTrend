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

    // Get the current session cart ID
    $cartId = session_id();

    // Check if the product is in the session cart
    if (isset($_SESSION['cart'][$productId])) {
        if ($_SESSION['cart'][$productId]['quantity'] > 1) {
            // Decrement the quantity in the session
            $_SESSION['cart'][$productId]['quantity'] -= 1;

            // Decrement the quantity in the database cart table
            $updateCartQuery = "UPDATE cart SET Quantity = ? WHERE Cart_ID = ? AND Product_ID = ?";
            $updateCartStmt = $conn->prepare($updateCartQuery);
            if ($updateCartStmt) {
                $newQuantity = $_SESSION['cart'][$productId]['quantity'];
                $updateCartStmt->bind_param("isi", $newQuantity, $cartId, $productId);
                $updateCartStmt->execute();
                $updateCartStmt->close();
            } else {
                echo "Failed to update cart: " . $conn->error;
                exit();
            }
        } else {
            // Remove the product from the session
            unset($_SESSION['cart'][$productId]);

            // Remove the product from the database cart table
            $deleteCartQuery = "DELETE FROM cart WHERE Cart_ID = ? AND Product_ID = ?";
            $deleteCartStmt = $conn->prepare($deleteCartQuery);
            if ($deleteCartStmt) {
                $deleteCartStmt->bind_param("si", $cartId, $productId);
                $deleteCartStmt->execute();
                $deleteCartStmt->close();
            } else {
                echo "Failed to remove from cart: " . $conn->error;
                exit();
            }
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
?>
