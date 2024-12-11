<?php
session_start();
include('php/config.php');  // Ensure this path is correct

// Initialize message variable for error or success
$message = array();

// Ensure the volunteer is logged in
if (!isset($_SESSION['id'])) {
    $message[] = "You must be logged in as a volunteer to schedule an appointment.";
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_SESSION['id'])) {
    $volunteer_id = $_SESSION['id'];  // The volunteer's ID from session
    $volunteer_name = mysqli_real_escape_string($con, $_POST['name']);  // Get volunteer's name from form input
    $volunteer_email = mysqli_real_escape_string($con, $_POST['email']);  // Get volunteer's email from form input
    $appointment_date = mysqli_real_escape_string($con, $_POST['date']);
    $appointment_time = mysqli_real_escape_string($con, $_POST['time']);
    $volunteer_task = mysqli_real_escape_string($con, $_POST['task']);  // New task input

    // Check if the volunteer already has an appointment for the same date and time
    $check_existing_query = "SELECT * FROM volunteer_appointments WHERE volunteer_id = ? AND appointment_date = ? AND appointment_time = ?";
    $stmt_check_existing = mysqli_prepare($con, $check_existing_query);

    if ($stmt_check_existing) {
        mysqli_stmt_bind_param($stmt_check_existing, "iss", $volunteer_id, $appointment_date, $appointment_time);
        mysqli_stmt_execute($stmt_check_existing);
        $result_check_existing = mysqli_stmt_get_result($stmt_check_existing);

        if (mysqli_num_rows($result_check_existing) > 0) {
            // Volunteer already has an appointment for the same date and time
            $message[] = "You already have an appointment at this time.";
        } else {
            // Proceed to schedule the new appointment
            $insert_appointment_query = "INSERT INTO volunteer_appointments (volunteer_id, volunteer_name, volunteer_email, appointment_date, appointment_time, volunteer_task) 
                                         VALUES (?, ?, ?, ?, ?, ?)";
            $stmt_insert = mysqli_prepare($con, $insert_appointment_query);

            if ($stmt_insert) {
                mysqli_stmt_bind_param($stmt_insert, "isssss", $volunteer_id, $volunteer_name, $volunteer_email, $appointment_date, $appointment_time, $volunteer_task);
                if (mysqli_stmt_execute($stmt_insert)) {
                    $message[] = "Volunteer appointment scheduled successfully!";
                } else {
                    $message[] = "Error scheduling appointment. Please try again later.";
                }
                mysqli_stmt_close($stmt_insert);
            } else {
                $message[] = "Error preparing the statement for appointment insertion.";
            }
        }
        mysqli_stmt_close($stmt_check_existing);
    } else {
        $message[] = "Error checking existing appointments. Please try again later.";
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

    .button-container {
        display: flex;
        justify-content: center;
        align-items: center;
        position: absolute;
        top: 80%;
        left: 70%;
        transform: translateX(-50%);
        height: auto;
    }

    .button-container .btn {
        margin: 0 10px;
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

    <!-- Appointment Form -->
    <section class="appointment" id="appointment">
        <h1 class="heading"> <span>Volunteer Appointment</span> </h1>
        <div class="row">
            <div class="image">
                <img src="image/appoint.png" alt="Appointment Image">
            </div>
            <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
                <h3>Volunteer Appointment</h3>

                <!-- Display error messages inside the form -->
                <div>
                    <?php
                    // Show any messages (errors or success)
                    if (isset($message) && !empty($message)) {
                        foreach ($message as $msg) {
                            echo '<p class="message" style="color: red; margin-bottom: 10px;">' . $msg . '</p>';
                        }
                    }
                    ?>
                </div>

                <!-- Name input field for volunteer -->
                <input type="text" name="name" placeholder="Your name" class="box" required>

                <!-- Volunteer email input field -->
                <input type="email" name="email" placeholder="Your email" class="box" required>

                <!-- Time input field with min and max values set -->
                <input type="time" name="time" class="box" id="time" required min="08:00" max="17:00">

                <!-- Date input field with min value set to today -->
                <input type="date" name="date" class="box" required id="dateInput">

                <!-- New task selection dropdown -->
                <select name="task" class="box" required>
                    <option value="">Select Appointment Type</option>
                    <option value="Assist Voters">Assist Voters</option>
                    <option value="Provide Guidance">Provide Guidance</option>
                    <option value="Maintain Orderly Conduct">Maintain Orderly Conduct</option>
                </select>

                <input type="submit" name="submit" value="Schedule Appointment" class="btn">
            </form>
        </div>
        <div class="button-container" style="text-align: center; margin-top: 20px;">
            <a href="appointment.php" class="btn">Appointment</a> <!-- Button to page 1 -->
            <a href="candidate_appointment.php" class="btn">Candidate</a> <!-- Button to page 2 -->
        </div>
    </section>

    <script src="js/script.js"></script>
    <script>
        // Disable past dates in the date input field
        document.addEventListener('DOMContentLoaded', function () {
            var dateInput = document.getElementById('dateInput');
            var today = new Date();

            var dd = today.getDate();
            var mm = today.getMonth() + 1;
            var yyyy = today.getFullYear();

            if (dd < 10) dd = '0' + dd;
            if (mm < 10) mm = '0' + mm;

            var todayFormatted = yyyy + '-' + mm + '-' + dd;

            dateInput.setAttribute('min', todayFormatted);
        });

        document.getElementById('time').addEventListener('input', function () {
            const time = this.value;
            const minTime = '08:00';
            const maxTime = '17:00';

            if (time < minTime || time > maxTime) {
                alert("Invalid time selection. Please choose a time between 08:00 and 17:00.");
                this.value = '';
            }
        });
    </script>
</body>

</html>