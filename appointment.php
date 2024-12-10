<?php
session_start();  // Start the session to check user login status

// Database connection
$conn = mysqli_connect('localhost', 'root', '', 'votingSystem') or die('Connection failed');

if (!isset($_SESSION['valid'])) {
    echo '<p class="message">You must be logged in to make an appointment.</p>';
    exit(); // Stop the script if the user is not logged in
}

if (isset($_POST['submit'])) {
    // Collect form data and sanitize it
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $time = $_POST['time'];  // Time input
    $date = $_POST['date'];  // Date input

    // Get user ID from session to link with the appointment
    $user_id = $_SESSION['id'];  // Assuming the user ID is stored in the session

    // Check if the user already has an appointment
    $check_appointment = mysqli_query($conn, "SELECT * FROM `contact_form` WHERE `user_id` = '$user_id'") or die('Query failed');

    if (mysqli_num_rows($check_appointment) > 0) {
        // If user already has an appointment, show error message
        $message[] = 'You have already made an appointment!';
    } else {
        // Get the current date
        $today = date('Y-m-d'); // Format today's date as Y-m-d

        // Check if the selected date is in the past
        if ($date < $today) {
            $message[] = 'You cannot book an appointment for a past date.';
        } else {
            // Insert the appointment details into the contact_form table
            $insert = mysqli_query($conn, "INSERT INTO `contact_form`(name, email, time, date, user_id) 
                VALUES('$name', '$email', '$time', '$date', '$user_id')") or die('Query failed');

            // Check if the appointment was successfully inserted
            if ($insert) {
                $message[] = 'Appointment made successfully!';
            } else {
                $message[] = 'Appointment failed.';
            }
        }
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

    <!-- font awesome cdn link  -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

    <!-- custom css file link  -->
    <link rel="stylesheet" href="css/style.css">
</head>
<style>
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
</style>

<body>

    <!-- header section starts  -->
    <header class="header">
        <a href="#" class="logo">
            <img src="image/tarumt.png" alt="TARUMT Logo">
        </a>
        <nav class="navbar">
            <a href="index.php">Home</a>
            <a href="#about">About</a>
            <a href="#rule">Rule</a>
            <a href="#staff">Staff</a>
            <a href="#appointment">Appointment</a>
            <a href="voter.php">Voter</a>
            <a href="#review">Review</a>
            <a href="#blogs">Blogs</a>
            <a href="register.php">Logout</a>
        </nav>
        <div id="menu-btn" class="fas fa-bars">☰</div>
    </header>
    <!-- header section ends -->

    <!-- Appointment Section -->
    <section class="appointment" id="appointment">
        <h1 class="heading"> <span>appointment</span> now </h1>
        <div class="row">
            <div class="image">
                <img src="image/appoint.png" alt="">
            </div>
            <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
                <?php
                // Check if there are any messages in the $message array
                if (isset($message) && !empty($message)) {
                    // Loop through and display each message
                    foreach ($message as $msg) {
                        echo '<p class="message" style="color: red;">' . $msg . '</p>';
                    }
                }
                ?>
                <h3>Make Appointment</h3>
                <input type="text" name="name" placeholder="Your name" class="box" required>
                <input type="email" name="email" placeholder="Your email" class="box" required>

                <!-- Time input field with min and max values set -->
                <input type="time" name="time" class="box" id="time" required min="08:00" max="17:00">

                <!-- Date input field with min value set to today -->
                <input type="date" name="date" class="box" required id="dateInput">
                <input type="submit" name="submit" value="Appointment Now" class="btn">
            </form>
        </div>
    </section>
    <!-- Appointment Section Ends -->

    <!-- JS file link -->
    <script src="js/script.js"></script>
    <script>
        // Disable past dates in the date input field
        document.addEventListener('DOMContentLoaded', function () {
            var dateInput = document.getElementById('dateInput');
            var today = new Date();

            // Format the date to YYYY-MM-DD
            var dd = today.getDate();
            var mm = today.getMonth() + 1; // Months are zero-based
            var yyyy = today.getFullYear();

            // Add leading zero to day and month if necessary
            if (dd < 10) dd = '0' + dd;
            if (mm < 10) mm = '0' + mm;

            // Today's date in YYYY-MM-DD format
            var todayFormatted = yyyy + '-' + mm + '-' + dd;

            // Set the 'min' attribute of the date input to today's date
            dateInput.setAttribute('min', todayFormatted);
        });

        // JavaScript to ensure time selection is within the range
        document.getElementById('time').addEventListener('input', function () {
            const time = this.value;
            const minTime = '08:00';
            const maxTime = '17:00';

            // If the selected time is outside the range, reset the value to the last valid time
            if (time < minTime || time > maxTime) {
                alert("Invalid time selection. Please choose a time between 08:00 and 17:00.");
                this.value = '';  // Reset the time field
            }
        });
    </script>
</body>

</html>