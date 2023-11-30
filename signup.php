<?php
require_once('db.php'); // Include your database connection file

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Validate and process the sign-up form data
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Check if the username is already taken
    $checkUsernameQuery = "SELECT * FROM users WHERE username = '$username'";
    $checkUsernameResult = mysqli_query($conn, $checkUsernameQuery);

    if ($checkUsernameResult && mysqli_num_rows($checkUsernameResult) > 0) {
        $error = "Username already taken. Choose a different one.";
    } else {
        // Insert the user into the database
        $insertQuery = "INSERT INTO users (username, password) VALUES ('$username', '$password')";
        $insertResult = mysqli_query($conn, $insertQuery);

        if ($insertResult) {
            // Redirect to login page after successful sign-up
            header("Location: login.php");
            exit();
        } else {
            $error = "Error creating user. Please try again.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>
    <!-- Add your CSS styles or link to Bootstrap here -->
    <link rel="stylesheet" href="styles.css">
</head>

<body>
    <div class="container">
        <h2>Sign Up</h2>
        <?php
        // Display error message if there is any
        if (isset($error)) {
            echo "<div class='alert alert-danger'>$error</div>";
        }
        ?>
        <form action="signup.php" method="post">
            <div class="form-group">
                <label for="username">Username:</label>
                <input type="text" class="form-control" id="username" name="username" required>
            </div>
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <button type="submit" class="btn btn-primary">Sign Up</button>
        </form>
        <p>Already have an account? <a href="login.php">Login here</a></p>
    </div>
</body>

</html>
