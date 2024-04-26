<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout</title>
</head>
<body>
    <h1>Checkout</h1>
    <form action="../backend/process_checkout.php" method="post">
        <label for="first_name">First Name:</label>
        <input type="text" id="first_name" name="first_name" required><br><br>
        <label for="last_name">Last Name:</label>
        <input type="text" id="last_name" name="last_name" required><br><br>
        <label for="city">City:</label>
        <input type="text" id="city" name="city" required><br><br>
        <label for="state">State:</label>
        <input type="text" id="state" name="state" required><br><br>
        <label for="street_address">Street Address:</label>
        <input type="text" id="street_address" name="street_address" required><br><br>
        <label for="zip_code">Zip Code:</label>
        <input type="text" id="zip_code" name="zip_code" required><br><br>
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required><br><br>
        <input type="submit" value="Proceed to Payment">
    </form>
</body>
</html>
