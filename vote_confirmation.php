<?php
session_start();

if (!isset($_SESSION['valid'])) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vote Confirmation</title>
    <link rel="stylesheet" href="style/style.css">
</head>
<body>
    <div class="container">
        <h1>Thank you for voting!</h1>
        <p>Your vote has been successfully cast.</p>
        <a href="index.php"><button class="btn">Back to Homepage</button></a>
    </div>
</body>
</html>
