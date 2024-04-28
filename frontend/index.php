<?php
// Include database connection
include 'db_connection.php'; // Check this file for proper connection details

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
    <title>TechTrend - Home</title>
    <link rel="icon" href="img/techTrendIcon.png" type="image/x-icon">
</head>
<body>
    <header>
        <h1>Welcome to TechTrend</h1>
    </header>
    <main>
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
        <!-- Footer content -->
    </footer>
    <!-- Other links -->
    <p>
        <a href="cart.php">View Cart</a>
    </p>
    <p>
        <a href="admin-portal.html">Admin Portal</a>
    </p>
    <p>
        <a href="customer-portal.html">Customer Portal</a>
    </p>
</body>
</html>
