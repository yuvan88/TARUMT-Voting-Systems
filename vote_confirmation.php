<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['valid'])) {
    header("Location: login.php");
    exit();
}

// Assuming that the vote data is stored in the session or database
// You can fetch the name of the candidate from the database if needed
// For this example, let's assume the session holds the candidate's name they voted for
$user_vote = isset($_SESSION['user_vote']) ? $_SESSION['user_vote'] : "Unknown";

// Ensure security by encoding any special characters
$user_vote = htmlspecialchars($user_vote);
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
        <p>Your vote for <strong><?php echo $user_vote; ?></strong> has been successfully cast.</p>
        <a href="index.php"><button class="btn">Back to Homepage</button></a>
        <a href="logout.php"><button class="btn">Logout</button></a>
    </div>
</body>

</html>