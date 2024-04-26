<?php
//Include the database connection file
include 'db_connection.php';
// Start session
session_start();

if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}

// Get the submitted username and password
$username = trim($_POST['Customer_Username'] ?? '');
$password = $_POST['Customer_Password'] ?? '';

// Log the received username
error_log("Received username: " . $username);

// Check if the username and password are not empty
if (empty($username) || empty($password)) {
    header('Location: signin.html?error=MissingCredentials');
    exit();
}

// Prepared statement to avoid SQL injection
$query = "SELECT Customer_Password FROM customer WHERE Customer_Username = ?";
$stmt = $conn->prepare($query);

// Bind the parameter
$stmt->bind_param("s", $username);

// Execute the statement and check for errors
if ($stmt->execute() === false) {
    error_log("SQL error: " . $stmt->error);
    die("SQL error occurred.");
}

// Get the result and ensure there's at least one record
$result = $stmt->get_result();
if ($result->num_rows === 0) {
    error_log("User not found for username: " . $username);
    header('Location: signin.html?error=UserNotFound');
    exit();
}

// Fetch the row and get the hashed password
$row = $result->fetch_assoc();
$hashed_password = $row['Customer_Password'];

// Log the hashed password for debugging
error_log("Stored hashed password for " . $username . ": " . $hashed_password);

// Check if the password matches
if (password_verify($password, $hashed_password)) {
    $_SESSION['Customer_Username'] = $username;
    header('Location: index2.php'); // Redirect to the desired page
    exit();
} else {
    error_log("Password mismatch for username: " . $username);
    header('Location: signin.html?error=IncorrectPassword');
    exit();
}

// Close the statement and connection
$stmt->close();
mysqli_close($conn);
?>
