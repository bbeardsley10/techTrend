<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Confirmation</title>
</head>
<body>
    <h1>Order Confirmation</h1>
    <p>Thank you for your order! Below are the details of your order:</p>

    <h2>Customer Information</h2>
    <p>First Name: <?php echo $firstName; ?></p>
    <p>Last Name: <?php echo $lastName; ?></p>
    <p>City: <?php echo $city; ?></p>
    <p>State: <?php echo $state; ?></p>
    <p>Street Address: <?php echo $streetAddress; ?></p>
    <p>Zip Code: <?php echo $zipCode; ?></p>
    <p>Email: <?php echo $email; ?></p>

    <h2>Payment Information</h2>
    <p>Payment Amount: $<?php echo $paymentAmount; ?></p>
    <p>Payment Type: <?php echo $paymentType; ?></p>
    <p>Payment Date: <?php echo $paymentDate; ?></p>
    <?php if ($paymentType === "Credit/Debit Card"): ?>
        <p>Card Number: <?php echo $cardNumber; ?></p>
        <p>Expiry Date: <?php echo $expiryDate; ?></p>
    <?php elseif ($paymentType === "Gift Card"): ?>
        <p>Gift Card Number: <?php echo $giftCardNumber; ?></p>
    <?php endif; ?>

    <!-- Add additional order details as needed -->

    <p>Thank you for shopping with us!</p>
</body>
</html>
