<?php 
// Include the database connection file
include 'db_connection.php';
// Start session
session_start();

// Ensure the user that is logged in is stored in the correct admin database
if (!isset($_SESSION['Admin_Username'])) {
    header("Location: admin-signin.php");
    exit();
}

// Returns all of the products in the current inventory 
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
    
    <!-- Displays the Current Inventory -->
    <h2>Current Inventory</h2>
    <table>
        <tr>
            <th>Product Name</th>
            <th>Current Quantity</th>
            <th>Product Status</th>
        </tr>
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
