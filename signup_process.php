<!-- signup_process.php -->
<?php
session_start();
require_once('db.php'); // Connect to the database

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = password_hash($_POST["password"], PASSWORD_BCRYPT); // Hash the password for security

    $sql = "INSERT INTO `users` (`username`, `password`) VALUES ('$username', '$password')";
    $result = mysqli_query($conn, $sql);

    if ($result) {
        $_SESSION["username"] = $username;
        header("Location: index.php"); // Redirect to the main page after successful sign-up
        exit();
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>
