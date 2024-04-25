
<?php 
    session_start();

    $con = mysqli_connect('localhost', 'root', '', 'techtrend');

    if (isset($_SESSION['Customer_Username'])) {
        $customerUsername = $_SESSION['Customer_Username'];

        // Close the statement
        $stmt->close();
    } else {
        // Redirect to the customer sign-in page if the user is not logged in
        header("Location: customer-signin.php");
        exit();
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
        <p><?php echo $_SESSION['Customer_Username']; ?></p>
    </div>
    <main>
        <!-- Link to product details page -->
        <a href="product1.php?product_id=1">View Product 1</a>
        <a href="product2.php?product_id=2">View Product 2</a>
        <a href="product3.php?product_id=3">View Product 3</a>
    </main>
    <footer>
        <!-- Footer content -->
    </footer>
    
    <!-- Link to view cart -->
    <p>
        <a href="cart.php">View Cart</p>
    </p>
    <!-- Link to admin signup -->
    <p>
        <a href="admin-portal.html">Admin Portal</a>
    </p>
    <!-- Footer content -->
    <p>
        <a href="customer-portal.html">Customer Portal</a>
    </p>
    
</body>
</html>