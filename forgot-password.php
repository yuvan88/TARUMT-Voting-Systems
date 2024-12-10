<?php
session_start();
include("php/config.php");

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Include PHPMailer autoload file
require 'vendor/autoload.php';

if (isset($_POST['submit'])) {
    $email = mysqli_real_escape_string($con, $_POST['email']);

    // Check if the email exists in the database
    $result = mysqli_query($con, "SELECT * FROM users WHERE Email='$email'");
    $row = mysqli_fetch_assoc($result);

    if ($row) {
        // Generate a unique reset token
        $token = bin2hex(random_bytes(50));

        // Save the token and expiration time to the database
        $expiry_time = date("Y-m-d H:i:s", strtotime('+1 hour')); // Token expires in 1 hour
        mysqli_query($con, "UPDATE users SET reset_token='$token', reset_token_expiry='$expiry_time' WHERE Email='$email'");

        // Send password reset email
        $mail = new PHPMailer(true);

        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'www.yuvan9580@gmail.com';  // Replace with your email
            $mail->Password = 'ufhv koqn vvwq tqui';     // Replace with your email app password or environment variable
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            // Recipients
            $mail->setFrom('your_email@gmail.com', 'TARUMT Voting System');  // Replace with your email
            $mail->addAddress($email);

            // Content
            $mail->isHTML(true);
            $mail->Subject = 'Password Reset Request';
            $mail->Body = 'Click the following link to reset your password: <a href="http://localhost/TARUMT-Voting-Systems/reset-password.php?token=' . $token . '">Reset Password</a>';

            $mail->send();
            echo "<div class='message'>
                      <p>If the email exists, a password reset link has been sent to your email. Please check your inbox.</p>
                  </div>";
        } catch (Exception $e) {
            echo "<div class='message'>
                      <p>Message could not be sent. Mailer Error: {$mail->ErrorInfo}</p>
                  </div>";
        }
    } else {
        echo "<div class='message'>
                  <p>If the email exists, a password reset link has been sent to your email. Please check your inbox.</p>
              </div>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
    <link rel="stylesheet" href="style/style.css">
</head>

<body>
    <div class="container">
        <div class="box form-box">
            <header>Forgot Password</header>
            <form action="" method="post">
                <div class="field input">
                    <label for="email">Enter your Email</label>
                    <input type="email" name="email" id="email" autocomplete="off" required>
                </div>

                <div class="field">
                    <input type="submit" class="btn" name="submit" value="Send Reset Link">
                </div>
            </form>
            <div class="field">
                <a href="login.php" class="btn login-btn">Back to Login</a>
            </div>
        </div>
    </div>

    <style>
        .login-btn {
            display: block;
            text-align: center;
            margin-top: 10px;
            background-color: #007bff;
            color: white;
            text-decoration: none;
            padding: 10px 15px;
            border-radius: 5px;
            font-size: 14px;
        }

        .login-btn:hover {
            background-color: #0056b3;
        }

        .message {
            color: green;
            text-align: center;
        }
    </style>
</body>

</html>
