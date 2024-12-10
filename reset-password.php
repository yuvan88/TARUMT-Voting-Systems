<?php
session_start();
include("php/config.php");

if (isset($_GET['token'])) {
    $token = $_GET['token'];

    // Check if the token exists in the database
    $result = mysqli_query($con, "SELECT * FROM users WHERE reset_token = '$token'");
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

                // Basic password validation
                if (strlen($password) < 8) {
                    echo "<div class='container'><div class='box'>Password must be at least 8 characters long.</div></div>";
                } else {
                    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

                    // Update the password in the database
                    $update_query = "UPDATE users SET password='$hashed_password', reset_token=NULL, reset_token_expiry=NULL WHERE reset_token='$token'";
                    mysqli_query($con, $update_query);

                    echo "<div class='container'>
                            <div class='box'>
                                Your password has been reset successfully. <a href='login.php'>Click here to login</a>.
                            </div>
                          </div>";
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