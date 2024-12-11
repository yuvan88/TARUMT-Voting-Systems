<?php
session_start();

// Database connection
$conn = mysqli_connect('localhost', 'root', '', 'votingSystem') or die('Connection failed');

// Check if the user is logged in
if (!isset($_SESSION['valid'])) {
    echo '<p class="message">You must be logged in to vote.</p>';
    exit();
}

// Check if the user has already voted
$user_id = $_SESSION['id'];
$query = "SELECT * FROM votes WHERE user_id = $user_id";
$result = mysqli_query($conn, $query);
$existing_vote = mysqli_fetch_assoc($result);

if ($existing_vote) {
    // If the user has already voted, show an alert and redirect after they click OK
    echo '<script>
            alert("You have already cast your vote. You cannot vote more than once.");
            window.location.href = "index.php";
          </script>';
    exit(); // Stop the script to prevent further actions
}

// Fetch available timeslots for the logged-in user from the appointments database
$query = "SELECT appointment_time, appointment_date FROM appointments WHERE user_id = $user_id";
$result = mysqli_query($conn, $query);
$bookings = mysqli_fetch_all($result, MYSQLI_ASSOC);

// Handle form submission for voting
if (isset($_POST['submit_vote'])) {
    // Collect vote and booking time
    $president = mysqli_real_escape_string($conn, $_POST['president']);
    $booking_time = mysqli_real_escape_string($conn, $_POST['booking_time']);

    // Insert the vote into the database
    $insert = mysqli_query($conn, "INSERT INTO votes (user_id, president, booking_time) 
        VALUES('$user_id', '$president', '$booking_time')");

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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="css/style.css">
    <style>
        /* General Styles */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f9;
            padding-top: 80px;
        }

        /* Header Styling */
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background-color: lightblue;
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
            transition: color 0.3s;
        }

        .navbar a:hover {
            color: #007BFF;
        }

        /* Vote Section */
        .vote-section {
            padding: 80px 20px 20px;
        }

        .vote-section h3 {
            text-align: center;
            font-size: 24px;
            margin-bottom: 20px;
        }

        .message {
            text-align: center;
            color: red;
            font-size: 18px;
            margin: 20px 0;
        }

        .vote-container {
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
            gap: 20px;
            margin: 0 auto;
        }

        .vote-card {
            background: #fff;
            border: 1px solid #ddd;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            width: 280px;
            height: 350px;
            text-align: center;
            padding: 20px;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .vote-card img {
            width: 100%;
            height: 150px;
            object-fit: cover;
            border-radius: 10px;
        }

        .vote-card h4 {
            font-size: 20px;
            margin: 15px 0;
        }

        .vote-card p {
            font-size: 14px;
            color: #666;
        }

        .vote-card input {
            margin-top: 10px;
            transform: scale(1.2);
            cursor: pointer;
        }

        .vote-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 10px rgba(0, 0, 0, 0.15);
        }

        .booking-time {
            margin: 20px 0;
            text-align: center;
        }

        .booking-time select,
        .btn {
            width: 100%;
            max-width: 300px;
            margin: 10px auto;
            padding: 10px;
            font-size: 16px;
            border-radius: 5px;
            border: 1px solid #ddd;
        }

        .btn {
            display: block;
            background-color: #007BFF;
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
            text-decoration: none;
            cursor: pointer;
            border: none;
            font-size: 16px;
            margin: 20px auto;
            text-align: center;
        }

        .btn:hover {
            background-color: #0056b3;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        /* Error Message Styles */
        .error-message {
            background-color: #f8d7da;
            color: #721c24;
            padding: 15px;
            margin: 20px auto;
            text-align: center;
            border-radius: 5px;
            width: 80%;
            max-width: 600px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            font-size: 18px;
            font-weight: bold;
        }

        @media (max-width: 768px) {
            .vote-container {
                flex-direction: column;
                align-items: center;
            }

            .vote-card {
                width: 90%;
            }
        }
    </style>
</head>

<body>

    <header class="header">
        <a href="#" class="logo">
            <img src="image/tarumt.png" alt="TARUMT Logo">
        </a>
        <nav class="navbar">
            <a href="index.php">Home</a>
            <a href="#about">About</a>
            <a href="#rule">Rule</a>
            <a href="#staff">Staff</a>
            <a href="appointment.php">Appointment</a>
            <a href="voter.php">Voter</a>
            <a href="#review">Review</a>
            <a href="#blogs">Blogs</a>
            <a href="dashboard.php">Dashboard</a>
            <a href="register.php">Logout</a>
        </nav>
    </header>

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
            <div class="vote-container">
                <!-- President 1 -->
                <div class="vote-card">
                    <img src="image/vote1.png" alt="President 1">
                    <h4>President 1</h4>
                    <p>Brief description about President 1's achievements.</p>
                    <label>
                        <input type="radio" name="president" value="President 1" required> Select
                    </label>
                </div>

                <!-- President 2 -->
                <div class="vote-card">
                    <img src="image/vote2.png" alt="President 2">
                    <h4>President 2</h4>
                    <p>Brief description about President 2's achievements.</p>
                    <label>
                        <input type="radio" name="president" value="President 2" required> Select
                    </label>
                </div>

                <!-- President 3 -->
                <div class="vote-card">
                    <img src="image/vote3.png" alt="President 3">
                    <h4>President 3</h4>
                    <p>Brief description about President 3's achievements.</p>
                    <label>
                        <input type="radio" name="president" value="President 3" required> Select
                    </label>
                </div>
            </div>

            <div class="booking-time">
                <h4>Select your Booking Time:</h4>
                <select name="booking_time" required>
                    <option value="">Select a time</option>
                    <?php
                    foreach ($bookings as $booking) {
                        echo "<option value='{$booking['appointment_time']}'>Date: {$booking['appointment_date']} - Time: {$booking['appointment_time']}</option>";
                    }
                    ?>
                </select>
            </div>

            <button type="submit" name="submit_vote" class="btn">Cast Vote</button>
        </form>
    </section>

</body>

</html>