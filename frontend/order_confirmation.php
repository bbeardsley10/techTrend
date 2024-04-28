<?php
// Start the session and connect to the database
session_start();
include 'db_connection.php';

// Ensure the user is logged in
if (!isset($_SESSION['Customer_Username'])) {
    header("Location: login.php");
    exit(); // Redirect to login page if no user is logged in
}

// Get the logged-in customer's username
$customerUsername = $_SESSION['Customer_Username'];

// Fetch customer information based on the session's username
$query = "SELECT * FROM Customer WHERE Customer_Username = '$customerUsername'";
$result = mysqli_query($conn, $query);

if ($result) {
    $customer = mysqli_fetch_assoc($result);

    // Fetch customer details
    $firstName = $customer['First_Name'];
    $lastName = $customer['Last_Name'];
    $city = $customer['City'];
    $state = $customer['State'];
    $streetAddress = $customer['Street_Address'];
    $zipCode = $customer['Zip_Code'];
    $email = $customer['Customer_Email'];
    
    // Fetch payment information if available
    if (isset($customer['Payment_ID'])) {
        $paymentId = $customer['Payment_ID'];
        $paymentQuery = "SELECT * FROM payment WHERE Payment_ID = '$paymentId'";
        $paymentResult = mysqli_query($conn, $paymentQuery);

        if ($paymentResult) {
            $payment = mysqli_fetch_assoc($paymentResult);

            // Payment details
            $paymentAmount = $payment['Payment_Amount'];
            $paymentType = $payment['Payment_Type'];
            $paymentDate = $payment['Payment_Date'];
            $cardNumber = $payment['Card_Number'] ?? '';
            $expiryMonth = $payment['Expiry_Month'] ?? '';
            $expiryYear = $payment['Expiry_Year'] ?? '';
            $expiryDate = ($expiryMonth && $expiryYear) ? "$expiryMonth/$expiryYear" : '';
            $giftCardNumber = $payment['Card_Number'] ?? '';
        }
    }
} else {
    die("Error fetching customer information: " . mysqli_error($conn));
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Confirmation</title>
    <link rel="icon" href="img/techTrendIcon.png" type="image/x-icon">
</head>
<body>
    <h1>Order Confirmation</h1>
    <p>Thank you for your order! Below are the details of your order:</p>

    <h2>Customer Information</h2>
    <p>First Name: <?php echo htmlspecialchars($firstName); ?></p>
    <p>Last Name: <?php echo htmlspecialchars($lastName); ?></p>
    <p>City: <?php echo htmlspecialchars($city); ?></p>
    <p>State: <?php echo htmlspecialchars($state); ?></p>
    <p>Street Address: <?php echo htmlspecialchars($streetAddress); ?></p>
    <p>Zip Code: <?php echo htmlspecialchars($zipCode); ?></p>
    <p>Email: <?php echo htmlspecialchars($email); ?></p>

    <h2>Payment Information</h2>
    <?php if (isset($paymentAmount)): ?>
        <p>Payment Amount: $<?php echo htmlspecialchars($paymentAmount); ?></p>
        <p>Payment Type: <?php echo htmlspecialchars($paymentType); ?></p>
        <p>Payment Date: <?php echo htmlspecialchars($paymentDate); ?></p>

        <?php if ($paymentType === "Credit/Debit Card"): ?>
            <p>Card Number: **** **** **** <?php echo htmlspecialchars(substr($cardNumber, -4)); ?></p>
            <p>Expiry Date: <?php echo htmlspecialchars($expiryDate); ?></p>
        <?php elseif ($paymentType === "Gift Card"): ?>
            <p>Gift Card Number: <?php echo htmlspecialchars($giftCardNumber); ?></p>
        <?php endif; ?>
    <?php else: ?>
        <p>No payment information available.</p>
    <?php endif; ?>

    <p>Thank you for shopping with us!</p>
    <p><a href="index.php">Main Menu</a></p>
</body>
</html>
