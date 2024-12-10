<?php
session_start();
include("php/config.php");

if (isset($_POST['submit'])) {
    $email = mysqli_real_escape_string($con, $_POST['email']);
    $password = mysqli_real_escape_string($con, $_POST['password']);

    // Admin login
    if ($email == "admin" && $password == "admin") {
        $_SESSION['valid'] = "admin";
        $_SESSION['username'] = "Admin";
        $_SESSION['id'] = 1;  // Admin user ID (adjust based on your DB)
        $_SESSION['is_admin'] = 1;  // Admin role
        header("Location: admin/index.php");
        exit();
    }

    // User login (using prepared statements to prevent SQL injection)
    $stmt = mysqli_prepare($con, "SELECT * FROM users WHERE Email = ?");
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($row = mysqli_fetch_assoc($result)) {
        // Verify password
        if (password_verify($password, $row['Password'])) {
            $_SESSION['valid'] = $row['Email'];
            $_SESSION['username'] = $row['Username'];
            $_SESSION['age'] = $row['Age'];
            $_SESSION['id'] = $row['Id'];
            header("Location: index.php");  // Redirect to user homepage
            exit();
        }
    }

    // If login fails
    echo "<div class='message'>
              <p>Invalid login credentials. Please try again.</p>
          </div>";
    echo "<a href='login.php'></a>";
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
                    <input type="text" name="email" required>
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