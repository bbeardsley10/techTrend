
<?php

// Include database connection
include 'db_connection.php'; 
// Start session
session_start();

if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}
?>

<?php
$query = "SELECT Product_ID, Product_Name, Product_Price FROM product WHERE Product_Status = 'available'";
$result = $conn->query($query);

if ($result === false) {
    die("Error fetching products: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TechTrend - Home</title>
    <link rel="icon" href="img/techTrendIcon.png" type="image/x-icon">
</head>
<body>
    <header>
        <h1>Welcome to TechTrend</h1>
    </header>
    <div class="user-id user-data">
        <p>Welcome <?php echo htmlspecialchars($_SESSION['Customer_Username']); ?>!</p>
    </div>

    <main>
         <!-- Diplays the current products that are 'in stock' -->
        <h2>Our Products</h2>
        <?php if ($result->num_rows > 0): ?>
            <ul>
            <?php while ($product = $result->fetch_assoc()): ?>
                <li>
                    <a href="product.php?product_id=<?php echo htmlspecialchars($product['Product_ID']); ?>">
                        <?php echo htmlspecialchars($product['Product_Name']); ?>
                    </a>
                    - $<?php echo htmlspecialchars($product['Product_Price']); ?>
                </li>
            <?php endwhile; ?>
            </ul>
        <?php else: ?>
            <p>No products available at the moment.</p>
        <?php endif; ?>
    </main>
    <footer>
    <footer>
        <!-- Link to view cart -->
    <p>
        <a href="cart.php">View Cart</p>
    </p>
    <!-- Link to admin signup -->
    <p>
        <a href="admin-portal.html">Admin Portal</a>
    </p>
    <p>
        <a href="customer-portal.html">Customer Portal</a>
    </p>
    </footer>
</body>
</html>







