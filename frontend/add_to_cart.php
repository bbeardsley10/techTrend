<?php
// Include the database connection file
include 'db_connection.php';
// Start session
session_start();

if (!$conn) {
    die("Failed to connect to the database");
}

// Check if the form has been submitted with a valid Product_ID
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['Product_ID'])) {
    // Validate Product_ID and ensure it's a positive integer
    $productId = filter_var($_POST['Product_ID'], FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);

    if ($productId === false) {
        echo "Invalid Product ID.";
        exit();
    }

    // Retrieves the product from the product database
    $query = "SELECT * FROM product WHERE Product_ID = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $productId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $product = $result->fetch_assoc();

        if ($product['Product_Status'] == "in stock") {
            // Update product quantity and status in the database
            $newQuantity = max(0, $product['Product_Quantity'] - 1); // Ensure non-negative
            $productStatus = ($newQuantity >= 1) ? "in stock" : "out of stock";

            $updateProductQuery = "UPDATE product SET Product_Quantity = ?, Product_Status = ? WHERE Product_ID = ?";
            $updateProductStmt = $conn->prepare($updateProductQuery);
            $updateProductStmt->bind_param("isi", $newQuantity, $productStatus, $productId);
            $updateProductStmt->execute();
            $updateProductStmt->close();

            // Initialize cart if not already set
            if (!isset($_SESSION["cart"])) {
                $_SESSION["cart"] = array();
            }

            $cartId = session_id(); // Session ID for cart

            if (isset($_SESSION["cart"][$productId])) {
                $_SESSION["cart"][$productId]["quantity"] += 1; // Increment quantity
            } else {
                $_SESSION["cart"][$productId] = array(
                    "name" => htmlspecialchars($product["Product_Name"]),
                    "price" => floatval($product["Product_Price"]),
                    "quantity" => 1 // Initial quantity
                );
            }

            $cartQuantity = $_SESSION["cart"][$productId]["quantity"];

            // Update or insert into the cart
            if (isset($_SESSION["cart"][$productId]) && $cartQuantity > 1) {
                $updateCartQuery = "UPDATE cart SET Quantity = ? WHERE Cart_ID = ? AND Product_ID = ?";
                $updateCartStmt = $conn->prepare($updateCartQuery);
                $updateCartStmt->bind_param("isi", $cartQuantity, $cartId, $productId);
                $updateCartStmt->execute();
                $updateCartStmt->close();
            } else {
                $insertCartQuery = "INSERT INTO cart (Cart_ID, Product_ID, Quantity) VALUES (?, ?, ?)";
                $insertCartStmt = $conn->prepare($insertCartQuery);
                $insertCartStmt->bind_param("sii", $cartId, $productId, $cartQuantity); // Ensure valid quantity
                $insertCartStmt->execute();
                $insertCartStmt->close();
            }

            // Redirect to the previous page
            $redirectUrl = $_SERVER['HTTP_REFERER'] ?? 'index.php';
            header("Location: $redirectUrl");
            exit();
        } else {
            echo "This product is currently out of stock.";
            exit();
        }
    } else {
        echo "Product not found.";
        exit();
    }

    $stmt->close();
} else {
    echo "Invalid request.";
    exit();
}
