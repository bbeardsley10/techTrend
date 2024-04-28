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

    // Retrieve product details from the database
    $query = "SELECT * FROM product WHERE Product_ID = ?";
    $stmt = $conn->prepare($query);

    if ($stmt) {
        $stmt->bind_param("i", $productId);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $product = $result->fetch_assoc();

            // Check if the product is available
            if ($product['Product_Status'] == "available") {
                // Initialize cart if not already set
                if (!isset($_SESSION["cart"])) {
                    $_SESSION["cart"] = array();
                }

                // Check if product is already in the cart
                if (isset($_SESSION["cart"][$productId])) {
                    // Increment quantity
                    $_SESSION["cart"][$productId]["quantity"] += 1;
                } else {
                    // Add product to the cart with quantity 1
                    $_SESSION["cart"][$productId] = array(
                        "name" => htmlspecialchars($product["Product_Name"]),
                        "price" => floatval($product["Product_Price"]),
                        "quantity" => 1
                    );
                }

                // Redirect to the referring page or default to index.php
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
