<?php 
session_start();
?>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>techTrend Customer Registration</title>
    <link rel="icon" href="img/techTrendIcon.png" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-gH2yIJqKdNHPEq0n4Mqa/HGKIhSkIHeL5AyhkYV8i59U5AR6csBvApHHNl/vI1Bx" crossorigin="anonymous">
    <link rel="stylesheet" href="customer-signup-style.css">
</head>
<body>
    <form action="customer-signup.php" method="post" class="container">
        <div class="box">
            <div class="row">
                <div class="col-sm-5 col-xs-1 box1">
                    <div class="inline-text">
                        <h1>Customer Account</h1>
                        <h1>Creation</h1>
                    </div>
                    <div class="user-id button2">
                        <a href="index.php" class="btn btn-primary">Return to Main Menu</a>
                    </div>
                    <div class="user-id button3">
                        <a href="customer-portal.html" class="btn btn-primary">Return to Customer Portal</a>
                    </div>
                    
                </div>
                <div class="col-sm-5 col-xs-1 box2">
                    <div class="user-id user-data">
                        <input type="text" name="Customer_Username" id="Customer_Username" required>
                        <label>Enter a Username</label>
                    </div>
                    
                    <div class="user-id user-data">
                        <input type="text" name="Customer_Password" id="Customer_Password" required>
                        <label>Enter a Password</label>
                    </div>
                    <div class="user-id user-data">
                        <input type="text" name="First_Name" id="First_Name" required>
                        <label>Enter First Name</label>
                    </div>
                    <div class="user-id user-data">
                        <input type="text" name="Last_Name" id="Last_Name" required>
                        <label>Enter Last Name</label>
                    </div>   
                    <div class="user-id user-data">
                        <input type="text" name="Street_Address" id="Street_Address" required>
                        <label>Enter Street Address</label>
                    </div>
                    <div class="user-id user-data">
                        <input type="text" name="City" id="City" required>
                        <label>Enter City</label>
                    </div>
                    <div class="user-id user-data">
                        <input type="text" name="State" id="State" required>
                        <label>Enter State</label>
                    </div>
                    <div class="user-id user-data">
                        <input type="text" name="Zip_Code" id="Zip_Code" required>
                        <label>Enter Zip Code</label>
                    </div>
                    <div class="user-id user-data">
                        <input type="text" name="Customer_Email" id="Customer_Email" required>
                        <label>Enter Email</label>
                    </div>
                    
                    <div class="user-id button">
                            <input type ="submit" name ="submitbutton" id="" value = "Create New Account"> 
                    </div>
                    <div class="error-messages">
                        <?php
                        if (isset($_SESSION['registration_error'])) {
                            echo "<div class='user-id alert alert-danger1'>" . $_SESSION['registration_error'] . "</div>";
                            unset($_SESSION['registration_error']);
                        } elseif (isset($_SESSION['email_error'])) {
                            echo "<div class='user-id alert alert-danger2'>" . $_SESSION['email_error'] . "</div>";
                            unset($_SESSION['email_error']);
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </form>
</body>
</html>