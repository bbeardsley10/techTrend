<?php
    session start();
    header('Location:admin-registration.html');    
$con= mysqli_connect('localhost', 'root');
if($con){
    echo "Connected Successfully"
}
else{
    echo "No Connection";
}
mysqli_select_db($con, 'techTrend');
$firstname = $_POST['first-name'];
$lastname = $_POST['last-name'];
$adminID = $_POST['admin-id'];
$username = $_POST['username'];
$password = $_POST['password'];


$quer = "Select * from Admin where username - '$username' && password - '$password'";
$result = mysqli_query($con, $quer);
$num = mysqli_num_rows($result);
if($num == 1){
    echo "Duplicate Detected";
}
else{
    $querr= "insert into Admin(first-name, last-name, admin-id, username, password) values('$firstname','$lastname','$adminID', '$username', '$password'";
    mysqli_query($con, $querr);
}

?>