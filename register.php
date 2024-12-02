<?php
include("php/config.php");

if (isset($_POST['submit'])) {
    $username = mysqli_real_escape_string($con, $_POST['username']);
    $email = mysqli_real_escape_string($con, $_POST['email']);
    $age = mysqli_real_escape_string($con, $_POST['age']);
    $password = mysqli_real_escape_string($con, $_POST['password']);

    // Hash the password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Check if the email already exists
    $verify_query = mysqli_query($con, "SELECT Email FROM users WHERE Email='$email'");
    if (mysqli_num_rows($verify_query) != 0) {
        echo "<div class='message'>
                  <p>This email is already registered. Try another one!</p>
              </div>";
        echo "<a href='register.php'><button class='btn'>Go Back</button></a>";
    } else {
        // Insert into database
        $insert_query = "INSERT INTO users (Username, Email, Age, Password) VALUES ('$username', '$email', '$age', '$hashed_password')";
        if (mysqli_query($con, $insert_query)) {
            echo "<div class='message'>
                      <p>Registration successful!</p>
                  </div>";
            echo "<a href='login.php'><button class='btn'>Login Now</button></a>";
        } else {
            echo "<div class='message'>
                      <p>Registration failed. Please try again later.</p>
                  </div>";
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Register</title>
    <link rel="stylesheet" href="style/style.css">
</head>
<body>
    <div class="container">
        <div class="box form-box">
            <header>Register</header>
            <form method="POST">
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
