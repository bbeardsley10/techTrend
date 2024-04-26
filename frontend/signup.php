<?php
//Include the database connection file
include 'db_connection.php';
// Start session
session_start();

if (!$conn) {
    echo "No Connection";
    exit; // Exit script if connection fails
}

$username = $_POST['Admin_Username'];
$password = $_POST['Admin_Password'];
$firstname = $_POST['First_Name'];
$lastname = $_POST['Last_Name'];
$adminRole = $_POST['Admin_Role'];

$quer = "SELECT * FROM admin WHERE `Admin_Username` = '$username'";
$result = mysqli_query($conn, $quer);
$num = mysqli_num_rows($result);

if ($num > 0) {
    // Set session variable with error message
    $_SESSION['registration_error'] = "The username is already taken!";
    // Redirect back to the registration page
    header("Location: admin-registration.php");
    exit;
} else {
    $query = "INSERT INTO Admin (First_Name, Last_Name, Admin_Username, Admin_Password, Admin_Role) VALUES (?,?,?,?,?)";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "sssss", $firstname, $lastname, $username, $password, $adminRole);
    mysqli_stmt_execute($stmt);
    mysqli_close($conn);
    
    // Account created successfully
    $_SESSION['account_created'] = true;
    header("Location: admin-portal.html");
    exit;
}
?>
