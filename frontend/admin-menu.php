
<?php 
    session_start();

    $con = mysqli_connect('localhost', 'root', '', 'techtrend');

    if (isset($_SESSION['Admin_Username'])) {
        $adminUsername = $_SESSION['Admin_Username'];
    
        // Prepare and execute the query to fetch the admin role
        $query = "SELECT Admin_Role FROM Admin WHERE Admin_Username = ?";
        $stmt = $con->prepare($query);
        
        // Bind the parameter
        $stmt->bind_param("s", $adminUsername);
        
        // Execute the query
        $stmt->execute();
        
        // Get the result
        $stmt->store_result();
        
        if ($stmt->num_rows > 0) {
            // Fetch the Admin_Role value
            $stmt->bind_result($adminRole);
            $stmt->fetch();
            
            // Store the role in the session
            $_SESSION['Admin_Role'] = $adminRole;
        } else {
            echo "Role not found for user: " . htmlspecialchars($adminUsername);
        }
    
        // Close the statement
        $stmt->close();
    } else {
        // Redirect to the admin sign-in page if the user is not logged in
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
    <div class="user-id user-data">
        <p>Welcome <?php echo $_SESSION['Admin_Username']; ?> (<?php echo $_SESSION['Admin_Role']; ?>)!</p>
    </div>
    
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
    <div class="button logout">
        <a href="admin-portal.html" class="btn btn-primary">Logout</a>
    </div>
    <!-- Add your JavaScript code here -->
    <script>
        // Add JavaScript for any interactive features
    </script>
</body>
</html>
