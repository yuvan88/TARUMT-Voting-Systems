<?php
session_start();  // Start the session to check user login status

// Database connection
$conn = mysqli_connect('localhost', 'root', '', 'votingSystem') or die('Connection failed');

if (!isset($_SESSION['valid'])) {
    echo '<p class="message">You must be logged in to vote.</p>';
    exit(); // Stop the script if the user is not logged in
}

// Fetch available timeslots for the logged-in user
$user_id = $_SESSION['id'];
$query = "SELECT * FROM contact_form WHERE user_id = $user_id";
$result = mysqli_query($conn, $query);
$bookings = mysqli_fetch_all($result, MYSQLI_ASSOC);

// Handle form submission for voting
if (isset($_POST['submit_vote'])) {

    // Collect vote and booking time
    $president = mysqli_real_escape_string($conn, $_POST['president']);
    $booking_time = mysqli_real_escape_string($conn, $_POST['booking_time']);
    
    // Insert the vote into the database
    $insert = mysqli_query($conn, "INSERT INTO votes (user_id, president, booking_time) 
        VALUES('$user_id', '$president', '$booking_time')") or die('Query failed');

    // Check if the vote was successfully inserted
    if ($insert) {
        $message[] = 'Your vote has been successfully cast!';
    } else {
        $message[] = 'Failed to cast your vote.';
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TARUMT Voting System</title>

    <!-- Font Awesome CDN link -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

    <!-- Custom CSS file link -->
    <link rel="stylesheet" href="css/style.css">

    <style>
        /* open hours section start */
    .OpeningHours {
        background-position: center;
        background-repeat: no-repeat;
        background-size: cover;
        background-attachment: fixed;
        position: relative;
        z-index: 1;
        padding: 5rem 0;
    }

    .OpeningHours::before {
        content: "";
        position: absolute;
        top: 0;
        left: 0;
        height: 100%;
        width: 100%;
        opacity: 0.7;
        z-index: -1;
        background-image: linear-gradient(180deg, #09090b 0%, #09090b 100%);
    }

    .OpeningHours .title {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 15px;
        text-align: center;
    }

    .OpeningHours .title h3 {
        color: #30fdff;
        font-family: "Cherish", Sans-serif;
        font-size: 52px;
        font-weight: 400;
        line-height: 1.2em;
        margin: 0;
    }

    .OpeningHours .title span {
        color: #ffffff;
        font-family: "Montserrat Alternates", Sans-serif;
        font-size: 30px;
        font-weight: 500;
        line-height: 1.2em;
        letter-spacing: -1.5px;
    }

    .OpeningHours .title p {
        color: #c6c6c6;
        font-family: "DM Sans", Sans-serif;
        font-size: 17px;
        font-weight: 400;
        line-height: 1.6em;
    }

    .OpeningHours .content {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 3rem;
        margin-top: 4rem;
        flex-wrap: wrap;
        padding: 0 2rem;
    }

    .OpeningHours .content .map {
        position: relative;
        flex: 1;
        min-width: 300px;
        height: 400px;
        border-radius: 8px;
        overflow: hidden;
    }

    .OpeningHours .content .map iframe {
        object-fit: cover;
        opacity: 0.9;
        min-width: 100%;
        height: 100%;
        border: none;
        outline: none;
    }

    .content .hours {
        flex: 1;
        min-width: 300px;
        background-color: rgba(0, 0, 0, 0.6);
        border-radius: 8px;
        padding: 2rem;
        display: flex;
        flex-direction: column;
        align-items: flex-start;
        gap: 20px;
        color: #ffffff;
    }

    .content .hours .title {
        font-family: "Montserrat Alternates", Sans-serif;
        font-size: 46px;
        font-weight: 500;
        line-height: 1.2em;
        letter-spacing: 1px;
        border-bottom: 1px solid #fff;
        padding-bottom: 10px;
    }

    .content .hours ul {
        width: 100%;
        display: flex;
        flex-direction: column;
        gap: 1.5rem;
    }

    .content .hours ul li {
        display: flex;
        justify-content: space-between;
        font-family: "Work Sans", Sans-serif;
        font-size: 18px;
        font-weight: 400;
    }

    .content .hours ul li span {
        color: #ffffff;
    }

    .content .hours ul li span:first-child {
        font-weight: 500;
    }

    /* open hours section end */

    /* General Styles */
    body {
        margin: 0;
        font-family: Arial, sans-serif;
    }

    /* Header Styling */
    .header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        background-color: lightblue;
        /* Turquoise background */
        padding: 10px 20px;
        position: fixed;
        width: 100%;
        top: 0;
        z-index: 1000;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
    }

    .logo img {
        width: 200px;
        max-width: 100%;
        height: auto;
    }

    /* Navbar Styling */
    .navbar {
        display: flex;
        list-style: none;
    }

    .navbar a {
        text-decoration: none;
        color: black;
        margin: 0 15px;
        font-size: 1rem;
        line-height: 50px;
        /* Center items vertically */
        transition: color 0.3s;
    }

    .navbar a:hover {
        color: #007BFF;
        /* Blue on hover */
    }

    /* Mobile Menu Button */
    #menu-btn {
        display: none;
        font-size: 24px;
        cursor: pointer;
    }

    /* Responsive Design */
    @media screen and (max-width: 768px) {
        .navbar {
            display: none;
            flex-direction: column;
            background-color: #40e0d0;
            position: absolute;
            top: 60px;
            right: 0;
            width: 100%;
            text-align: center;
        }

        .navbar.active {
            display: flex;
        }

        #menu-btn {
            display: block;
        }
    }
        .message {
            color: red;
            font-size: 18px;
        }

        .btn {
            background-color: #007BFF;
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .btn:hover {
            background-color: #0056b3;
        }

        .vote-section {
            margin: 30px 0;
            padding: 20px;
            background-color: #f9f9f9;
            border-radius: 10px;
        }

        .vote-section h3 {
            font-size: 24px;
            margin-bottom: 20px;
        }

        .vote-options {
            margin-bottom: 20px;
        }

        .vote-options input {
            margin-right: 10px;
        }

        .booking-time select {
            padding: 5px 10px;
            font-size: 16px;
            margin-bottom: 20px;
        }
    </style>

</head>

<body>

    <!-- header section starts -->
    <header class="header">
        <a href="#" class="logo">
            <img src="image/tarumt.png" alt="TARUMT Logo">
        </a>
        <nav class="navbar">
            <a href="index.php">Home</a>
            <a href="#about">About</a>
            <a href="#rule">Rule</a>
            <a href="#staff">Staff</a>
            <a href="voter.php">Voter</a>
            <a href="register.php">Logout</a>
        </nav>
    </header>
    <!-- header section ends -->

    <!-- voter section starts -->
    <section class="vote-section">
        <h3>Vote for the Malaysia Student President</h3>

        <?php
        if (isset($message)) {
            foreach ($message as $msg) {
                echo "<p class='message'>$msg</p>";
            }
        }
        ?>

        <form action="voter.php" method="POST">
            <!-- Vote options -->
            <div class="vote-options">
                <h4>Select your President:</h4>
                <label>
                    <input type="radio" name="president" value="President 1" required> President 1
                </label><br>
                <label>
                    <input type="radio" name="president" value="President 2" required> President 2
                </label><br>
                <label>
                    <input type="radio" name="president" value="President 3" required> President 3
                </label>
            </div>

            <!-- Available booking time -->
            <div class="booking-time">
                <h4>Select your Booking Time:</h4>
                <select name="booking_time" required>
                    <option value="">Select a time</option>
                    <?php
                    // Display available booking times for the logged-in user
                    foreach ($bookings as $booking) {
                        echo "<option value='{$booking['time']}'>Time: {$booking['time']}</option>";
                    }
                    ?>
                </select>
            </div>

            <div class="field">
                <input type="submit" name="submit_vote" value="Cast Vote" class="btn">
            </div>
        </form>
    </section>
    <!-- voter section ends -->

    <!-- footer section starts -->
    <section class="footer">
        <div class="box-container">
            <div class="box">
                <h3>quick links</h3>
                <a href="#home"> <i class="fas fa-chevron-right"></i> home </a>
                <a href="#about"> <i class="fas fa-chevron-right"></i> about </a>
                <a href="#rules"> <i class="fas fa-chevron-right"></i> rule </a>
            </div>
        </div>
    </section>
    <!-- footer section ends -->

    <!-- JS file link -->
    <script src="js/script.js"></script>

</body>

</html>
