<?php
// Set session cookie parameters before starting the session
session_set_cookie_params([
    'lifetime' => 0,
    'path' => '/',
    'domain' => '',  // Set the domain if needed (e.g., '.yourdomain.com')
    'secure' => true,  // Ensure cookies are sent over HTTPS
    'httponly' => true,  // Make the cookie inaccessible via JavaScript
    'samesite' => 'Strict'  // Prevent cross-site request forgery (CSRF) attacks
]);

// Start the session after setting the cookie params
session_start();
include("php/config.php");

// Function to limit login attempts
function check_login_attempts($email, $con)
{
    $stmt = mysqli_prepare($con, "SELECT attempts, last_attempt FROM login_attempts WHERE email = ?");
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($row = mysqli_fetch_assoc($result)) {
        $attempts = $row['attempts'];
        $last_attempt = strtotime($row['last_attempt']);
        $current_time = time();

        // Block login attempts for 15 minutes after 3 failed attempts
        if ($attempts >= 3 && ($current_time - $last_attempt) < 900) {
            return false;
        }
    }
    return true;
}

if (isset($_POST['submit'])) {
    // Sanitize and validate input
    $email = mysqli_real_escape_string($con, $_POST['email']);
    $password = mysqli_real_escape_string($con, $_POST['password']);

    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "<div class='message'><p>Invalid email format. Please try again.</p></div>";
        exit;
    }

    // Check login attempts before allowing further login attempts
    if (!check_login_attempts($email, $con)) {
        echo "<div class='message'><p>Too many failed login attempts. Please try again later.</p></div>";
        exit;
    }

    // Admin login (special case: bypass email validation)
    if ($email == "admin" && $password == "admin") {
        $_SESSION['valid'] = "admin";
        $_SESSION['username'] = "Admin";
        $_SESSION['id'] = 1;  // Admin user ID (adjust based on your DB)
        $_SESSION['is_admin'] = 1;  // Admin role
        header("Location: admin/index.php");  // Redirect to the admin panel
        exit();
    }

    // User login using prepared statements
    $stmt = mysqli_prepare($con, "SELECT * FROM users WHERE Email = ?");
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    // If user exists in the database
    if ($row = mysqli_fetch_assoc($result)) {
        // Verify password
        if (password_verify($password, $row['Password'])) {
            // Regenerate session ID for security
            session_regenerate_id(true);

            // Set session variables for user
            $_SESSION['valid'] = $row['Email'];
            $_SESSION['username'] = $row['Username'];
            $_SESSION['age'] = $row['Age'];
            $_SESSION['id'] = $row['Id'];
            $_SESSION['is_admin'] = $row['is_admin'];  // For differentiating admin and user

            // Clear failed login attempts
            $stmt = mysqli_prepare($con, "UPDATE login_attempts SET attempts = 0 WHERE email = ?");
            mysqli_stmt_bind_param($stmt, "s", $email);
            mysqli_stmt_execute($stmt);

            header("Location: index.php");  // Redirect to user homepage
            exit();
        } else {
            // Increment failed login attempts
            $stmt = mysqli_prepare($con, "INSERT INTO login_attempts (email, attempts, last_attempt) VALUES (?, 1, NOW()) ON DUPLICATE KEY UPDATE attempts = attempts + 1, last_attempt = NOW()");
            mysqli_stmt_bind_param($stmt, "s", $email);
            mysqli_stmt_execute($stmt);

            echo "<div class='message'><p>Incorrect password. Please try again.</p></div>";
        }
    } else {
        echo "<div class='message'><p>Email not found. Please try again or register.</p></div>";
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
