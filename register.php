<?php
session_start();
include("php/config.php");

// Define encryption key and method
define('ENCRYPTION_KEY', 'your-secret-key-here'); // Replace with a secure key
define('ENCRYPTION_METHOD', 'AES-256-CBC');

// Encryption and decryption functions
function encrypt_data($data)
{
    $key = hash('sha256', ENCRYPTION_KEY);
    $iv = substr(hash('sha256', 'iv-secret'), 0, 16);
    return base64_encode(openssl_encrypt($data, ENCRYPTION_METHOD, $key, 0, $iv));
}

function decrypt_data($data)
{
    $key = hash('sha256', ENCRYPTION_KEY);
    $iv = substr(hash('sha256', 'iv-secret'), 0, 16);
    return openssl_decrypt(base64_decode($data), ENCRYPTION_METHOD, $key, 0, $iv);
}

// Check if form is submitted
if (isset($_POST['submit'])) {
    // Sanitize and validate inputs
    $username = trim(mysqli_real_escape_string($con, $_POST['username']));
    $email = trim(mysqli_real_escape_string($con, $_POST['email']));
    $age = isset($_POST['age']) && is_numeric($_POST['age']) && $_POST['age'] > 0 ? (int) $_POST['age'] : NULL;
    $password = mysqli_real_escape_string($con, $_POST['password']);

    if ($age === NULL) {
        echo "<div class='message'><p>Invalid age provided. Please enter a valid number greater than zero.</p></div>";
        exit;
    }

    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "<div class='message'><p>Invalid email format. Please try again.</p></div>";
        exit;
    }

    // Check if the email already exists
    $verify_query = $con->prepare("SELECT Email FROM users WHERE Email = ?");
    $verify_query->bind_param("s", $email);
    $verify_query->execute();
    $verify_query->store_result();

    if ($verify_query->num_rows > 0) {
        echo "<div class='message'><p>This email is already registered. Try another one!</p></div>";
        $verify_query->close();
        exit;
    }
    $verify_query->close();

    // Encrypt sensitive data (if required)
    $encrypted_age = encrypt_data($age);

    // Hash the password securely
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Insert into database
    $insert_query = $con->prepare("INSERT INTO users (Username, Email, Age, Password, is_admin) VALUES (?, ?, ?, ?, 0)");
    $insert_query->bind_param("ssss", $username, $email, $encrypted_age, $hashed_password);

    try {
        if ($insert_query->execute()) {
            $user_id = $con->insert_id; // Get the ID of the newly inserted user

            // Log registration activity
            $ip_address = $_SERVER['REMOTE_ADDR'];
            $log_file = 'logs/registration.log';
            file_put_contents($log_file, "Registration attempt by $username from IP: $ip_address at " . date('Y-m-d H:i:s') . "\n", FILE_APPEND);

            // Insert audit log
            $log_query = $con->prepare("INSERT INTO audit_log (action, user_email, ip_address) VALUES (?, ?, ?)");
            $action = "User Registration";
            $log_query->bind_param("sss", $action, $email, $ip_address);
            $log_query->execute();
            $log_query->close();

            // Redirect to enroll_finger.php with user ID
            header("Location: enroll_finger.php?user_id=$user_id");
            exit;
        }
    } catch (mysqli_sql_exception $e) {
        echo "<div class='message'><p>Registration failed due to an unexpected error: " . $e->getMessage() . "</p></div>";
    }

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
                    <input type="number" name="age" required min="1">
                </div>
                <div class="field input">
                    <label>Password</label>
                    <input type="password" name="password" required>
                </div>
                <div class="field">
                    <input type="submit" name="submit" value="Submit and Enroll Finger" class="btn">
                </div>
                <div class="links">
                    Already have an account? <a href="login.php">Login</a>
                </div>
            </form>
        </div>
    </div>
</body>

</html>
