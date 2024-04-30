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
    // Validate the product ID to ensure it's a positive integer
    $productId = filter_var($_GET['product_id'], FILTER_VALIDATE_INT);

    if ($productId === false) {
        echo "Invalid Product ID.";
        exit();
    }

    // Get the current session cart ID
    $cartId = session_id();

    // Check if the product is in the session cart
    if (isset($_SESSION['cart'][$productId])) {
        // Decrement quantity in the session cart
        if ($_SESSION['cart'][$productId]['quantity'] > 1) {
            $_SESSION['cart'][$productId]['quantity'] -= 1;

            // Update the quantity in the cart table in the database
            $updateCartQuery = "UPDATE cart SET Quantity = ? WHERE Cart_ID = ? AND Product_ID = ?";
            $updateCartStmt = $conn->prepare($updateCartQuery);
            if ($updateCartStmt) {
                $updateCartStmt->bind_param("isi", $_SESSION['cart'][$productId]['quantity'], $cartId, $productId);
                $updateCartStmt->execute();
                $updateCartStmt->close();

                // Increment the product quantity in the product table
                $incrementProductQuery = "UPDATE product SET Product_Quantity = Product_Quantity + 1 WHERE Product_ID = ?";
                $incrementProductStmt = $conn->prepare($incrementProductQuery);
                if ($incrementProductStmt) {
                    $incrementProductStmt->bind_param("i", $productId);
                    $incrementProductStmt->execute();
                    $incrementProductStmt->close();
                } else {
                    echo "Failed to increment product quantity: " . $conn->error;
                    exit();
                }
            } else {
                echo "Failed to update cart: " . $conn->error;
                exit();
            }
        } else {
            // If quantity is 1, remove the product from the session
            unset($_SESSION['cart'][$productId]);

            // Delete the product from the cart table in the database
            $deleteCartQuery = "DELETE FROM cart WHERE Cart_ID = ? AND Product_ID = ?";
            $deleteCartStmt = $conn->prepare($deleteCartQuery);
            if ($deleteCartStmt) {
                $deleteCartStmt->bind_param("si", $cartId, $productId);
                $deleteCartStmt->execute();
                $deleteCartStmt->close();

                // Increment the product quantity in the product table
                $incrementProductQuery = "UPDATE product SET Product_Quantity = Product_Quantity + 1 WHERE Product_ID = ?";
                $incrementProductStmt = $conn->prepare($incrementProductQuery);
                if ($incrementProductStmt) {
                    $incrementProductStmt->bind_param("i", $productId);
                    $incrementProductStmt->execute();
                    $incrementProductStmt->close();
                } else {
                    echo "Failed to increment product quantity: " . $conn->error;
                    exit();
                }
            } else {
                echo "Failed to delete from cart: " . $conn->error;
                exit();
            }
        }

        // Redirect back to the cart page
        header("Location: cart.php");
        exit();
    } else {
        echo "Product not found in the cart.";
        exit();
    }
} else {
    echo "Invalid request. Product ID not provided.";
    exit();
}
