<?php
include 'db_connection.php'; // Database connection
session_start();

// Ensure the user is logged in and has an admin role
if (!isset($_SESSION['Admin_Username'])) {
    header("Location: admin-signin.php");
    exit();
}

// Function to get the next product ID
function get_next_product_id($conn) {
    $query = "SELECT MAX(Product_ID) as max_id FROM product";
    $result = $conn->query($query);

    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        return $row['max_id'] + 1;
    } else {
        return 1; // Default value if there's no product
    }
}

// Function to edit or delete a product
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $productId = isset($_POST['product_id']) ? (int) $_POST['product_id'] : 0;
    $action = isset($_POST['action']) ? $_POST['action'] : '';

    if ($action === 'edit' && isset($_POST['quantity'])) {
        // Update product quantity
        $quantity = (int) $_POST['quantity'];
        $query = "UPDATE product SET Product_Quantity = ? WHERE Product_ID = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ii", $quantity, $productId);
        $stmt->execute();
        $stmt->close();
    } elseif ($action === 'delete') {
        // Delete the product
        $query = "DELETE FROM product WHERE Product_ID = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $productId);
        $stmt->execute();
        $stmt->close();
    }
}

// Add a new product
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'add') {
    $productName = $_POST['product_name'];
    $productPrice = $_POST['product_price'];
    $productQuantity = $_POST['product_quantity'];

    // Get the next product ID
    $nextProductId = get_next_product_id($conn);

    // Insert new product into the database
    $query = "INSERT INTO product (Product_ID, Product_Name, Product_Price, Product_Quantity, Product_Status) 
              VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    $status = 'available'; // Default status
    $stmt->bind_param("isdis", $nextProductId, $productName, $productPrice, $productQuantity, $status);
    $stmt->execute();
    $stmt->close();
    error_log("Product Status: $status");
    header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");

}

// Fetch all products for the inventory table
$query = "SELECT * FROM product";
$result = $conn->query($query);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="icon" href="img/techTrendIcon.png" type="image/x-icon">
    <title>Admin Inventory Management</title>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Get the form by its ID or select it another way
        const form = document.querySelector('form'); 
        form.addEventListener('submit', function() {
            // Disable the submit button to prevent double submission
            const submitButton = form.querySelector('button[type="submit"]');
            submitButton.disabled = true;
        });
    });
    </script>
</head>
<body>
    <h1>Inventory Management</h1>

    <!-- Form to add a new product -->
    <h2>Add New Product</h2>
    <form action="" method="post">
        <input type="hidden" name="action" value="add">
        <label>Product Name: <input type="text" name="product_name" required></label>
        <label>Product Price: <input type="number" step="0.01" name="product_price" required></label>
        <label>Product Quantity: <input type="number" name="product_quantity" required></label>
        <button type="submit">Add Product</button>
    </form>

    <!-- Table displaying current inventory with update and delete options -->
    <h2>Inventory</h2>
    <table>
        <tr>
            <th>Product Name</th>
            <th>Current Quantity</th>
            <th>Actions</th>
        </tr>
        <?php while ($product = $result->fetch_assoc()): ?>
            <tr>
                <td><?php echo htmlspecialchars($product['Product_Name']); ?></td>
                <td><?php echo htmlspecialchars($product['Product_Quantity']); ?></td>
                <td>
                    <!-- Form for updating or deleting a product -->
                    <form action="update_inventory.php" method="post">
                        <input type="hidden" name="product_id" value="<?php echo htmlspecialchars($product['Product_ID']); ?>">
                        <input type="number" name="quantity" min="0" placeholder="Update Quantity">
                        <button type="submit" name="action" value="edit">Edit</button>
                        <button type="submit" name="action" value="delete">Delete</button>
                    </form>
                </td>
            </tr>
        <?php endwhile; ?>
    </table>
    
    <a href="admin-menu.php">Return to Menu</a>
    <!-- Logout option -->
    <a href="admin-portal.html">Logout</a>
</body>
</html>
