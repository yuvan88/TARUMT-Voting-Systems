<?php
session_start();
include("php/config.php");

if (isset($_GET['token'])) {
    $token = $_GET['token'];

    // Prepared statement to prevent SQL injection
    $stmt = mysqli_prepare($con, "SELECT * FROM users WHERE reset_token = ?");
    mysqli_stmt_bind_param($stmt, 's', $token);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $user = mysqli_fetch_assoc($result);

    if ($user) {
        // Check if the token has expired
        $expiry_time = $user['reset_token_expiry'];
        $current_time = date("Y-m-d H:i:s");

        if (strtotime($expiry_time) < strtotime($current_time)) {
            echo "<div class='container'><div class='box'>The reset token has expired. <a href='forgot-password.php'>Request a new one</a></div></div>";
        } else {
            // Token is valid, show the password reset form
            if (isset($_POST['reset'])) {
                $password = mysqli_real_escape_string($con, $_POST['password']);

                // Basic password validation (at least 8 characters)
                if (strlen($password) < 8) {
                    echo "<div class='container'><div class='box'>Password must be at least 8 characters long.</div></div>";
                } else {
                    // Check if the password contains at least one uppercase letter, one number, and one special character
                    if (!preg_match("/[A-Z]/", $password)) {
                        echo "<div class='container'><div class='box'>Password must contain at least one uppercase letter.</div></div>";
                    } elseif (!preg_match("/[0-9]/", $password)) {
                        echo "<div class='container'><div class='box'>Password must contain at least one number.</div></div>";
                    } elseif (!preg_match("/[\W_]/", $password)) {
                        echo "<div class='container'><div class='box'>Password must contain at least one special character.</div></div>";
                    } else {
                        // Hash the new password
                        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

                        // Prepared statement to update password securely
                        $stmt = mysqli_prepare($con, "UPDATE users SET password = ?, reset_token = NULL, reset_token_expiry = NULL WHERE reset_token = ?");
                        mysqli_stmt_bind_param($stmt, 'ss', $hashed_password, $token);

                        if (mysqli_stmt_execute($stmt)) {
                            echo "<div class='container'>
                                    <div class='box'>
                                        Your password has been reset successfully. <a href='login.php'>Click here to login</a>.
                                    </div>
                                  </div>";
                            // Redirect to login page after success
                            header("Location: login.php");
                            exit();
                        } else {
                            echo "<div class='container'><div class='box'>An error occurred. Please try again later.</div></div>";
                        }
                    }
                }
            } else {
                // Show the password reset form
                echo '<div class="container">
                        <div class="box form-box">
                            <header>Reset Password</header>
                            <form action="" method="post">
                                <div class="field input">
                                    <label for="password">New Password:</label>
                                    <input type="password" name="password" required>
                                </div>
                                <div class="field">
                                    <input type="submit" class="btn" name="reset" value="Reset Password">
                                </div>
                            </form>
                        </div>
                      </div>';
            }
        }
    } else {
        echo "<div class='container'><div class='box'>Invalid token. Please request a new reset link.</div></div>";
    }
}
?>

<style>
    body {
        font-family: Arial, sans-serif;
        background-color: #f4f4f9;
        margin: 0;
        padding: 0;
    }

    .container {
        display: flex;
        justify-content: center;
        align-items: center;
        min-height: 100vh;
        padding: 20px;
    }

    .box {
        background-color: #ffffff;
        padding: 30px;
        border-radius: 10px;
        box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
        max-width: 400px;
        width: 100%;
        text-align: center;
    }

    .form-box header {
        font-size: 24px;
        font-weight: bold;
        margin-bottom: 20px;
    }

    .field {
        margin-bottom: 15px;
    }

    .field label {
        display: block;
        margin-bottom: 5px;
        font-weight: bold;
    }

    .field input[type="password"],
    .field input[type="submit"] {
        width: 100%;
        padding: 10px;
        border: 1px solid #ddd;
        border-radius: 5px;
        font-size: 16px;
    }

    .field input[type="password"] {
        margin-bottom: 10px;
    }

    .btn {
        background-color: #007bff;
        color: #fff;
        border: none;
        cursor: pointer;
        font-size: 16px;
        font-weight: bold;
    }

    .btn:hover {
        background-color: #0056b3;
    }
</style>