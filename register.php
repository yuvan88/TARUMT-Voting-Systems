<?php
include("php/config.php");

if (isset($_POST['submit'])) {
    // Sanitize and validate inputs
    $username = trim(mysqli_real_escape_string($con, $_POST['username']));
    $email = trim(mysqli_real_escape_string($con, $_POST['email']));
    $age = (int) $_POST['age']; // Cast age to an integer for safety
    $password = mysqli_real_escape_string($con, $_POST['password']);

    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "<div class='message'>
                  <p>Invalid email format. Please try again.</p>
              </div>";
        exit;
    }

    // Validate age (make sure it's a positive integer)
    if ($age <= 0) {
        echo "<div class='message'>
                  <p>Age must be a positive number. Please try again.</p>
              </div>";
        exit;
    }

    // Check if the email exists
    $email_check_query = $con->prepare("SELECT Email FROM users WHERE Email = ?");
    $email_check_query->bind_param("s", $email);
    $email_check_query->execute();
    $email_check_query->store_result();

    if ($email_check_query->num_rows > 0) {
        echo "<div class='message'>
                  <p>This email is already registered. Try another one!</p>
              </div>";
        echo "<a href='register.php'></a>";
        $email_check_query->close();
        exit;
    }
    $email_check_query->close();

    // Hash the password securely
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Insert new user into the database
    $insert_query = $con->prepare("INSERT INTO users (Username, Email, Age, Password) VALUES (?, ?, ?, ?)");
    $insert_query->bind_param("ssis", $username, $email, $age, $hashed_password);

    try {
        if ($insert_query->execute()) {
            // Redirect to login page after successful registration
            header("Location: login.php");
            exit;
        }
    } catch (mysqli_sql_exception $e) {
        echo "<div class='message'>
                  <p>Registration failed due to an unexpected error: " . $e->getMessage() . "</p>
              </div>";
    }

    // Close the prepared statement
    $insert_query->close();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="style/style.css">
</head>

<body>
    <div class="container">
        <div class="box form-box">
            <header>Register</header>
            <form method="POST" action="">
                <div class="field input">
                    <label>Username</label>
                    <input type="text" name="username" required>
                </div>
                <div class="field input">
                    <label>Email</label>
                    <input type="email" name="email" required>
                </div>
                <div class="field input">
                    <label>Age</label>
                    <input type="number" name="age" required>
                </div>
                <div class="field input">
                    <label>Password</label>
                    <input type="password" name="password" required>
                </div>
                <div class="field">
                    <input type="submit" name="submit" value="Register" class="btn">
                </div>
                <div class="links">
                    Already have an account? <a href="login.php">Login</a>
                </div>
            </form>
        </div>
    </div>
</body>

</html>