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
            echo "The reset token has expired.";
        } else {
            // Token is valid, show the password reset form
            if (isset($_POST['reset'])) {
                $password = mysqli_real_escape_string($con, $_POST['password']);
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);

                // Update the password in the database
                $update_query = "UPDATE users SET password='$hashed_password', reset_token=NULL, reset_token_expiry=NULL WHERE reset_token='$token'";
                mysqli_query($con, $update_query);

                echo "Your password has been reset successfully.";
            } else {
                // Show the password reset form
                echo '<form action="" method="post">
                        <label for="password">New Password:</label>
                        <input type="password" name="password" required>
                        <input type="submit" name="reset" value="Reset Password">
                    </form>';
            }
        }
    } else {
        echo "Invalid token.";
    }
}
?>
