<?php
include '../backend/db_connection.php';

// Start session
session_start();

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['product_id'])) {
    // Get product ID from the form
    $productId = $_POST['product_id'];

    // Retrieve product details from the database
    $query = "SELECT * FROM product WHERE Product_ID = $productId";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) > 0) {
        $product = mysqli_fetch_assoc($result);

        // Check if the product is available
        if ($product['Product_Status'] == "available") {
            // Initialize cart if it doesn't exist
            if (!isset($_SESSION["cart"])) {
                $_SESSION["cart"] = array();
            }

            // Check if product is already in the cart
            if (isset($_SESSION["cart"][$productId])) {
                // Update quantity if product is already in the cart
                $_SESSION["cart"][$productId]["quantity"] += 1;
            } else {
                // Add product to the cart with quantity 1
                $_SESSION["cart"][$productId] = array(
                    "name" => $product["Product_Name"],
                    "price" => $product["Product_Price"],
                    "quantity" => 1
                );
            }

            // Redirect back to the product page
            header("Location: {$_SERVER['HTTP_REFERER']}");
            exit();
        } else {
            // Product is unavailable, display message
            echo "This product is currently out of stock.";
        }
    } else {
        echo "Product not found.";
    }
}

// Fixed product ID for the third product
$productId = 1;

// Retrieve product details from the database
$query = "SELECT * FROM product WHERE Product_ID = $productId";
$result = mysqli_query($conn, $query);

if(mysqli_num_rows($result) > 0) {
    $product = mysqli_fetch_assoc($result);
    // Display product details
?>
<!DOCTYPE html>
<html>
<head>
    <title><?php echo $product['Product_Name']; ?></title>
</head>
<body>
    <h1><?php echo $product['Product_Name']; ?></h1>
    <p>Price: $<?php echo $product['Product_Price']; ?></p>
    <!-- Check availability and display form only if product is available -->
    <?php if ($product['Product_Status'] == "available"): ?>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <input type="hidden" name="product_id" value="<?php echo $product['Product_ID']; ?>">
            <input type="submit" value="Add to Cart">
        </form>
    <?php else: ?>
        <p>This product is currently out of stock.</p>
    <?php endif; ?>
    <!-- Link to view cart -->
    <a href="cart.php">View Cart</a>
    <!-- Link to view home -->
    <a href="index.php">Home</a>
</body>
</html>
<?php
} else {
    echo "Product not found.";
}
?>
