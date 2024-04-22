<?php 
    session_start();

    $con = mysqli_connect('localhost', 'root', '', 'techtrend');


    if(isset($_SESSION['Admin_Username'])) {
        $username = $_SESSION['Admin_Username'];
        $password = $_SESSION['Admin_Password'];

    } else {
        header("Location: admin-signin.php");
        exit();
    }

?>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>techTrend Admin Menu</title>
    <link rel="icon" href="\img\techTrendIcon.png" type="image/x-icon">
    <link rel="stylesheet" href="admin-menu-style.css">
</head>
<body>
    <h1>Admin Inventory Management</h1>
    
    <p>Welcome <?php echo $username; ?></p>
    <!-- Inventory List -->
    <h2>Inventory</h2>
    <table>
        <tr>
            <th>Product Name</th>
            <th>Current Quantity</th>
            <th>Action</th>
        </tr>
        <!-- Example row, replace with dynamic content -->
        <tr>
            <td>Product 1</td>
            <td>100</td>
            <td>
                <!-- Form for adding/removing/editing quantity -->
                <form action="update_inventory.php" method="post">
                    <input type="hidden" name="product_id" value="1"> <!-- Use hidden input for product ID -->
                    <input type="number" name="quantity" value="0" min="0" max="9999"> <!-- Input for quantity -->
                    <button type="submit" name="action" value="add">Add</button>
                    <button type="submit" name="action" value="remove">Remove</button>
                    <button type="submit" name="action" value="edit">Edit</button>
                </form>
            </td>
        </tr>
        <!-- Add more rows dynamically based on inventory -->
    </table>

    <!-- Add your JavaScript code here -->
    <script>
        // Add JavaScript for any interactive features
    </script>
</body>
</html>