<?php
// Include database connection
include 'db_connection.php';

// Start session
session_start();

if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

// Check to see if product_id is provided 
if (!isset($_GET['product_id']) || !is_numeric($_GET['product_id'])) {
    die("Invalid product ID."); // Error if product ID is not valid
}

$product_id = intval($_GET['product_id']); // Converts the product ID to an integer to an integer

// Fetch product information from the product table in the database
$query = "SELECT * FROM product WHERE Product_ID = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $product_id);
$stmt->execute();

$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("Product not found."); // Error if product not found
}

$product = $result->fetch_assoc(); // Retrieves the product information
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?php echo htmlspecialchars($product['Product_Name']); ?></title>
    <link rel="icon" href="img/techTrendIcon.png" type="image/x-icon">
    <link rel="stylesheet" href="product-style.css">
</head>
<style>
    img {
    width: 200px; 
    height: 200px; 
}
</style>
<body>
    <header>
        <h1><?php echo htmlspecialchars($product['Product_Name']); ?></h1>
    </header>
    <main>
        <!-- Display product image -->
        <img src="<?php echo htmlspecialchars($product['Product_Image']); ?>" alt="<?php echo htmlspecialchars($product['Product_Name']); ?>">

        <p>Price: $<?php echo htmlspecialchars($product['Product_Price']); ?></p>
        <!-- Checks to see if the product is in stock -->
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
            <!-- Link to go back to the main page -->
        <a href="index.php">Back to Home</a> 
        </p>
    </footer>
</body>
</html>