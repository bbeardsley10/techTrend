<?php 
// Include the database connection file
include 'db_connection.php';
// Start session
session_start();

// Ensure the user is logged in and has an admin role
if (!isset($_SESSION['Admin_Username'])) {
    header("Location: admin-signin.php");
    exit();
}

// Fetch all products for the inventory table
$query = "SELECT * FROM product";
$result = $conn->query($query);

?>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Menu</title>
    <link rel="icon" href="img/techTrendIcon.png" type="image/x-icon">
    <link rel="stylesheet" href="admin-menu-style.css">
</head>
<body>
    <h1>Admin Inventory Management</h1>
    <div class="user-id user-data">
        <p>Welcome <?php echo htmlspecialchars($_SESSION['Admin_Username']); ?>!</p>
    </div>

    <a href="update_inventory.php">Edit Inventory</a>
    <!-- Form to add a new product -->
    <!--<h2>Add New Product</h2>
    <form action="update_inventory.php" method="post"> 
        <input type="hidden" name="action" value="add_product"> 
        <label>Product Name: <input type="text" name="product_name" required></label>
        <label>Product Price: <input type="number" step="0.01" name="product_price" required></label>
        <label>Product Quantity: <input type="number" name="product_quantity" required></label>
        <button type="submit">Add Product</button>
    </form>
            -->
    <!-- Inventory list -->
    <h2>Current Inventory</h2>
    <table>
        <tr>
            <th>Product Name</th>
            <th>Current Quantity</th>
            <th>Product Status</th>
        </tr>
        <!-- Display current inventory -->
        <?php while ($product = $result->fetch_assoc()): ?>
            <tr>
                <td><?php echo htmlspecialchars($product['Product_Name']); ?></td>
                <td><?php echo htmlspecialchars($product['Product_Quantity']); ?></td>
                <td><?php echo htmlspecialchars($product['Product_Status']); ?></td>
            </tr>
        <?php endwhile; ?>
    </table>

    <a href="admin-portal.html">Logout</a>
</body>
</html>
