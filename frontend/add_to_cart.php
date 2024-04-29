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

    // Retrieves the product ID from the product database
    $query = "SELECT * FROM product WHERE Product_ID = ?";
    $stmt = $conn->prepare($query);

    if ($stmt) {
        $stmt->bind_param("i", $productId);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $product = $result->fetch_assoc();

            // Checks to see fi the product is currently in stock
            if ($product['Product_Status'] == "in stock") {
                // Initialize cart if not already set
                if (!isset($_SESSION["cart"])) {
                    $_SESSION["cart"] = array();
                }

                $cartId = session_id(); // Session ID is used as the Cart ID

                // Checks if the product is in the cart already and if it is it gets auto incremented
                if (isset($_SESSION["cart"][$productId])) {
                    // Increment quantity in the session cart
                    $_SESSION["cart"][$productId]["quantity"] += 1;

                    // Update the quantity in the cart table
                    $updateCartQuery = "UPDATE cart SET Quantity = ? WHERE Cart_ID = ? AND Product_ID = ?";
                    $updateCartStmt = $conn->prepare($updateCartQuery);
                    if ($updateCartStmt) {
                        $newQuantity = $_SESSION["cart"][$productId]["quantity"];
                        $updateCartStmt->bind_param("isi", $newQuantity, $cartId, $productId);
                        $updateCartStmt->execute();
                        $updateCartStmt->close();
                    } else {
                        echo "Failed to update cart: " . $conn->error;
                    }
                } else {
                    // Add product to the session cart with quantity 1
                    $_SESSION["cart"][$productId] = array(
                        "name" => htmlspecialchars($product["Product_Name"]),
                        "price" => floatval($product["Product_Price"]),
                        "quantity" => 1
                    );

                    // Insert a new record in the cart table whenever it gets added to the cart
                    $insertCartQuery = "INSERT INTO cart (Cart_ID, Product_ID, Quantity) VALUES (?, ?, ?)";
                    $insertCartStmt = $conn->prepare($insertCartQuery);
                    if ($insertCartStmt) {
                        $quantity = 1; // New item has initial quantity 1
                        $insertCartStmt->bind_param("sii", $cartId, $productId, $quantity);
                        $insertCartStmt->execute();
                        $insertCartStmt->close();
                    } else {
                        echo "Failed to insert into cart: " . $conn->error;
                    }
                }

                // Redirects to index.php
                $redirectUrl = $_SERVER['HTTP_REFERER'] ?? 'index.php';
                header("Location: $redirectUrl");
                exit();
            } else {
                echo "This product is currently out of stock.";
            }
        } else {
            echo "Product not found.";
        }

        $stmt->close();
    } else {
        echo "Database query failed: " . $conn->error;
    }
} else {
    echo "Invalid request.";
}
