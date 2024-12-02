<?php
session_start();
include("php/config.php");

if (isset($_POST['submit'])) {
    $email = mysqli_real_escape_string($con, $_POST['email']);
    $password = mysqli_real_escape_string($con, $_POST['password']);

    // Fetch user details
    $result = mysqli_query($con, "SELECT * FROM users WHERE Email='$email'");
    $row = mysqli_fetch_assoc($result);

    if ($row && password_verify($password, $row['Password'])) {
        // Start user session
        $_SESSION['valid'] = $row['Email'];
        $_SESSION['username'] = $row['Username'];
        $_SESSION['age'] = $row['Age'];
        $_SESSION['id'] = $row['Id'];
        header("Location: index.php");
        exit();
    } else {
        echo "<div class='message'>
                  <p>Wrong Username or Password</p>
              </div>";
        echo "<a href='login.php'><button class='btn'>Go Back</button></a>";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <link rel="stylesheet" href="style/style.css">
</head>
<body>
    <div class="container">
        <div class="box form-box">
            <header>Login</header>
            <form method="POST">
                <div class="field input">
                    <label>Email</label>
                    <input type="email" name="email" required>
                </div>
                <div class="field input">
                    <label>Password</label>
                    <input type="password" name="password" required>
                </div>
                <div class="field">
                    <input type="submit" name="submit" value="Login" class="btn">
                </div>
                <div class="links">
                    <a href="forgot-password.php">Forgot Password?</a><br>
                    Don't have an account? <a href="register.php">Register</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
