<?php
session_start();
include 'db_connection.php';


if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $totalPrice = $_POST['totalPrice'];
    
} else {
    exit();
}

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment</title>
    <link rel="icon" href="img/techTrendIcon.png" type="image/x-icon">
    <style>
        .cardFields, .giftCardFields {
            display: none;
        }
    </style>
</head>
<body>
    <h1>Payment</h1>
    <form action="process_payment.php" method="post" onsubmit="return validateForm()">
        <!-- Payment Amount -->
        <input type="hidden" name="totalPrice" value="<?php echo htmlspecialchars($totalPrice); ?>">

        <!-- Payment Type  -->
        <label>Payment Type:</label><br>
        <input type="radio" id="creditDebit" name="paymentType" value="Credit/Debit Card" required onchange="showFields()">
        <label for="creditDebit">Credit/Debit Card</label><br>
        <input type="radio" id="giftCard" name="paymentType" value="Gift Card" onchange="showFields()">
        <label for="giftCard">Gift Card</label><br><br>

        <!-- Card Fields -->
        <div class="cardFields">
            <label for="cardNumber">Card Number:</label><br>
            <input id="cardNumber" type="tel" inputmode="numeric" pattern="[0-9\s]{13,19}" maxlength="19" name="cardNumber" placeholder="xxxx xxxx xxxx xxxx" required><br><br>

            <!-- Expiry Month Field -->
            <label for="expiryMonth">Expiration Month:</label><br>
            <input type="number" id="expiryMonth" name="expiryMonth" placeholder="MM" min="1" max="12" required><br><br>

            <!-- Expiry Year Field -->
            <label for="expiryYear">Expiration Year:</label><br>
            <input type="number" id="expiryYear" name="expiryYear" placeholder="YY" min="<?php echo date('y'); ?>" max="99" required><br><br>

            <!-- CVV/CVC Field -->
            <label for="cvv">CVV/CVC:</label><br>
            <input type="text" id="cvv" name="cvv" maxlength="4" placeholder="" required><br><br>
        </div>

        <!-- Gift Card Fields -->
        <div class="giftCardFields">
            <label for="giftCardNumber">Gift Card Number:</label><br>
            <input type="text" id="giftCardNumber" name="giftCardNumber" placeholder="Enter gift card number"><br><br>

        </div>

        <!-- Payment Date (Automatically set to current date) -->
        <input type="hidden" name="paymentDate" value="<?php echo date('Y-m-d'); ?>">

        <input type="submit" value="Submit Payment">
    </form>

    <!-- script for making fields required -->
    <script>
    function showFields() {
        var creditDebit = document.getElementById("creditDebit");
        var giftCard = document.getElementById("giftCard");
        var cardFields = document.querySelector('.cardFields');
        var giftCardFields = document.querySelector('.giftCardFields');

        if (creditDebit.checked) {
            cardFields.style.display = 'block';
            giftCardFields.style.display = 'none';
            document.getElementById("cardNumber").setAttribute("required", "required");
            document.getElementById("expiryMonth").setAttribute("required", "required");
            document.getElementById("expiryYear").setAttribute("required", "required");
            document.getElementById("cvv").setAttribute("required", "required");
        } else if (giftCard.checked) {
            cardFields.style.display = 'none';
            giftCardFields.style.display = 'block';
            document.getElementById("cardNumber").removeAttribute("required");
            document.getElementById("expiryMonth").removeAttribute("required");
            document.getElementById("expiryYear").removeAttribute("required");
            document.getElementById("cvv").removeAttribute("required");
        }
    }

    function validateForm() {
        var creditDebit = document.getElementById("creditDebit");
        var cardFields = document.querySelector('.cardFields');
        var cardNumber = document.getElementById("cardNumber").value;
        var expiryMonth = document.getElementById("expiryMonth").value;
        var expiryYear = document.getElementById("expiryYear").value;
        var cvv = document.getElementById("cvv").value;

        if (creditDebit.checked) {
            if (!cardNumber || !expiryMonth || !expiryYear || !cvv) {
                alert("Please fill out all credit/debit card fields.");
                return false;
            }
        }
        return true;
    }
    </script>
</body>
</html>