<?php
// Include database connection
include 'db_connection.php';

// Start session
session_start();

// Check if product_id is provided via GET parameter
if (!isset($_GET['product_id']) || !is_numeric($_GET['product_id'])) {
    die("Invalid product ID."); // Error if product ID is not valid
}

$product_id = intval($_GET['product_id']); // Convert to integer for safety

// Fetch product information from the database
$query = "SELECT * FROM product WHERE Product_ID = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $product_id);
$stmt->execute();

$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("Product not found."); // Error if product not found
}

$product = $result->fetch_assoc(); // Retrieve the product information
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?php echo htmlspecialchars($product['Product_Name']); ?></title>
    <link rel="icon" href="img/techTrendIcon.png" type="image/x-icon">
</head>
<body>
    <header>
        <h1><?php echo htmlspecialchars($product['Product_Name']); ?></h1>
    </header>
    <main>
        <p>Price: $<?php echo htmlspecialchars($product['Product_Price']); ?></p>

      
        
        <!-- Check if product is available -->
        <?php if ($product['Product_Status'] === "in stock"): ?>
            <form action="add_to_cart.php" method="post">
                <input type="hidden" name="Product_ID" value="<?php echo htmlspecialchars($product['Product_ID']); ?>">
                <input type="submit" value="Add to Cart">
            </form>
        <?php else: ?>
            <p>This product is currently out of stock.</p>
        <?php endif; ?>
    </main>
    <footer>
        <!-- Additional links or information can be added here -->
        <p>
        <a href="cart.php">View Cart</a>
        </p>
        <p>
        <a href="index.php">Back to Home</a> <!-- Link to go back to the main page -->
        </p>
    </footer>
</body>
</html>
