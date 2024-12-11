<?php
session_start();
include('php/config.php');  // Ensure this path is correct

// Initialize message variable for error or success
$message = array();

// Check if the user is logged in
if (!isset($_SESSION['valid'])) {
    $message[] = "You need to log in to schedule an appointment.";
}

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $appointment_date = mysqli_real_escape_string($con, $_POST['date']);
    $appointment_time = mysqli_real_escape_string($con, $_POST['time']);
    $user_name = mysqli_real_escape_string($con, $_POST['name']);
    $user_email = mysqli_real_escape_string($con, $_POST['email']);
    $user_id = $_SESSION['id'];  // Get the logged-in user's ID

    // Check if the user has already booked an appointment
    $check_existing_appointment_query = "SELECT * FROM appointments WHERE user_id = ?";
    $stmt_check_existing = mysqli_prepare($con, $check_existing_appointment_query);

    if ($stmt_check_existing) {
        mysqli_stmt_bind_param($stmt_check_existing, "i", $user_id);
        mysqli_stmt_execute($stmt_check_existing);
        $result_check_existing = mysqli_stmt_get_result($stmt_check_existing);

        if (mysqli_num_rows($result_check_existing) > 0) {
            // User has already booked an appointment
            $message[] = "You have already booked an appointment. You cannot book another one.";
        } else {
            // Check if the selected slot is already booked by another user
            $check_availability_query = "SELECT * FROM appointments WHERE appointment_date = ? AND appointment_time = ?";
            $stmt = mysqli_prepare($con, $check_availability_query);

            if ($stmt) {
                mysqli_stmt_bind_param($stmt, "ss", $appointment_date, $appointment_time);
                mysqli_stmt_execute($stmt);
                $result = mysqli_stmt_get_result($stmt);

                if (mysqli_num_rows($result) > 0) {
                    // Slot is already booked
                    $message[] = "The selected slot is already booked. Please choose another time.";
                } else {
                    // Slot is available, proceed with booking
                    $insert_appointment_query = "INSERT INTO appointments (user_id, name, email, appointment_date, appointment_time) 
                                                 VALUES (?, ?, ?, ?, ?)";
                    $stmt_insert = mysqli_prepare($con, $insert_appointment_query);

                    if ($stmt_insert) {
                        mysqli_stmt_bind_param($stmt_insert, "issss", $user_id, $user_name, $user_email, $appointment_date, $appointment_time);
                        if (mysqli_stmt_execute($stmt_insert)) {
                            $message[] = "Appointment scheduled successfully!";
                        } else {
                            $message[] = "Error scheduling appointment. Please try again later.";
                        }
                        mysqli_stmt_close($stmt_insert);
                    }
                }
                mysqli_stmt_close($stmt);
            }
        }
        mysqli_stmt_close($stmt_check_existing);
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
    .message {
        color: red;
        font-weight: bold;
        margin-top: 10px;
    }

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

    /* New button container styles */
    /* New button container styles */
    .button-container {
        display: flex;
        justify-content: center;
        /* Align buttons horizontally to the center */
        align-items: center;
        /* Align buttons vertically to the middle */
        position: absolute;
        /* Fixes position relative to the nearest positioned ancestor */
        top: 80%;
        left: 70%;
        /* Horizontally centers the container */
        transform: translateX(-50%);
        /* Adjusts for the actual width of the container */
        height: auto;
    }


    .button-container .btn {
        margin: 0 10px;
        /* Space between buttons */
        padding: 10px 20px;
        color: black;
        text-decoration: none;
        border-radius: 5px;
        text-align: center;
        font-size: 20px;
        transition: background-color 0.3s;
    }

    .button-container .btn:hover {
        background-color: #0056b3;
        /* Darker blue when hovered */
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
            <a href="#home">Home</a>
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
        <div id="menu-btn" class="fas fa-bars">☰</div>
    </header>
    <!-- header section ends -->

    <!-- Appointment Section -->
    <section class="appointment" id="appointment">
        <h1 class="heading"> <span>Appointment</span> now </h1>
        <div class="row">
            <div class="image">
                <img src="image/appoint.png" alt="">
            </div>
            <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
                <?php
                // Display messages (error or success) if they exist
                if (isset($message) && !empty($message)) {
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
        <div class="button-container" style="text-align: center; margin-top: 20px;">
            <a href="candidate_appointment.php" class="btn">Candidate</a> <!-- Button to page 1 -->
            <a href="volunteer_appointment.php" class="btn">Volunteer</a> <!-- Button to page 2 -->
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