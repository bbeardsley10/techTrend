<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>techTrend-Admin Registration</title>
    <link rel="icon" href="img/techTrendIcon.png" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-gH2yIJqKdNHPEq0n4Mqa/HGKIhSkIHeL5AyhkYV8i59U5AR6csBvApHHNl/vI1Bx" crossorigin="anonymous">
    <link rel="stylesheet" href="login-style.css">
</head>
<body>
    <form action="signup.php" method="post" class="container">
        <div class="box">
            <div class="row">
                <div class="col-sm-5 col-xs-1 box1">
                    <div class="inline-text">
                        <h1>Admin Registration</h1>
                    </div>
                    <div class="user-id button2">
                        <a href="index.php" class="btn btn-primary">Return to Main Menu</a>
                    </div>
                    <div class="user-id button3">
                        <a href="admin-portal.html" class="btn btn-primary">Return to Admin Portal</a>
                    </div>
                </div>
                <div class="col-sm-5 col-xs-1 box2">
                    <div class="user-id user-data">
                        <input type="text" name="Admin_Username" id="First_Name" required>
                        <label>Enter a Username</label>
                    </div>
                    <div class="user-id user-data">
                        <input type="text" name="Admin_Password" id="First_Name" required>
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
                        <input type="text" name="Admin_Role" id="Admin_Role" required>
                        <label>Enter Your Role</label>
                    </div>
                    <div class="user-id button1">
                        <input type ="submit" name ="submitbutton" id="" value = "Create New Account"> 
                    </div>

                    <?php 

                    session_start();
                    if (isset($_SESSION['registration_error'])){
                        echo "<div class='user-id alert alert-danger'>" . $_SESSION['registration_error'] . "</div>";
                        unset($_SESSION['registration_error']);
                    }
                    ?>
                </div>
            </div>
        </div>
    </form>
</body>
</html>