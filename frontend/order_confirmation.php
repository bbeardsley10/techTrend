<?php
// Start the session
session_start();
// Connect to the database
include 'db_connection.php';

if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

// Ensure a user is logged in
if (!isset($_SESSION['Customer_Username'])) {
    header("Location: login.php");
    exit(); // Redirect to login if no user is logged in
}

// Get the logged-in username
$customerUsername = $_SESSION['Customer_Username'];

// Fetch customer information based on the session's username
$customerQuery = "SELECT * FROM customer WHERE Customer_Username = ?";
$stmt = $conn->prepare($customerQuery);
$stmt->bind_param("s", $customerUsername);
$stmt->execute();
$result = $stmt->get_result();

if ($result && $result->num_rows > 0) {
    $customer = mysqli_fetch_assoc($result);

    // Get the customer details
    $firstName = htmlspecialchars($customer['First_Name']);
    $lastName = htmlspecialchars($customer['Last_Name']);
    $city = htmlspecialchars($customer['City']);
    $state = htmlspecialchars($customer['State']);
    $streetAddress = htmlspecialchars($customer['Street_Address']);
    $zipCode = htmlspecialchars($customer['Zip_Code']);
    $email = htmlspecialchars($customer['Customer_Email']);

    // Get the payment information
    if (isset($_SESSION['Payment_ID'])) {
        $paymentId = $_SESSION['Payment_ID'];

        // Use a prepared statement to fetch payment information
        $paymentQuery = "SELECT * FROM payment WHERE Payment_ID = ?";
        $stmt = $conn->prepare($paymentQuery);
        $stmt->bind_param("i", $paymentId);
        $stmt->execute();
        $paymentResult = $stmt->get_result();

        if ($paymentResult && $paymentResult->num_rows > 0) {
            $payment = mysqli_fetch_assoc($paymentResult);

            // Get payment details
            $paymentAmount = floatval($payment['Payment_Amount']); // Ensure valid float
            $paymentType = htmlspecialchars($payment['Payment_Type']);
            $paymentDate = htmlspecialchars($payment['Payment_Date']);
            $cardNumber = htmlspecialchars($payment['Card_Number'] ?? '');
            $expiryMonth = htmlspecialchars($payment['Expiry_Month'] ?? '');
            $expiryYear = htmlspecialchars($payment['Expiry_Year'] ?? '');
            $expiryDate = ($expiryMonth && $expiryYear) ? "$expiryMonth/$expiryYear" : '';
            $giftCardNumber = htmlspecialchars($payment['Card_Number'] ?? '');
        } else {
            echo "Error fetching payment information.";
            exit();
        }
    } else {
        echo "No Payment_ID found for this customer.";
        exit();
    }
} else {
    echo "Error fetching customer information.";
    exit();
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
    <p><a href="logout.php">Return to Main Menu</a></p>
</body>
</html>